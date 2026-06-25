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
            return $this->redirectToRoute('app_car_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

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
}
