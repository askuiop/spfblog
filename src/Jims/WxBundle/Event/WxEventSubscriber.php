<?php
/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 上午10:22
 */

namespace Jims\WxBundle\Event;


use Doctrine\ORM\EntityManager;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use Jims\WxBundle\Model\WechatUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WxEventSubscriber implements EventSubscriberInterface
{
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
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em         = $entityManager;
        $this->repository = $entityManager->getRepository('JimsWxBundle:WxUser');
    }
    public static function getSubscribedEvents()
    {
        return array(
            Events::AUTHORIZE => 'onAuthorize',

            Events::MESSAGE_TEXT => 'onText',
        );
    }

    public function onText(WxMessageEvent $event)
    {
        $message =$event->getMessage();
        $content = $message->Content;
        ///....
    }



    public function onAuthorize(WechatAuthorizeEvent $event)
    {
        $wx_user = $event->getUser();
        $openid = $wx_user['openid'];
        /** @var WechatUserInterface $user */
        $user = $this->repository->findOneBy(array('openid' => $openid));
        if (!$user) {
            $user = new $this->userClass;
            $user->load($wx_user);
            $this->em->persist($user);
            $this->em->flush();
        }



    }

}