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
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Log\Tlog;
use Thelia\Model\Cart;
use Thelia\Model\CartQuery;
use Thelia\Tools\URL;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/cart", name: "group_order_cart_")]
class CartController extends BaseFrontController
{
    /**
     * @param Request $request
     * @param Session $session
     * @return Response|RedirectResponse
     */
    #[Route("/sub-customer", name: "validate")]
    public function validate(Request $request, Session $session): Response|RedirectResponse
    {
        $subCustomerId = $request->getSession()->get("GroupOrderLoginSubCustomer");
        $mainCustomerId = $request->getSession()->get("GroupOrderMainCustomer");

        try {
            if ($subCustomerId && $mainCustomerId) {
                $cartItems = $session->getSessionCart()->getCartItems();

                $mainCustomer = GroupOrderMainCustomerQuery::create()->filterById($mainCustomerId)->findOne();

                $customer = $mainCustomer->getCustomer();

                $subCustomer = GroupOrderSubCustomerQuery::create()->filterById($subCustomerId)->findOne();

                if (!$mainCart = CartQuery::create()->filterByCustomerId($customer->getId())->findOne()) {
                    $mainCart = new Cart();
                    $mainCart->setCustomerId($customer->getId());
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

                $session->getSessionCart()->delete();

                return $this->render("valide-cart");
            }
        } catch (\Exception $e) {
            Tlog::getInstance()->error(sprintf('Error during sub-customer cart validation : %s', $e->getMessage()));
        }

        return $this->generateRedirect(URL::getInstance()->absoluteUrl("/cart"));
    }
}