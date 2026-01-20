<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 22/09/2020
 * Time: 16:02
 */

namespace GroupOrder\Hook\Back;


use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class BackHook extends BaseHook
{
    public function customerEditJs(HookRenderEvent $event): void
    {
        $event->add($this->render('customerEditJs.html'));
    }
}