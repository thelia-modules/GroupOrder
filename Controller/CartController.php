<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 25/09/2020
 * Time: 17:28
 */

namespace GroupOrder\Controller;


use GroupOrder\Model\Base\GroupOrderMainCustomerQuery;
use GroupOrder\Model\GroupOrderCartItem;
use GroupOrder\Model\GroupOrderSubCustomerQuery;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Model\Cart;
use Thelia\Model\CartQuery;
use Thelia\Tools\URL;

class CartController extends BaseFrontController
{
    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function validate()
    {
        $subCustomerId = $this->getRequest()->getSession()->get("GroupOrderLoginSubCustomer");
        $mainCustomerId = $this->getRequest()->getSession()->get("GroupOrderMainCustomer");

        if ($subCustomerId && $mainCustomerId) {
            $cartItems = $this->getSession()->getSessionCart()->getCartItems();

            $mainCustomer = GroupOrderMainCustomerQuery::create()->filterById($mainCustomerId)->findOne();

            $customer = $mainCustomer->getCustomer();

            $subCustomer = GroupOrderSubCustomerQuery::create()->filterById($subCustomerId)->findOne();

            if (!$mainCart = CartQuery::create()->filterByCustomerId($customer->getId())->findOne()) {
                $mainCart = new Cart();
                $mainCart
                    ->setCustomerId($customer->getId());
            }

            foreach ($cartItems as $cartItem) {
                $cartItem
                    ->setCartId($mainCart->getId())
                    ->save();

                $groupOrderCartItem = new GroupOrderCartItem();
                $groupOrderCartItem
                    ->setSubCustomerId($subCustomer->getId())
                    ->setCartItemId($cartItem->getId())
                    ->save();
            }

            $this->getSession()->getSessionCart()->delete();

            return $this->render("valide-cart");
        }
        return $this->generateRedirect(URL::getInstance()->absoluteUrl("/cart"));
    }
}