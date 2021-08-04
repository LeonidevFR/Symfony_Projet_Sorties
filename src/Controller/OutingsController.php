<?php

namespace App\Controller;

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

            if($outingForm->isSubmitted()){

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
