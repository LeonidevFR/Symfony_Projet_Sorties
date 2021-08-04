<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profil", name="app_profil_")
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
    public function edit(UserPasswordEncoderInterface $encoder, Request $request, User $user) {
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);
        // $encoder->isPasswordValid($user, $old_password)
        // $encoder->encodePassword($user, $new_password)
        if($form->isSubmitted()
            && $form->isValid()
            // $encoder->isPasswordValid($user, $form->get('oldPassword')->getData())
        ) {
            $image=$form->get('avatar')->getData();
                // je test si l'image a été changée, si non: une nouvelle est saisie
                if(!is_null($image)) {
                    // donner un nouveau nom unique à l'image
                    $new_image_name = uniqid() . '.' . $image->guessExtension();
                    // enregistrer l'image sur le serveur
                    $image->move($this->getParameter('upload_dir'), $new_image_name);
                    // je donne le nom de l'image dans la DBB
                    $userOldAvatar = $user->getAvatar();
                    $user->setAvatar($new_image_name);
                    $path = "../public/img/userAvatar";
                    if($userOldAvatar!= null)
                        unlink($path."/".($userOldAvatar));
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_main_home');
        }

        return $this->render('profil/profile_edit.html.twig', array('form' => $form->createView()));
    }
}
