<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 25/09/2020
 * Time: 11:46
 */

namespace GroupOrder\EventListeners;

use GroupOrder\Model\GroupOrderMainCustomerQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\DefaultActionEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\CartQuery;

class LoginListener implements EventSubscriberInterface
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function login(CustomerLoginEvent $event): void
    {
        if (GroupOrderMainCustomerQuery::create()->filterByCustomerId($event->getCustomer()->getId())->findOne() &&
            $cart = CartQuery::create()->filterByCustomerId($event->getCustomer()->getId())->findOne()) {
            $this->getRequest()->getSession()->setSessionCart($cart);
        }

        if ($mainCustomer = GroupOrderMainCustomerQuery::create()->filterByCustomerId($event->getCustomer()->getId())->findOne()){
            $this->getRequest()->getSession()->set('CurrentUserIsMainCustomer', $mainCustomer);
        }
    }

    public function logout(DefaultActionEvent $event): void
    {
        if ($this->getRequest()->getSession()->get('CurrentUserIsMainCustomer')){
            $this->getRequest()->getSession()->set('CurrentUserIsMainCustomer', null);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::CUSTOMER_LOGIN => ["login", 128],
            TheliaEvents::CUSTOMER_LOGOUT => ["logout", 110]
        ];
    }
}