<?php

namespace Mila\ShortLinksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller
{
    public function loginAction()
    {
        return $this->render('MilaShortLinksBundle:Auth:login.html.twig');
    }

    public function registerAction()
    {
        return $this->render('MilaShortLinksBundle:Auth:register.html.twig');
    }
}
