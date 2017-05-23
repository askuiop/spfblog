<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends SpfController
{
    public function indexAction()
    {
        $template = 'BlogBundle:Default:index.html.twig';
        $params = [
            'words' => '<p>nothing</p>',
        ];
        $navigates = [
            'title' =>  '%title',
            'head'  => '%head',
            'body'  => [
                'container' => '%container',
            ],
            'foot'  => '%foot',
        ];
        return $this->spfRender($template, $navigates, $params);
    }

    public function postAction(Request $request)
    {
        $template = 'BlogBundle:Default:post.html.twig';
        $params = [
            'words' => '<p>当西红柿成熟时，它们的颜色会从绿色逐渐转变成橙色、红色。评估西红柿何时成熟基本上是用肉眼完成的，会有些主观。</p>',
        ];

        $navigates = [
            'title' =>  '%title',
            'head'  => '%head',
            'body'  => [
                'container' => '%container',
            ],
            'foot'  => '%foot',
        ];

        return $this->spfRender($template, $navigates, $params);

    }

    public function contactAction(Request $request)
    {
        $template = 'BlogBundle:Default:contact.html.twig';
        $params = [
            'words' => '<p>当西红柿成熟时，它们的颜色会从绿色逐渐转变成橙色、红色。评估西红柿何时成熟基本上是用肉眼完成的，会有些主观。</p>',
        ];

        $navigates = [
            'title' =>  '%title',
            'head'  => '%head',
            'body'  => [
                'container' => '%container',
            ],
            'foot'  => '%foot',
        ];

        return $this->spfRender($template, $navigates, $params);


    }
    public function otherAction(Request $request)
    {
        $template = 'BlogBundle:Default:other.html.twig';
        $params = [
            'words' => '<p>当西红柿成熟时，它们的颜色会从绿色逐渐转变成橙色、红色。评估西红柿何时成熟基本上是用肉眼完成的，会有些主观。</p>',
        ];

        $navigates = [
            'title' =>  '%title',
            'head'  => '%head',
            'body'  => [
                'container' => '%container',
            ],
            'foot'  => '%foot',
        ];

        return $this->spfRender($template, $navigates, $params);


    }

    public function testDataAction()
    {
        $data = [
            0 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],
            1 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],
            2 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],
            3 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],
            4 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],
            5 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],
            6 => ['title'=>'xxxx', 'link'=>'http://baidu.com'],

        ];
        $data = array_slice($data, 0, mt_rand(1, 5));
        return new JsonResponse($data);
    }





}
