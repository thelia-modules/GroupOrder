<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 22/09/2020
 * Time: 10:28
 */

namespace GroupOrder\Loop;


use GroupOrder\Model\GroupOrderSubCustomer;
use GroupOrder\Model\GroupOrderSubCustomerQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * @method getId()
 * @method getMainCustomer()
 * @method getLogin()
 */
class SubCustomerLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions(): ArgumentCollection
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('main_customer'),
            Argument::createAlphaNumStringTypeArgument('login')
        );
    }

    public function buildModelCriteria(): GroupOrderSubCustomerQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
    {
        $query = GroupOrderSubCustomerQuery::create();

        if ($ids = $this->getId()) {
            $query->filterById($ids);
        }

        if ($mainCustomerIds = $this->getMainCustomer()) {
            $query->filterByMainCustomerId($mainCustomerIds);
        }

        if ($login = $this->getLogin()) {
            $query->filterByLogin($login);
        }

        return $query;
    }

    public function parseResults(LoopResult $loopResult): LoopResult
    {
        /** @var GroupOrderSubCustomer $subCustomer */
        foreach ($loopResult->getResultDataCollection() as $subCustomer) {
            $loopResultRow = new LoopResultRow($subCustomer);
            $loopResultRow
                ->set("ID", $subCustomer->getId())
                ->set("MAIN_CUSTOMER_ID", $subCustomer->getMainCustomerId())
                ->set("FIRSTNAME", $subCustomer->getFirstName())
                ->set("LASTNAME", $subCustomer->getLastName())
                ->set("EMAIL", $subCustomer->getEmail())
                ->set("ADDRESS1", $subCustomer->getAddress1())
                ->set("ADDRESS2", $subCustomer->getAddress2())
                ->set("ADDRESS3", $subCustomer->getAddress3())
                ->set("CITY", $subCustomer->getCity())
                ->set("ZIPCODE", $subCustomer->getZipCode())
                ->set("COUNTRY_ID", $subCustomer->getCountryId())
                ->set("LOGIN", $subCustomer->getLogin());

            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }

}