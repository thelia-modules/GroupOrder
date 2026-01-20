<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 23/09/2020
 * Time: 10:09
 */

namespace GroupOrder\Loop;


use GroupOrder\Model\GroupOrderMainCustomer;
use GroupOrder\Model\GroupOrderMainCustomerQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * @method getId()
 * @method getSubCustomerId()
 * @method getCustomerId()
 * @method getActive()
 */
class MainCustomerLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions(): ArgumentCollection
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('sub_customer_id'),
            Argument::createIntListTypeArgument('customer_id'),
            Argument::createIntTypeArgument('active')
        );
    }

    public function buildModelCriteria(): GroupOrderMainCustomerQuery|ModelCriteria
    {
        $query = GroupOrderMainCustomerQuery::create();

        if ($ids = $this->getId()) {
            $query->filterById($ids);
        }

        if ($subCustomerIds = $this->getSubCustomerId()) {
            $query
                ->useGroupOrderSubCustomerQuery()
                ->filterById($subCustomerIds)
                ->endUse();
        }

        if ($customerIds = $this->getCustomerId()) {
            $query->filterByCustomerId($customerIds);
        }

        if ($this->getActive() === true) {
            $query->filterByActive(1);
        }

        return $query;
    }

    public function parseResults(LoopResult $loopResult): LoopResult
    {
        /** @var GroupOrderMainCustomer $mainCustomer */
        foreach ($loopResult->getResultDataCollection() as $mainCustomer) {
            $loopResultRow = new LoopResultRow($mainCustomer);
            $loopResultRow
                ->set("ID", $mainCustomer->getId())
                ->set("CUSTOMER_ID", $mainCustomer->getCustomerId());

            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }

}