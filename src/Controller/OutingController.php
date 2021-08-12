<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Outings;
use App\Entity\User;
use App\Form\OutingsFormType;
use App\Repository\OutingsRepository;
use App\Repository\StatusRepository;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/view/{id}", name="_view")
     * requirements={"id": "\d+"}
     */
    public function outingView($id, StatusRepository $statusRepository): Response
    {
        $view = $this->getDoctrine()->getRepository(Outings::class)->find($id);

        if(!$view){
            throw $this->createNotFoundException('Aucune sortie ne correspond a l\'ID'.$id);
        }

        $nowstr = date("d/m/Y H:i:s");
        $str_date_outing = date_format($view->getDateHourOuting(), "d/m/Y H:i:s");
        $str_date_end_subscription = date_format($view->getDateInscriptionLimit(), "d/m/Y H:i:s");
        $date_outing = date_create_from_format("d/m/Y H:i:s", $str_date_outing);
        $outing_duration = $view->getDuration();
        $date_outing_end = date_modify($date_outing, '+' . $outing_duration . 'minutes');
        $outing_end_str = date_format($date_outing_end, "d/m/Y H:i:s");
        $spots_taken = count($view->getMembers());
        $total_spots_number = $view->getSpotNumber();

        if (($str_date_outing > $nowstr) && ($spots_taken < $total_spots_number) && ($str_date_end_subscription > $nowstr)) {
            $view->setStatus($statusRepository->findStatusByName('Ouvert'));
        } elseif ((($str_date_outing > $nowstr) && ($spots_taken >= $total_spots_number)) || ($str_date_end_subscription < $nowstr)) {
            $view->setStatus($statusRepository->findStatusByName('Fermé'));
        } elseif ($str_date_outing < $nowstr && $outing_end_str > $nowstr) {
            $view->setStatus($statusRepository->findStatusByName('En cours'));
        } elseif ($str_date_outing < $nowstr) {
            $view->setStatus($statusRepository->findStatusByName('Passé'));
        }

        return $this->render('outings/view.html.twig', [
            'controller_name' => 'OutingController',
            'view' => $view,
            'dateliimite' => $str_date_end_subscription

        ]);
    }

    /**
     * @Route("/create", name="_create")
     */
    public function createOuting(Request $request, StatusRepository $statusRepository): Response
    {
        $outing = new Outings();
        $outingForm = $this->createForm(OutingsFormType::class, $outing);

        $outingForm->handleRequest($request);

        if ($outingForm->isSubmitted() && $outingForm->isValid()) {
            $city = new City();
            $cityName = $request->get('ville');
            $codePostal = $request->get('codePostal');
            $city->setName($cityName)
                ->setCodePostal($codePostal);
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

            if ($outingForm->isValid()) {
                $outing->setAuthor($this->getUser());
                $em->persist($outing);
                $em->flush();
                return new Response('Sortie bien enregistré.');
            }
        }

        return $this->render('outings/outings.html.twig', [
            'outingForm' => $outingForm->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     * requirements={"id": "\d+"}
     */
    public function edit(Request $request, Outings $outing)
    {
        $user = $this->getUser();
        $admin = false;
        foreach ($user->getRoles() as $role){
            dump($role);
            if($role == "ROLE_ADMIN")
                $admin = true;
        }
        dump($admin);
        if((!$admin && $user != $outing->getAuthor()) || (!$admin && $outing->getStatus() == "Passé")) {
            //$request->getSession()->getFlashBag()->add('access_denied', 'Vous n\'avez pas les permissions pour accèder à cette page.');
            $this->addFlash('access_denied', "Vous n'avez pas les permissions pour accèder à cette page.");
            //return new RedirectResponse('http://localhost/CloneProjetSorties/public/');
            return $this->redirectToRoute('app_main_home');
        } else {
            $oldcity = $outing->getCity();
            $oldcity->getName();
            $outing->setCity($oldcity);
            $outingForm = $this->createForm(OutingsFormType::class, $outing);
            $outingForm->handleRequest($request);
            if ($outingForm->isSubmitted() && $outingForm->isValid()) {
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
        }

        return $this->render('outings/edit.html.twig', [
            'outingForm' => $outingForm->createView(),
            'cityName' => $oldcity->getName(),
            'zipCode' => $oldcity->getCodePostal()
        ]);
    }

    /**
     * @Route("/subscribe/{id}", name="_subscribe")
     *  requirements={"id": "\d+"}
     */
    public function subscribe($id)
    {
        $SortieRepository = $this->getDoctrine()->getRepository(Outings::class);
        $sortie = $SortieRepository->find($id);

        // on inscrit l'utilisateur que si le quotas le permet
        if(count($sortie->getMembers()) < $sortie->getSpotNumber())
        {
            $sortie->addMember($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
        }
        else
        {
            $this->addFlash('danger', "Dommage il semblerait qu'il n'y ai plus de place pour cette sortie ! ");
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

    /**
     * @Route("/remove/{id}", name="_remove",
     *     requirements={"id": "\d+"})
     */
    public function remove(Request $request, Outings $outing)
    {
        $user = $this->getUser();
        $admin = false;
        foreach ($user->getRoles() as $role){
            dump($role);
            if($role == "ROLE_ADMIN")
                $admin = true;
        }
        if($this->getUser() != $outing->getAuthor() && !$admin) {
            $request->getSession()->getFlashBag()->add('access_denied', 'Vous n\'avez pas les permissions pour accèder à cette page.');
            //return new RedirectResponse('http://localhost/CloneProjetSorties/public/');
            return $this->redirectToRoute('app_main_home');
        } elseif ($this->getUser() == $outing->getAuthor()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($outing);
            $em->flush();
            return $this->redirectToRoute('app_main_home');
        }
    }

}
