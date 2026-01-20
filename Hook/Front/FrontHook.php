<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 21/09/2020
 * Time: 10:15
 */

namespace GroupOrder\Hook\Front;


use GroupOrder\GroupOrder;
use GroupOrder\Model\GroupOrderMainCustomer;
use GroupOrder\Model\GroupOrderSubCustomerQuery;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;

class FrontHook extends BaseHook
{
    public function onRegisterFormBottom(HookRenderEvent $event): void
    {
        $event->add($this->render('onRegisterFormBottom.html'));
    }

    public function onAccountAdditional(HookRenderBlockEvent $event): void
    {
        /** @var GroupOrderMainCustomer $mainCustomer */
        if ($mainCustomer = $this->getRequest()->getSession()->get("CurrentUserIsMainCustomer")) {
            $event->add([
                'id' => 'sub-customers',
                'title' => Translator::getInstance()->trans("My Sub Customers", [], GroupOrder::DOMAIN_NAME),
                'content' => $this->render('onAccountAdditional.html', [
                    'mainCustomerId' => $mainCustomer->getId()
                ])
            ]);
        }
    }

    public function onAccountJavaScript(HookRenderEvent $event): void
    {
        $event->add($this->render("includes/account-group-order-js.html"));
    }

    public function onMainBodyBottom(HookRenderEvent $event): void
    {
        /** @var GroupOrderMainCustomer $mainCustomer */
        if ($mainCustomer = $this->getRequest()->getSession()->get("CurrentUserIsMainCustomer")) {

            $event->add($this->render("sticky-window.html", [
                "mainCustomerId" => $mainCustomer->getId()
            ]));
        }
    }

    public function onMainHeadBottom(HookRenderEvent $event): void
    {
        $css = $this->addCSS('assets/group_order.css');
        $event->add($css);
    }

    public function onMainJavascriptInitialization(HookRenderEvent $event): void
    {
        $subCustomerId = $this->getRequest()->getSession()->get("GroupOrderSelectedSubCustomer");
        $subCustomerLogin = $this->getRequest()->getSession()->get("GroupOrderLoginSubCustomer");
        $subCustomer = GroupOrderSubCustomerQuery::create()->filterById($subCustomerId)->findOne();
        $title = $subCustomer ? $subCustomer->getFirstName() . ' ' . $subCustomer->getLastName() : null;
        $event->add($this->render("includes/sticky-window-js.html", [
            'selectedSubCustomerId' => $subCustomerId,
            'loginSubCustomerId' => $subCustomerLogin,
            'subCustomerTitle' => $title
        ]));
    }

    public function onLoginMainBottom(HookRenderEvent $event): void
    {
        $event->add($this->render("sub-customer-login.html"));
    }
}