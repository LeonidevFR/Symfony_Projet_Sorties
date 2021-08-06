<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profil/", name="app_profil_")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("view/{string}", name="view")
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
     * @Route("edit", name="edit")
     */
    public function edit(UserPasswordEncoderInterface $encoder, Request $request) {
        $user = $this->getUser();
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);
        // $encoder->isPasswordValid($user, $old_password)
        // $encoder->encodePassword($user, $new_password)
        if($form->isSubmitted()
            && $form->isValid()
            && $encoder->isPasswordValid($user, $form->get('oldPassword')->getData())
        ) {
            //Si le champ 'nouveau mot de passe' est rempli alors =>
            if(!empty($form->get('plainPassword')->getData())) {
                //Change le mot de passe de l'user en BDD
                $user->setPassword(
                    $encoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                //Suite du traitement

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
                    $path = '../public/img/userAvatar';
                    $finder = new Finder();
                    $finder->files()->name($userOldAvatar)->in($path);
                    foreach ($finder as $file){
                        if($file != null && ($file->getFilename() != "imagedemerde.png")){

                            unlink($path . "/" . ($userOldAvatar));
                        }
                    }
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                //On logout l'utilisateur s'il a changé son mdp
                return $this->redirect($this->generateUrl('app_logout'));
            } //Sinon =>
            else {
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
                    $path = '../public/img/userAvatar';
                    $finder = new Finder();
                    $finder->files()->name($userOldAvatar)->in($path);
                    foreach ($finder as $file){
                        if($file != null && ($file->getFilename() != "imagedemerde.png")){

                            unlink($path . "/" . ($userOldAvatar));
                        }
                    }
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', "Modifications bien enregistrées");
                //on redirige l'user vers son profil
                return $this->redirectToRoute('app_profil_view', ['string' => $form->get('pseudo')->getData()]);
            }
        }

        return $this->render('profil/profile_edit.html.twig', array('form' => $form->createView()));
    }
}
