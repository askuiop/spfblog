<?php

namespace WxTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $token = 'suoklya';

        $signature = $request->query->get('signature');
        $timestamp = $request->query->get('timestamp');
        $nonce = $request->query->get('nonce');
        $echostr = $request->query->get('echostr');

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){

            return new Response($echostr);
            //header('content-type:text');
            //echo $echostr;
        }

        throw new NotFoundHttpException();
    }
}
