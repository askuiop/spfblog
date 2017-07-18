<?php

namespace Jims\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        dump($em);
        return $this->render('JimsWxBundle:Default:index.html.twig');
    }
}
