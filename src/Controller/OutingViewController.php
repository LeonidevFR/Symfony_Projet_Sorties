<?php

namespace App\Controller;

use App\Entity\Outings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingViewController extends AbstractController
{
    /**
     * @Route("/outing/view/{id}", name="outing_view")
     */
    public function outingView($id): Response
    {

        $view = $this->getDoctrine()->getRepository(Outings::class)->find($id);

        if(!$view){
            throw $this->createNotFoundException('Aucune sortie ne correspond a l\'ID'.$id);
        }


        return $this->render('outing_view/index.html.twig', [
            'controller_name' => 'OutingViewController',
            'view' => $view,
        ]);
    }
}
