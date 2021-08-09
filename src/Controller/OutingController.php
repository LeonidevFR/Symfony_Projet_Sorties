<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Outings;
use App\Form\OutingsFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/outing", name="app_outing")
 */
class OutingController extends AbstractController
{
    /**
     * @Route("/{id}", name="_view")
     * requirements={"id": "\d+"}
     */
    public function outingView($id): Response
    {
        $view = $this->getDoctrine()->getRepository(Outings::class)->find($id);

        if(!$view){
            throw $this->createNotFoundException('Aucune sortie ne correspond a l\'ID'.$id);
        }
        return $this->render('outings/view.html.twig', [
            'controller_name' => 'OutingViewController',
            'view' => $view,
        ]);
    }

    /**
     * @Route("/create", name="_create")
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
     * @Route("/edit/{id}", name="_edit")
     * requirements={"id": "\d+"}
     */
    public function edit(Request $request, Outings $outing)
    {

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
            $em->flush();

        }
        return $this->render('outings/edit.html.twig', [
            'outingForm' => $outingForm->createView()
        ]);
    }

    /**
     * @Route("/subscription/{id}", name="_subscription")
     *  requirements={"id": "\d+"}
     */
    public function subscription($id)
    {
        $SortieRepository = $this->getDoctrine()->getRepository(Outings::class);
        $sortie = $SortieRepository->find($id);

        // on inscrit l'utilisateur que si le côtat le permet
        if(count($sortie->getMembers()) < $sortie->getSpotNumber())
        {
            $sortie->addMember($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
        }
        else
        {
            $this->addFlash('danger', "Dommage il semblerait qu'il n'y est plus de place pour cette sortie ! ");
        }

        return $this->redirectToRoute('app_main_home');
    }

    /**
     * @Route("/unsubscribe/{id}", name="_unsubscribe",
     *     requirements={"id": "\d+"})
     */
    public function unsuscribe(int $id)
    {
        $sortieRepository = $this->getDoctrine()->getRepository(Outings::class);
        $sortie = $sortieRepository->find($id);

        if (!$sortie) {
            $this->addFlash('danger', "Cette sortie n'existe pas !");
            return $this->redirectToRoute('app_main_home');
        }

        // supprime l'utilisateur de la liste des participants
        $sortie->removeMember($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('app_main_home');
    }

}
