<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\RideType;
use App\Enum\UserRole;
use App\Enum\RideStatus;
use App\Form\SearchRideType;
use App\DTO\SearchRideDTO;
use App\Repository\RideRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted(UserRole::DRIVER->value)]
#[Route('/ride')]
final class RideController extends AbstractController
{

    #[Route(name: 'app_ride_index', methods: ['GET', 'POST'])]
    public function index(
        RideRepository $rideRepository,
        Request $request,
    ): Response {

        $user = $this->getUser();
        $dto = new SearchRideDTO();

        $form = $this->createForm(SearchRideType::class, $dto, ['mode' => 'searchByStaff']);
        $form->handleRequest($request);

        $rides = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $rides = $rideRepository->findRidesByFields(
                $data
            );
        }
        return $this->render('ride/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'rides' => $rides
        ]);
    }

    #[Route('/new', name: 'app_ride_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $ride = new Ride();
        $ride->setStatus(RideStatus::CONFIRMED);
        $form = $this->createForm(RideType::class, $ride, [
            'user' => $user,
            'mode' => 'create'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($ride);
                $entityManager->flush();

                $this->addFlash('success', 'Trajet ajouté avec succés.');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('app_home');
            }
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('ride/new.html.twig', [
            'ride' => $ride,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_ride_show', methods: ['GET'])]
    public function show(
        Ride $ride
    ): Response {
        return $this->render('ride/show.html.twig', [
            'ride' => $ride,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_ride_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Ride $ride,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(RideType::class, $ride, ['user' => $this->getUser(), 'mode' => 'update']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Voiture modifiée.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ride/edit.html.twig', [
            'ride' => $ride,
            'form' => $form->createView()
        ]);
    }
}
