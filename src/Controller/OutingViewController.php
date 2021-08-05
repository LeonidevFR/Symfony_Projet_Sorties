<?php

namespace App\Controller;

use App\Entity\Outings;
use App\Repository\OutingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingViewController extends AbstractController
{
    /**
     * @Route("/outing/view", name="outing_view")
     */
    public function index(): Response
    {

        $view = $this->getDoctrine()->getRepository(Outings::class)->find($id);
        dd($view);


        return $this->render('outing_view/index.html.twig', [
            'controller_name' => 'OutingViewController',
        ]);
    }
}
