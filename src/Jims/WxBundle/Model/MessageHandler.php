<?php
/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 上午10:33
 */

namespace Jims\WxBundle\Model;


use Doctrine\ORM\EntityManager;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use Jims\WxBundle\Event\Events;
use Jims\WxBundle\Event\WxMessageEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MessageHandler
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;
    /**
     * @param EntityManager $entityManager
     *
     */


    /**
     * MessageHandler constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManager $entityManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->em         = $entityManager;
        $this->repository = $entityManager->getRepository('JimsWxBundle:WxUser');
    }
    public function handle($message)
    {
        $event = new WxMessageEvent($message);
        // 全部消息
        $this->eventDispatcher->dispatch(Events::MESSAGE_ALL, $event);
        switch ($message->MsgType) {
            case 'text':
                # 文字消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_TEXT, $event);
                return $this->textHandler($message);
                break;
            case 'image':
                # 图片消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_IMAGE, $event);
                break;
            case 'voice':
                # 语音消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_VOICE, $event);
                break;
            case 'video':
                # 视频消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_VIDEO, $event);
                break;
            case 'location':
                # 坐标消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_LOCATION, $event);
                break;
            case 'link':
                # 链接消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_LINK, $event);
                break;
            case 'event':
                # 事件消息...
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT, $event);
                $this->handleEventMessage($message, $event);
                break;
        }
    }
    /**
     * @param $message
     * @param WechatMessageEvent $event
     */
    private function handleEventMessage($message, $event)
    {
        switch (strtolower($message->Event)) {
            case 'subscribe':
                # 关注事件
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_SUBSCRIBE, $event);
                if (!empty($message->Ticket)) {
                    $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_SCAN, $event);
                }
                break;
            case 'unsubscribe':
                # 取消关注事件
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_UNSUBSCRIBE, $event);
                break;
            case 'scan':
                # 扫描带参数二维码事件
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_SCAN, $event);
                break;
            case 'location':
                # 上报地理位置事件
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_LOCATION, $event);
                break;
            case 'click':
                # 点击菜单拉取消息
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_CLICK, $event);
                break;
            case 'view':
                # 点击菜单跳转链接
                $this->eventDispatcher->dispatch(Events::MESSAGE_EVENT_VIEW, $event);
                break;
        }
    }


    private function textHandler($message)
    {
        $content = $message->Content;
        if (is_numeric($content) && $content) {
            $wxUser = $this->repository->findOneBy(['id' => $content]);
            if ($wxUser) {
                return new News([
                    'title'       => "用户",
                    'description' => '用户description...',
                    'url'         => '',
                    'image'       => $wxUser->getAvatar(),
                ]);
            } else {
                return new News([
                    'title'       => "没有该用户",
                    'description' => 'description...',
                    'url'         => '',
                    'image'       => '',
                ]);
            }
        } else {
            return new Text([
                'content' => '您好！你输入的是字符串'
            ]);
        }
    }
}