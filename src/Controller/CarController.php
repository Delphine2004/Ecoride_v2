<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\CarType;
use App\Enum\UserRole;
use App\Enum\CarBrand;
use App\Enum\CarColor;
use App\Enum\CarPower;
use App\Repository\CarRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;

#[IsGranted(UserRole::DRIVER->value)]
#[Route('/car')]
final class CarController extends AbstractController
{

    public function __construct(
        private Security $security
    ) {}

    #[Route('/{id}', name: 'app_car_index', methods: ['GET'])]
    public function index(
        CarRepository $carRepository
    ): Response {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('car/index.html.twig', [
            'cars' => $carRepository->findCarsByDriver($user->getId()),
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'Voiture ajouté avec succés.');
            return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Car $car,
        carRepository $carRepository,
    ): Response {

        // Vérification que la requête est valide
        if (!$this->isCsrfTokenValid(
            'delete' . $car->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        // Vérification que l'utilisateur est connecté
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }


        // Empêche un client de supprimer la voiture d'un autre
        if ($carRepository->isOwner($user, $car->getId()) && !$this->isGranted('ROLE_EMPLOYE')) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé.');
            return $this->redirectToRoute('app_login');
        }

        if (!$carRepository->hasCar($user)) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas supprimer toutes les voitures.'
            );
            return $this->redirectToRoute('app_car_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        // Vérifier que la voiture n'est pas attaché à un trajet
        if ($car->getRides()) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas supprimer votre compte pendant un séjour en cours.'
            );
            return $this->redirectToRoute('app_car_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        $car->setBrand(CarBrand::NA);
        $car->setModel('NA');
        $car->setColor(CarColor::NA);
        $car->setYear('NA');
        $car->setPower(CarPower::NA);
        $car->setSeats(0);
        $car->setRegistrationNumber('NA');
        $car->setRegistrationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $car->setOwner(null);

        $this->addFlash(
            'success',
            'Voiture supprimée avec succés.'
        );
        return $this->redirectToRoute('app_car_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }
}
