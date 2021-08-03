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
        $cityCodePostal = $request->query->get('codesPostaux');
        $cityName = $request->query->get('nom');
        $city = new City();
        $city->setName($cityName);
        $city->setCodePostal($cityCodePostal[0]);

        return $this->render('outling/index.html.twig', [
            'controller_name' => 'OutlingController',
        ]);
    }
}
