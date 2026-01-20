<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 21/09/2020
 * Time: 14:02
 */

namespace GroupOrder\Form;


use GroupOrder\GroupOrder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\Country;
use Thelia\Model\CountryQuery;

class SubCustomerForm extends BaseForm
{
    protected function buildForm(): void
    {
        $countries = CountryQuery::create()->filterByVisible(1)->find();
        $choice = [];

        /** @var Country $country */
        foreach ($countries as $country) {
            $choice[$country->getTitle()] = $country->getId();
        }

        $this->getFormBuilder()
            ->add("firstname", TextType::class, array(
                "required" => true,
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => Translator::getInstance()->trans("First Name", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "firstname",
                ),
            ))
            ->add("lastname", TextType::class, array(
                "required" => true,
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => Translator::getInstance()->trans("Last Name", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "lastname",
                ),
            ))
            ->add("login", TextType::class, array(
                "required" => false,
                "label" => Translator::getInstance()->trans("Login", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "login",
                ),
            ))
            ->add("password", PasswordType::class, array(
                "required" => false,
                "label" => Translator::getInstance()->trans("Password", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "password",
                ),
            ))
            ->add("email", EmailType::class, array(
                "required" => false,
                "constraints" => array(
                    new Email(),
                ),
                "label" => Translator::getInstance()->trans("Email Address", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "email",
                ),
            ))
            ->add("address1", TextType::class, array(
                "required" => false,
                "label" => Translator::getInstance()->trans("Street Address", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "address1",
                ),
            ))
            ->add("address2", TextType::class, array(
                "label" => Translator::getInstance()->trans("Address Line 2", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "address2",
                ),
                "required" => false,
            ))
            ->add("address3", TextType::class, array(
                "label" => Translator::getInstance()->trans("Address Line 3", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "address3",
                ),
                "required" => false,
            ))
            ->add("city", TextType::class, array(
                "required" => false,
                "label" => Translator::getInstance()->trans("City", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "city",
                ),
            ))
            ->add("zipcode", TextType::class, array(
                "required" => false,
                "label" => Translator::getInstance()->trans("Zip code", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "zipcode",
                ),
            ))
            ->add("country_id", ChoiceType::class, array(
                "required" => false,
                "label" => Translator::getInstance()->trans("Country", [], GroupOrder::DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "country",
                ),
                "choices" => $choice
            ));
    }

    public static function getName(): string
    {
        return 'group_order_sub_customer';
    }
}