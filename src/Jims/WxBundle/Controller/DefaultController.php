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


/*        $app = $this->get('wx_app');
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
            return "您好！欢迎关注我!";
        });
        $response = $server->serve();

        return $response;*/
    }

    public function testAction()
    {
        return $this->render('JimsWxBundle:Default:index.html.twig');
    }
}
