<?php


namespace App\Controller;

use App\Entity\Outings;
use App\Entity\User;
use App\Form\ListOutingsFormType;
use App\Repository\OutingsRepository;
use App\Repository\StatusRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Timezone;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main_home")
     */
    public function home(Request $request,PaginatorInterface $paginator, StatusRepository $statusRepository)
    {
        $form = $this->createForm(ListOutingsFormType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $repositoryOutings = $this->getDoctrine()->getRepository(Outings::class);
        if($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $campus = $datas['campus'] ?? null;
            $contains = $datas['nameContains'] ?? null;
            $dateDebut = $datas['dateStart'];
            $dateFin = $datas['dateEnd'];
            if($datas['choiceAuthor']){
                $isAuthor = $datas['choiceAuthor'];
            } else {
                $isAuthor = null;
            }
            if($datas['choiceRegistered']){
                $isRegistered = $datas['choiceRegistered'];
            } else {
                $isRegistered = null;
            }
            if($datas['choiceNotRegistered']){
                $isUnregistered = $datas['choiceNotRegistered'];
            } else {
                $isUnregistered = null;
            }
            if($datas['choiceFinished']){
                $isFinished = $datas['choiceFinished'];
            } else {
                $isFinished = null;
            }
            $results = $repositoryOutings->findByParameters($user,$campus,$contains,$dateDebut,$dateFin, $isAuthor, $isRegistered, $isUnregistered, $isFinished);
        } else {
            $results = $this->getDoctrine()->getRepository(Outings::class)->findBy([],['id' => 'DESC']);
        }
        $display = $paginator->paginate(
            $results,
            $request->query->getInt('page',1),
            6
        );
        foreach ($results as $outing) {
            $nowstr = date("d/m/Y H:i:s");
            $str_date_outing = date_format($outing->getDateHourOuting(), "d/m/Y H:i:s");
            $str_date_end_subscription = date_format($outing->getDateInscriptionLimit(), "d/m/Y H:i:s");
            $date_outing = date_create_from_format("d/m/Y H:i:s", $str_date_outing);
            $outing_duration = $outing->getDuration();
            $date_outing_end = date_modify($date_outing, '+'.$outing_duration.'minutes');
            $outing_end_str = date_format($date_outing_end, "d/m/Y H:i:s");
            $spots_taken = count($outing->getMembers());
            $total_spots_number = $outing->getSpotNumber();

            if ($outing->getStatus($statusRepository->findStatusByName('En création'))) {
                $outing->getStatus($statusRepository->findStatusByName('En création'));
            } elseif(($str_date_outing > $nowstr) && ($spots_taken < $total_spots_number) && ($str_date_end_subscription > $nowstr)) {
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
            'outings' => $display
        ]);
    }
}