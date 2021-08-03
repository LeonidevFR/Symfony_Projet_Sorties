<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutlingController extends AbstractController
{
    /**
     * @Route("/outling", name="outling")
     */
    public function index(Request $request): Response
    {
        return $this->render('outling/index.html.twig', [
            'controller_name' => 'OutlingController',
        ]);
    }
}
