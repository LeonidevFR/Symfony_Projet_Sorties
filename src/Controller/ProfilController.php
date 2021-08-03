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
    public function profil($string, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(
            ['pseudo' => $string]
        );

        return $this->render('profil/profile.html.twig', [
            'user' => $user,
            'id' =>$user->getId()
        ]);
    }
}
