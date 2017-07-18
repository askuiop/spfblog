<?php

/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 下午2:39
 */
namespace Jims\WxBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class WxUserToken extends AbstractToken
{
    public function __construct($openid, array $roles = array())
    {
        parent::__construct($roles);
        $this->setAttribute('openid', $openid);
        $this->setAuthenticated(count($roles) > 0);
    }
    public function getOpenid()
    {
        return $this->getAttribute('openid');
    }
    public function getCredentials()
    {
        return '';
    }
}