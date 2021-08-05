<?php

namespace App\Handler;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $request->getSession()->getFlashBag()->add('access_denied', 'Vous n\'avez pas les permissions pour accèder à cette page.');
        return new RedirectResponse('http://localhost/Symfony_Projet_Sorties/public/');
    }
}