<?php


namespace App\Controller;

use App\Entity\Outings;
use App\Form\ListOutingsFormType;
use App\Repository\OutingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Timezone;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main_home")
     */
    public function home(Request $request)
    {
        date_default_timezone_set('Europe/Paris');
        $user = $this->getUser();
        $form = $this->createForm(ListOutingsFormType::class);
        $form->handleRequest($request);
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

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'outings' => $results,
            'now' => new \DateTime('now')
        ]);
    }
}