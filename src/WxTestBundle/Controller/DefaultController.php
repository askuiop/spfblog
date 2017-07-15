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
        file_put_contents('/tmp/xxxxx.log',$request->getUri());

        $app = $this->get('wx_app');

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

        return $response;




        /*$token = 'suoklya';
        $signature = $request->query->get('signature');
        $timestamp = $request->query->get('timestamp');
        $nonce = $request->query->get('nonce');
        $echostr = $request->query->get('echostr', '');

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature && $echostr ){
            return new Response($echostr);
            //header('content-type:text');
            //echo $echostr;
        }
        throw new NotFoundHttpException();*/
    }

    public function testAuthAction()
    {
        $app = $this->get('wx_app');
        $response = $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect('http://askuiop.com/wx/oauthcallback');

        return $response;

    }

    public function authCallBackAction()
    {
        $app = $this->get('wx_app');
        $user = $app->oauth->user();

        print_r($user->toArray());
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用

    }

}
