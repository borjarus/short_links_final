<?php

namespace Mila\ShortLinksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MilaShortLinksBundle:Default:index.html.twig');
    }
}
