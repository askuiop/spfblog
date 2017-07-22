<?php

namespace Jims\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function entryAction()
    {
        $server         = $this->get('wx_app')->server;
        $messageHandler = $this->get('wx.message.handler');
        $server->setMessageHandler(function($message) use ($messageHandler) {
            $messageHandler->handle($message);
        });
        $response = $server->serve();
        return $response;
    }

    public function testAction()
    {
        return $this->render('JimsWxBundle:Default:index.html.twig');
    }
}
