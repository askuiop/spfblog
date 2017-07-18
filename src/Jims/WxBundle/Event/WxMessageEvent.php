<?php
/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 上午10:21
 */

namespace Jims\WxBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class WxMessageEvent extends Event
{
    private $message;
    /**
     * WechatMessageEvent constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}