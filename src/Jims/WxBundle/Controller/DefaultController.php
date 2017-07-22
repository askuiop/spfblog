<?php

namespace Jims\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JimsWxBundle:Default:index.html.twig');
    }

    public function testAction()
    {
        return $this->render('JimsWxBundle:Default:index.html.twig');
    }
}
