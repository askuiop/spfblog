<?php

/**
 * Created by PhpStorm.
 * User: jims
 * Date: 17-7-17
 * Time: 上午10:17
 */
namespace Jims\WxBundle\Event;

final class Events
{
    const AUTHORIZE                 = 'jims.wx.authorize';
    const MESSAGE_ALL               = 'jims.wx.message.all';
    const MESSAGE_TEXT              = 'jims.wx.message.text';
    const MESSAGE_IMAGE             = 'jims.wx.message.image';
    const MESSAGE_VOICE             = 'jims.wx.message.voice';
    const MESSAGE_VIDEO             = 'jims.wx.message.video';
    const MESSAGE_LOCATION          = 'jims.wx.message.location';
    const MESSAGE_LINK              = 'jims.wx.message.link';
    const MESSAGE_EVENT             = 'jims.wx.message.event';
    const MESSAGE_EVENT_SUBSCRIBE   = 'jims.wx.message.event.subscribe';
    const MESSAGE_EVENT_UNSUBSCRIBE = 'jims.wx.message.event.unsubscribe';
    const MESSAGE_EVENT_SCAN        = 'jims.wx.message.event.scan';
    const MESSAGE_EVENT_LOCATION    = 'jims.wx.message.event.location';
    const MESSAGE_EVENT_CLICK       = 'jims.wx.message.event.click';
    const MESSAGE_EVENT_VIEW        = 'jims.wx.message.event.view';

    final private function __construct()
    {
    }
}