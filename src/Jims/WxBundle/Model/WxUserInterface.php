<?php

/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 上午10:24
 */

namespace Jims\WxBundle\Model;

interface WxUserInterface
{
    public function getOpenid();
    public function load(array $data);
    public function __toString();
}