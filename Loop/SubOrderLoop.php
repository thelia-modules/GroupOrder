<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 25/09/2020
 * Time: 09:20
 */

namespace GroupOrder\Loop;


use GroupOrder\Model\GroupOrderSubOrder;
use GroupOrder\Model\GroupOrderSubOrderQuery;
use Propel\Runtime\Exception\PropelException;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\OrderAddressQuery;
use Thelia\Model\ProductSaleElementsQuery;
use Thelia\TaxEngine\Calculator;
use Thelia\Tools\MoneyFormat;

/**
 * @method getId()
 * @method getSubCustomer()
 * @method getGroupOrder()
 */
class SubOrderLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions(): ArgumentCollection
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('sub_customer'),
            Argument::createIntListTypeArgument('group_order')
        );
    }

    public function buildModelCriteria(): GroupOrderSubOrderQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
    {
        $query = GroupOrderSubOrderQuery::create();

        if ($ids = $this->getId()){
            $query->filterById($ids);
        }

        if ($subCustomerIds = $this->getSubCustomer()){
            $query->filterBySubCustomerId($subCustomerIds);
        }

        if ($groupOrder = $this->getGroupOrder()){
            $query->filterByGroupOrderId($groupOrder);
        }

        return $query;
    }

    /**
     * @param LoopResult $loopResult
     * @return LoopResult
     * @throws PropelException
     */
    public function parseResults(LoopResult $loopResult): LoopResult
    {
        /** @var GroupOrderSubOrder $subOrder */
        foreach ($loopResult->getResultDataCollection() as $subOrder){
            $product_ids = [];
            $amount = 0;
            $order = $subOrder->getGroupOrder()->getOrder();

            $calc = new Calculator();
            foreach ($subOrder->getGroupOrderProducts() as $groupOrderProduct){
                $pse = ProductSaleElementsQuery::create()->filterById($groupOrderProduct->getOrderProduct()->getProductSaleElementsId())->findOne();
                $invoiceAddress = OrderAddressQuery::create()->filterById($order->getInvoiceOrderAddressId())->findOne();
                $product = $pse->getProduct();
                $calc->load($product, $invoiceAddress->getCountry());
                $product_ids[] = $groupOrderProduct->getOrderProductId();
                $amount += $calc->getTaxedPrice((float)$groupOrderProduct->getOrderProduct()->getPrice()) * $groupOrderProduct->getOrderProduct()->getQuantity();
            }

            $amount = MoneyFormat::getInstance($this->getCurrentRequest())->formatByCurrency($amount * $this->getCurrentRequest()->getSession()->getCurrency()->getRate());

            $loopResultRow = new LoopResultRow($subOrder);
            $loopResultRow
                ->set("ID", $subOrder->getId())
                ->set("SUB_CUSTOMER_ID", $subOrder->getSubCustomerId())
                ->set("GROUP_ORDER_ID", $subOrder->getGroupOrderId())
                ->set("PRODUCT_IDS", $product_ids)
                ->set("ORDER_NUMBER", $order->getRef())
                ->set("DATE", $order->getCreatedAt())
                ->set("AMOUNT", $amount)
            ;

            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }



}