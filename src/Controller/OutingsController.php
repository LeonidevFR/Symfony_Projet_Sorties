<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Outings;
use App\Form\OutingsFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingsController extends AbstractController
{
    /**
     * @Route("/outings", name="app_outings")
     */

    public function createOuting(Request $request): Response
    {
        $outing = new Outings();
        $outingForm = $this->createForm(OutingsFormType::class, $outing);

        $outingForm->handleRequest($request);

            if($outingForm->isSubmitted() && $outingForm->isValid()){
                dd($outing);
                $city = new City();
                dd($outingForm->getData());
                $city->setName($outingForm->get('city')->getData());
                $outing->setCity($city);
                dump($outing);
                $em = $this->getDoctrine()->getManager();
                $em->persist($outing);
                $em->flush();
                return new Response('Sortie bien enregistré.');
        }

        return $this->render('outings/outings.html.twig', [
            'outingForm' => $outingForm->createView()
        ]);


    }
}
