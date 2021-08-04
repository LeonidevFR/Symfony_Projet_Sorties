<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="app_profil_")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/{string}", name="view")
     */
    public function profil($string, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(
            ['pseudo' => $string]
        );

        return $this->render('profil/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/edit/{id}", "edit")
     */
    public function edit(Request $request, User $user) {
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);
        // $encoder->isPasswordValid($user, $old_password)
        // $encoder->encodePassword($user, $new_password)
        if($form->isSubmitted()
            && $form->isValid()
            && $encoder->isPasswordValid($user, $form->get('oldPassword')->getData())
        ) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_main_home');
        }

        return $this->render('profil/profile_edit.html.twig', array('form' => $form->createView()));
    }
}
