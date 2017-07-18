<?php
/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 上午10:20
 */

namespace Jims\WxBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class WxAuthorizeEvent extends Event
{
    /**
     * @var array
     */
    private $user;
    /**
     * WechatAuthorizeEvent constructor.
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }
    /**
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param array $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}