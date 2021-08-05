<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Outings;
use App\Form\OutingsFormType;
use App\Repository\CityRepository;
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
                $city = new City();
                $city->setName($request->get('ville'))
                    ->setCodePostal($request->get('codePostal'));
                $em = $this->getDoctrine()
                    ->getManager();
                $query = $this->getDoctrine()
                    ->getRepository(City::class)
                    ->findOneBy(
                        ['codePostal' => $city->getCodePostal()]
                    );
                if(!$query){
                    $em->persist($city);
                    $outing->setCity($city);
                }
                else
                    $outing->setCity($query);
                $em->persist($outing);
                $em->flush();
            return new Response('Sortie bien enregistré.');
        }

        return $this->render('outings/outings.html.twig', [
            'outingForm' => $outingForm->createView()
        ]);


    }
}
