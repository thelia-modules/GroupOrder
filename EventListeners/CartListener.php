<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 24/09/2020
 * Time: 10:14
 */

namespace GroupOrder\EventListeners;


use GroupOrder\Model\GroupOrder;
use GroupOrder\Model\GroupOrderCartItem;
use GroupOrder\Model\GroupOrderCartItemQuery;
use GroupOrder\Model\GroupOrderMainCustomer;
use GroupOrder\Model\GroupOrderMainCustomerQuery;
use GroupOrder\Model\GroupOrderProduct;
use GroupOrder\Model\GroupOrderQuery;
use GroupOrder\Model\GroupOrderSubCustomer;
use GroupOrder\Model\GroupOrderSubOrder;
use GroupOrder\Model\GroupOrderSubOrderQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\Order\OrderProductEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\CartItemQuery;

class CartListener implements EventSubscriberInterface
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param CartEvent $event
     * @throws PropelException
     */
    public function isNew(CartEvent $event): void
    {
        if ($subCustomerId = $this->getRequest()->getSession()->get("GroupOrderSelectedSubCustomer")) {

            $groupOrderCartItem = null;

            $foundItems = CartItemQuery::create()
                ->filterByCartId($event->getCart()->getId())
                ->filterByProductId($event->getProduct())
                ->filterByProductSaleElementsId($event->getProductSaleElementsId())
                ->find();

            if (!$foundItems) {
                $foundItemsIds = array_map(static function($item) {return $item->getId();}, $foundItems);

                if ($foundItemsIds) {
                    $groupOrderCartItem = GroupOrderCartItemQuery::create()->filterBySubCustomerId($subCustomerId)->filterByCartItemId($foundItemsIds)->findOne();
                }
            }

            if ($groupOrderCartItem) {
                $groupOrderCartItem->getCartItem()->addQuantity($event->getQuantity())->save();

                $event->stopPropagation();
            }
            $event->setNewness(true);
        }
    }

    /**
     * @param CartEvent $event
     * @throws PropelException
     */
    public function addItem(CartEvent $event): void
    {
        if ($subCustomerId = $this->getRequest()->getSession()->get("GroupOrderSelectedSubCustomer")) {
            $groupOrderCartItem = new GroupOrderCartItem();
            $groupOrderCartItem
                ->setSubCustomerId($subCustomerId)
                ->setCartItemId($event->getCartItem()->getId())
                ->save();
        }
    }

    /**
     * @param OrderProductEvent $event
     * @throws PropelException
     */
    public function addOrderItem(OrderProductEvent $event): void
    {
        /** @var GroupOrderMainCustomer $mainCustomer */
        if ($mainCustomer = $this->getRequest()->getSession()->get("CurrentUserIsMainCustomer")) {
            if (!$groupOrder = GroupOrderQuery::create()->filterByOrderId($event->getOrder()->getId())->findOne()) {
                $groupOrder = new GroupOrder();
                $groupOrder
                    ->setMainCustomerId($mainCustomer->getId())
                    ->setOrderId($event->getOrder()->getId())
                    ->save();
            }
            /** @var GroupOrderSubCustomer $subCustomer */
            foreach ($mainCustomer->getGroupOrderSubCustomers() as $subCustomer) {
                $subOrder = GroupOrderSubOrderQuery::create()
                    ->filterByGroupOrderId($groupOrder->getId())
                    ->filterBySubCustomerId($subCustomer->getId())
                    ->findOne();
                if (!$subOrder && $subCustomer->getGroupOrderCartItems()->getData()) {
                    $subOrder = new GroupOrderSubOrder();
                    $subOrder
                        ->setSubCustomerId($subCustomer->getId())
                        ->setGroupOrderId($groupOrder->getId())
                        ->save();
                }

                /** @var GroupOrderCartItem $cartItem */
                foreach ($subCustomer->getGroupOrderCartItems() as $cartItem) {
                    if ($cartItem->getCartItemId() === $event->getCartItemId()) {
                        $groupOrderProduct = new GroupOrderProduct();
                        $groupOrderProduct
                            ->setSubOrderId($subOrder->getId())
                            ->setOrderProductId($event->getId())
                            ->save();

                        $cartItem->delete();
                    }
                }
            }
        }
    }

    /**
     * @param CartEvent $event
     * @throws PropelException
     */
    public function deleteItem(CartEvent $event): void
    {
        $customerId = null;
        if ($customer = $this->getRequest()->getSession()->getCustomerUser()) {
            $customerId = $customer->getId();
        }
        if (GroupOrderMainCustomerQuery::create()->filterByCustomerId($customerId)->findOne()) {
            $cartItem = GroupOrderCartItemQuery::create()->filterByCartItemId($event->getCartItemId());
            $cartItem?->delete();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::CART_ADDITEM => [
                ['isNew', 150],
                ['addItem', 110]
            ],
            TheliaEvents::CART_DELETEITEM => ['deleteItem', 150],
            TheliaEvents::ORDER_PRODUCT_AFTER_CREATE => ['addOrderItem', 110]
        ];
    }

}