<?php

namespace PHPTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'PHPTestBundle:full:index.html.twig'
        );
    }
}
