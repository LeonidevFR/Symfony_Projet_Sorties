<?php


namespace App\Controller;

use App\Entity\Outings;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ListOutingsFormType;
use App\Form\Model\ChangePassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'outings' => $outingsRepo->findAll()
        ]);
    }
}