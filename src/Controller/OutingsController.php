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

        if ($outingForm->isSubmitted()) {
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
            if (!$query) {
                $em->persist($city);
                $outing->setCity($city);
            } else
                $outing->setCity($query);
            $em->persist($outing);
            $em->flush();
            return new Response('Sortie bien enregistré.');

        }

        return $this->render('outings/outings.html.twig', [
            'outingForm' => $outingForm->createView()
        ]);
    }

    /**
     * @Route("outings/edit", name="app_edit_outings")
     */
    public function editOuting($id)
    {

        $repository = $this->getDoctrine()->getRepository(Outings::class);
        $outings = $repository->find($id);


        return $this->render('outings/edit.html.twig', [
            'edit' => $outings
        ]);

    }


    /**
     * @Route("outings/edit/{id}", name="app_edit_outings_id")
     */

    public function edit(Request $request, outings $outings)
    {

        $outingForm = $this->createForm(OutingsFormType::class, $outings);

        $outingForm->handleRequest($request);

        if ($outingForm->isSubmitted()) {
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
            if (!$query) {
                $em->persist($city);
                $outings->setCity($city);
            } else
                $outings->setCity($query);
            $em->flush();

        }
        return $this->render('outings/edit.html.twig', [
            'outingForm' => $outingForm->createView()
        ]);
    }
}
