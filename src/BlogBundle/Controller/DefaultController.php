<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends SpfController
{
    public function indexAction(Request $request)
    {
        $template = 'BlogBundle:Default:index.html.twig';
        $params = [
            'words' => '<p>此前鸟类之间的“社交互动”研究已经显示了一些有趣的见解</p>',
        ];

        $navigates = [
            'title' =>  'index',
            'head'  => '_head',
            'body'  => [
                'masthead' =>  '_masthead',
                'container' => '_container',
            ],
            'foot'  => '_foot',
        ];

        return $this->spfRender($template, $navigates, $params);


    }

    public function testAction(Request $request)
    {
        $template = 'BlogBundle:Default:test.html.twig';
        $params = [
            'words' => '<p>当西红柿成熟时，它们的颜色会从绿色逐渐转变成橙色、红色。评估西红柿何时成熟基本上是用肉眼完成的，会有些主观。</p>',
        ];

        $navigates = [
            'title' =>  '_title',
            'head'  => '_head',
            'body'  => [
                'masthead' =>  '_masthead',
                'page-container' => '_page_container',
            ],
            'foot'  => '_foot',
        ];

        return $this->spfRender($template, $navigates, $params);

    }

    public function test3Action(Request $request)
    {
        $template = 'BlogBundle:Default:test3.html.twig';
        $params = [
            'words' => '<p>此前</p>',
        ];

        $navigates = [
            'title' =>  'index',
            'head'  => '_head',
            'body'  => [
                'masthead' =>  '_masthead',
                'page-container' => '_page_container',
            ],
            'foot'  => '_foot',
        ];

        return $this->spfRender($template, $navigates, $params);


    }





}
