<?php

namespace App\Controller;

use App\Entity\Car;
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


#[IsGranted(UserRole::DRIVER->value)]
#[Route('/car')]
final class CarController extends AbstractController
{

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('success', 'Voiture ajouté avec succés.');
            return $this->redirectToRoute('app_dashboard_user', [], Response::HTTP_SEE_OTHER);
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

        $userConnected = $this->getUser();

        // Vérification que l'utilisateur est connecté
        if (!$userConnected) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }


        // Empêche un client de supprimer la voiture d'un autre
        if ($carRepository->isOwner($userConnected, $car->getId()) && !$this->isGranted('ROLE_EMPLOYE')) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifier que la voiture n'est pas attaché à un trajet
        if ($car->getRides()) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas supprimer votre compte pendant un séjour en cours.'
            );
            return $this->redirectToRoute('app_dashboard_user', [], Response::HTTP_SEE_OTHER);
        }

        $car->setBrand(CarBrand::NA->value);
        $car->setModel('NA');
        $car->setColor(CarColor::NA->value);
        $car->setYear('NA');
        $car->setPower(CarPower::NA->value);
        $car->setSeats(0);
        $car->setRegistrationNumber('NA');
        $car->setRegistrationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $car->setOwner(null);

        $this->addFlash(
            'success',
            'Voiture supprimée avec succés.'
        );
        return $this->redirectToRoute('app_dashboard_user', [], Response::HTTP_SEE_OTHER);
    }
}
