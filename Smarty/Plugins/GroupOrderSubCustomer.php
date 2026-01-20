<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 28/09/2020
 * Time: 11:48
 */

namespace GroupOrder\Smarty\Plugins;


use GroupOrder\Model\GroupOrderCartItemQuery;
use GroupOrder\Model\GroupOrderProductQuery;
use Propel\Runtime\Exception\PropelException;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

class GroupOrderSubCustomer extends AbstractSmartyPlugin
{
    public function getPluginDescriptors(): array
    {
        return [
            new SmartyPluginDescriptor('function', 'groupOrderSubCustomerName', $this, 'groupOrderSubCustomerName'),
        ];
    }

    /**
     * @param $params
     * @param $smarty
     * @throws PropelException
     */
    public function groupOrderSubCustomerName($params, $smarty): void
    {
        $cartItemId = $params["item_id"];

        $orderProductId = $params["order_product_id"];

        $subCustomer = null;

        if ($cartItemId && $cartItem = GroupOrderCartItemQuery::create()->filterByCartItemId($cartItemId)->findOne()){
            $subCustomer = $cartItem->getGroupOrderSubCustomer();
        }

        if (!$subCustomer && $orderProductId && $orderProduct = GroupOrderProductQuery::create()->filterByOrderProductId($orderProductId)->findOne()) {
            $subCustomer = $orderProduct->getGroupOrderSubOrder()->getGroupOrderSubCustomer();
        }

        if ($subCustomer){
            $smarty->assign('subCustomerName', $subCustomer->getFirstName() . " " . $subCustomer->getLastName());
        }
    }
}