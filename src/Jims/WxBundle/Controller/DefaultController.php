<?php

namespace Jims\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function entryAction(Request $request)
    {
        file_put_contents('/tmp/xxxxx.log',$request->getUri());

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
