<?php


namespace App\Controller;

use App\Entity\Outings;
use App\Form\ListOutingsFormType;
use App\Repository\OutingsRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Timezone;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main_home")
     */
    public function home(Request $request, StatusRepository $statusRepository)
    {
        $form = $this->createForm(ListOutingsFormType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Outings::class);
        $campus = $form->get('campus')->getData();
        $choiceAuthor = $form->get('choiceAuthor')->getData();
        $choiceRegistered = $form->get('choiceRegistered')->getData();
        $choiceNotRegistered = $form->get('choiceNotRegistered')->getData();
        if($form->isSubmitted() && $form->isValid() && ($campus != null || $choiceAuthor == true || $choiceRegistered == true || $choiceNotRegistered == true)){
            $results = $repository->findByParameter($user,$campus,$choiceAuthor,$choiceRegistered,$choiceNotRegistered);
        } else {
            $results = $this->getDoctrine()->getRepository(Outings::class)->findAll();
        }


        $outingsRepo = $this->getDoctrine()->getRepository(Outings::class);
        $all_outings = $outingsRepo->findAll();

        foreach ($all_outings as $outing) {
            date_default_timezone_set('Europe/Paris');
            $nowstr = date("d/m/Y H:i:s");
            $str_date_outing = date_format($outing->getDateHourOuting(), "d/m/Y H:i:s");
            $str_date_end_subscription = date_format($outing->getDateInscriptionLimit(), "d/m/Y H:i:s");
            $date_outing = date_create_from_format("d/m/Y H:i:s", $str_date_outing);
            $outing_duration = $outing->getDuration();
            $date_outing_end = date_modify($date_outing, '+'.$outing_duration.'minutes');
            $outing_end_str = date_format($date_outing_end, "d/m/Y H:i:s");
            $spots_taken = count($outing->getMembers());
            $total_spots_number = $outing->getSpotNumber();

            if (($str_date_outing > $nowstr) && ($spots_taken < $total_spots_number) && ($str_date_end_subscription > $nowstr)) {
                $outing->setStatus($statusRepository->findStatusByName('Ouvert'));
            } elseif ((($str_date_outing > $nowstr) && ($spots_taken >= $total_spots_number))) {
                $outing->setStatus($statusRepository->findStatusByName('Fermé'));
            } elseif ($str_date_outing < $nowstr && $outing_end_str > $nowstr) {
                $outing->setStatus($statusRepository->findStatusByName('En cours'));
            } elseif ($str_date_outing < $nowstr) {
                $outing->setStatus($statusRepository->findStatusByName('Passé'));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($outing);
            $em->flush();
        }

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'outings' => $all_outings
        ]);
    }

    /**
     * @Route("/nope", name="app_you_shall_not_pass")
     */
    public function youshallnotpass()
    {
        return $this->render('outings/youshallnotpass.html.twig');
    }

}