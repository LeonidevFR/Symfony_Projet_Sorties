<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function myprofile($string, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(
            ['pseudo' => $string]
        );

        return $this->render('profil/profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function userProfile($string, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(
            ['pseudo' => $string]
        );
        return $this->render('profil/user.html.twig', [
            'user' => $user,
        ]);
    }
}
