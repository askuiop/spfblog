<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends SpfController
{
    protected $layoutView = '@Blog/Default/layout.html.twig';
    public function indexAction()
    {
        $template = 'BlogBundle:Default:index.html.twig';
        $params = [
            'words' => '<p>我是数据</p>',
        ];
        return $this->spfRender($template, $params);
    }

    public function postAction(Request $request)
    {
        $template = 'BlogBundle:Default:post.html.twig';
        $params = [
            'title' => $request->getClientIp(),
            'words' => '<p>我是数据</p>',
        ];
        return $this->spfRender($template, $params);
    }

    public function contactAction(Request $request)
    {
        $template = 'BlogBundle:Default:contact.html.twig';
        $params = [
            'words' => '<p>我是数据</p>',
        ];
        return $this->spfRender($template, $params);


    }
    public function otherAction(Request $request)
    {
        $template = 'BlogBundle:Default:other.html.twig';
        $params = [
            'words' => '<p>我是数据</p>',
        ];

        return $this->spfRender($template, $params);

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
