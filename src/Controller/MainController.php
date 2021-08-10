<?php


namespace App\Controller;

use App\Entity\Outings;
use App\Form\ListOutingsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main_home")
     */
    public function home(Request $request)
    {
        $form = $this->createForm(ListOutingsFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }

        $outingsRepo = $this->getDoctrine()->getRepository(Outings::class);
        $all_outings = $outingsRepo->findAll();

        foreach ($all_outings as $outing) {

        }

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'outings' => $all_outings
        ]);
    }
}