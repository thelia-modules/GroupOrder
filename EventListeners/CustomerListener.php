<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 17/09/2020
 * Time: 16:45
 */

namespace GroupOrder\EventListeners;


use GroupOrder\GroupOrder;
use GroupOrder\Model\GroupOrderMainCustomer;
use GroupOrder\Model\GroupOrderMainCustomerQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Callback;
use Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Core\Translation\Translator;

class CustomerListener implements EventSubscriberInterface
{
    const MAIN_CUSTOMER_CHECKBOX = "main_customer_checkbox";

    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function addMainCustomerCheckBox(TheliaFormEvent $event): void
    {
        $event->getForm()->getFormBuilder()->add(
            self::MAIN_CUSTOMER_CHECKBOX,
            CheckboxType::class,
            [
                'constraints' => [
                    new Callback([$this, 'setIsMainCustomer'])
                ],
                'required' => false,
                'label' => Translator::getInstance()->trans(
                    'Main Customer', [],
                    GroupOrder::DOMAIN_NAME
                ),
                'label_attr' => [
                    'for' => self::MAIN_CUSTOMER_CHECKBOX
                ]
            ]
        );

    }

    public function setIsMainCustomer($value): void
    {
        $this->getRequest()->getSession()->set(self::MAIN_CUSTOMER_CHECKBOX, null);

        if ($value) {
            $this->getRequest()->getSession()->set(self::MAIN_CUSTOMER_CHECKBOX, $value);
        }
    }

    /**
     * @param CustomerCreateOrUpdateEvent $event
     * @throws PropelException
     */
    public function processMainCustomerCheckBox(CustomerCreateOrUpdateEvent $event): void
    {
        if ($event->hasCustomer()) {
            $isMainCustomer = $this->getRequest()->getSession()->get(self::MAIN_CUSTOMER_CHECKBOX);
            $mainCustomer = GroupOrderMainCustomerQuery::create()->filterByCustomerId($event->getCustomer()->getId())->findOne();
            if ($isMainCustomer) {
                if (!$mainCustomer) {
                    $mainCustomer = new GroupOrderMainCustomer();
                    $mainCustomer
                        ->setCustomerId($event->getCustomer()->getId());
                }
                $mainCustomer
                    ->setActive(1)
                    ->save();
            }
            if ($mainCustomer && !$isMainCustomer) {
                $mainCustomer
                    ->setActive(0)
                    ->save();
            }
        }
        $this->getRequest()->getSession()->set(self::MAIN_CUSTOMER_CHECKBOX, null);
    }


    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::FORM_BEFORE_BUILD . ".thelia_customer_create" => ['addMainCustomerCheckBox', 128],
            TheliaEvents::FORM_BEFORE_BUILD . ".thelia_customer_update" => ['addMainCustomerCheckBox', 128],
            TheliaEvents::CUSTOMER_CREATEACCOUNT => ['processMainCustomerCheckBox', 10],
            TheliaEvents::CUSTOMER_UPDATEACCOUNT => ['processMainCustomerCheckBox', 10],
        ];
    }
}