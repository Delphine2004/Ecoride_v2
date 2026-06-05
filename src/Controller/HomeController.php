<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Form\SearchRideType;
use App\Repository\RideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(
        Request $request,
        RideRepository $rideRepository
    ): Response {

        $form = $this->createForm(SearchRideType::class);
        $form->handleRequest($request);

        $rides = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $rides = $rideRepository->findRidesByField($data);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'rides' => $rides,
            'controller_name' => 'HomeController',
        ]);
    }
}
