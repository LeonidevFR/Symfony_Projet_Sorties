<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Outings;
use App\Form\OutingsFormType;
use App\Repository\StatusRepository;
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

        if ($view->getStatus($statusRepository->findStatusByName('En création'))) {
            $view->getStatus($statusRepository->findStatusByName('En création'));
        } elseif(($str_date_outing > $nowstr) && ($spots_taken < $total_spots_number) && ($str_date_end_subscription > $nowstr)) {
            $view->setStatus($statusRepository->findStatusByName('Ouvert'));
        } elseif ((($str_date_outing > $nowstr) && ($spots_taken >= $total_spots_number))) {
            $view->setStatus($statusRepository->findStatusByName('Fermé'));
        } elseif ($str_date_outing < $nowstr && $outing_end_str > $nowstr) {
            $view->setStatus($statusRepository->findStatusByName('En cours'));
        } elseif ($str_date_outing < $nowstr) {
            $view->setStatus($statusRepository->findStatusByName('Passé'));
        }
        $user = $this->getUser();
        $userMember = false;
        foreach ($view->getMembers() as $member){
            if($member == $this->getUser())
                $userMember = true;
        }
        return $this->render('outings/view.html.twig', [
            'controller_name' => 'OutingController',
            'view' => $view,
            'dateliimite' => $str_date_end_subscription,
            'userMember' => $userMember,
            'status' => $view->getStatus(),
            'outing' => $view
        ]);
    }

    /**
     * @Route("/create", name="_create")
     */
    public function createOuting(Request $request, StatusRepository $statusRepository): Response
    {
        $outing = new Outings();
        $outingForm = $this->createForm(OutingsFormType::class, $outing);
        $cityName = "";
        $zipCode = "";
        $errorCity = false;
        $errorZipCode = false;
        $outingForm->handleRequest($request);
        if($outingForm->isSubmitted()) {
            $cityName = $request->get('ville');
            $zipCode = $request->get('codePostal');
            if(strlen($cityName) <= 1) {
                $errorCity = true;
            }
            if(strlen($zipCode) != 5) {
                $errorZipCode = true;
            }
            if ($outingForm->isValid() && !$errorCity && !$errorZipCode) {
                $city = new City();
                $city->setName($cityName)
                    ->setCodePostal($zipCode);
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

                $outing->setAuthor($this->getUser());
                $outing->setStatus($statusRepository->findStatusByName('En création'));
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', ('Sortie bien enregistré.'));
                return $this->redirectToRoute('app_outing_view',['id' => $outing->getId()]);
            }
        }
        return $this->render('outings/outings.html.twig', [
            'outingForm' => $outingForm->createView(),
            'zipCode' => $zipCode,
            'cityName' => $cityName,
            'errorZipCode' => $errorZipCode,
            'errorCity' => $errorCity,
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
            if($role == "ROLE_ADMIN")
                $admin = true;
        }
        if((!$admin && $user != $outing->getAuthor()) || (!$admin && $outing->getStatus() == "Passé")) {
            //$request->getSession()->getFlashBag()->add('access_denied', 'Vous n\'avez pas les permissions pour accèder à cette page.');
            $this->addFlash('access_denied', "Vous n'avez pas les permissions pour accèder à cette page.");
            //return new RedirectResponse('http://localhost/CloneProjetSorties/public/');
            return $this->redirectToRoute('app_main_home');
        } else {
            $errorCity = false;
            $errorZipCode = false;
            $oldcity = $outing->getCity();
            $outing->setCity($oldcity);
            $outingForm = $this->createForm(OutingsFormType::class, $outing);
            $outingForm->handleRequest($request);

            if ($outingForm->isSubmitted()) {
                $cityName = $request->get('ville');
                $zipCode = $request->get('codePostal');
                if(strlen($cityName) <= 1) {
                    $errorCity = true;
                }
                if(strlen($zipCode) != 5) {
                    $errorZipCode = true;
                }
                if($outingForm->isValid() && !$errorZipCode && !$errorCity) {
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
                    $this->addFlash('success', ('Modification bien enregistrée.'));
                    return $this->redirectToRoute('app_outing_view', ['id' => $outing->getId()]);
                }
            }
        }
        return $this->render('outings/edit.html.twig', [
            'outingForm' => $outingForm->createView(),
            'cityName' => $oldcity->getName(),
            'zipCode' => $oldcity->getCodePostal(),
            'errorZipCode' => $errorZipCode,
            'errorCity' => $errorCity,
            'status' => $outing->getStatus(),
            'outing' => $outing
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
        if($sortie->getStatus()->getWording() != "Ouvert"){
            $this->addFlash('warning', "Vous ne pouvez pas vous inscrire à cette sortie.");
            return $this->redirectToRoute('app_main_home');
        }
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
        if($sortie->getStatus()->getWording() != "Ouvert"){
            $this->addFlash('warning', "Vous ne pouvez pas vous désistez de cette sortie.");
            return $this->redirectToRoute('app_main_home');
        }

        // supprime l'utilisateur de la liste des participants
        $sortie->removeMember($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('app_main_home');
    }

    /**
     * @Route("/publish/{id}", name="_publish",
     *     requirements={"id": "\d+"})
     */
    public function publish(Outings $outing, StatusRepository $statusRepository)
    {
        $user = $this->getUser();
        $admin = false;
        foreach ($user->getRoles() as $role){
            if($role == "ROLE_ADMIN")
                $admin = true;
        }
        if($this->getUser() != $outing->getAuthor() && !$admin) {
            $this->addFlash('access_denied', "Vous n\'avez pas les permissions pour accèder à cette page.");
            return $this->redirectToRoute('app_main_home');
        } else {
            $em = $this->getDoctrine()->getManager();
            if($outing->getStatus()->getWording() != "En création"){
                $this->addFlash('warning', "Vous ne pouvez pas publier cette sortie.");
                return $this->redirectToRoute('app_main_home');
            }
            $outing->setStatus($statusRepository->findStatusByName('Ouvert'));
            $em->persist($outing);
            $em->flush();
            $this->addFlash('success_outing', "Vous avez publié cette sortie !");
        }
        return $this->redirectToRoute('app_main_home');
    }

    /**
     * @Route("/cancel/{id}", name="_cancel",
     *     requirements={"id": "\d+"})
     */
    public function cancel(Outings $outing, StatusRepository $statusRepository)
    {
        $user = $this->getUser();
        $admin = false;
        foreach ($user->getRoles() as $role){
            if($role == "ROLE_ADMIN")
                $admin = true;
        }
        if($this->getUser() != $outing->getAuthor() && !$admin) {
            $this->addFlash('access_denied', 'Vous n\'avez pas les permissions pour accèder à cette page.');
            return $this->redirectToRoute('app_main_home');
            //return new RedirectResponse('http://localhost/CloneProjetSorties/public/');
        } else {
            if($outing->getStatus()->getWording() != "En création" || $outing->getStatus()->getWording() != "Ouvert"){
                $this->addFlash('warning', "Vous ne pouvez pas annuler cette sortie.");
                return $this->redirectToRoute('app_main_home');
            }
            $outing->setStatus($statusRepository->findStatusByName('Annulé'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($outing);
            $em->flush();
        }
        return $this->redirectToRoute('app_main_home');
    }

}
