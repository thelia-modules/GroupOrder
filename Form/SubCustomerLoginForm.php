<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 25/09/2020
 * Time: 15:00
 */

namespace GroupOrder\Form;


use GroupOrder\GroupOrder;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class SubCustomerLoginForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->getFormBuilder()
            ->add("login", TextType::class, array(
                "required" => true,
                "label" => Translator::getInstance()->trans("Login", [], GroupOrder::DOMAIN_NAME),
                "constraints" => array(
                    new NotBlank()
                ),
                "label_attr" => array(
                    "for" => "login",
                ),
            ))
            ->add("password", PasswordType::class, array(
                "required" => true,
                "label" => Translator::getInstance()->trans("Password", [], GroupOrder::DOMAIN_NAME),
                "constraints" => array(
                    new NotBlank()
                ),
                "label_attr" => array(
                    "for" => "password",
                ),
            ));
    }

    public static function getName(): string
    {
        return 'group_order_sub_customer_login';
    }
}