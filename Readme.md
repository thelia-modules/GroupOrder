# Group Order

This Module allows you to create group orders.

## Installation

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/group-order-module:~1.0.0
```

## Usage

During registering or in the back office, you have the possibility to create Main Customers.
A main customer can create multiple sub-customers.

A sub-customer can connect via the login and password set by the main customer.
They have restricted right, they can add items to their cart and validate their cart.
When a sub-customer validates his cart, the items are sent to the cart of the main customer.

The main customer can see all sub-customers and their orders.

## For Developers

To log in as a sub customer, use the route `/login/sub-customer`.

To validate your cart, use the route `/cart/sub-customer`.

## Hook

### Front

* `register.form-bottom` is used to add the main customer checkbox on the register page
* `account.additional` and `account.javascript-initialization` is used to add the new panel allowing a main customer to create sub customers
* `main.footer-bottom`, `main.stylesheet` and `main.javascript-initialization` is used to display the sticky window for main customers
* `login.main-bottom` is used to add the login form for sub customers connections

### Back

* `customer.edit-js` is used to add the main customer checkbox on the customer edit page

## Loop

[group_order_sub_customer]

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | id of a sub customer |
|**main_customer** | id of the main customer |
|**login** | login of a sub customer |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID    | id of the sub customer |
|$MAIN_CUSTOMER_ID    | id of the main customer |
|$FIRSTNAME    | first name of the sub customer |
|$LASTNAME    | last name of the sub customer |
|$EMAIL    | email of the sub customer |
|$ADDRESS1    | address of the sub customer |
|$ADDRESS2    | address2 of the sub customer |
|$ADDRESS3    | address3 of the sub customer |
|$CITY    | city of the sub customer |
|$ZIPCODE    | zip code of the sub customer |
|$COUNTRY_ID    | id of the country |
|$LOGIN    | login of the sub customer |

### Exemple

    <ul>
        {loop type="group_order_sub_customer" name="my_group_order_sub_customer_loop" main_customer=$mainCustomerId}
            <li>{$FIRSTNAME} {$LASTNAME}</li>
        {/loop}
    </ul>

[group_order_main_customer]

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | id of a main customer |
|**sub_customer_id** | id of a sub customer linked to a main customer |
|**customer_id** | id of a customer linked to a main customer |
|**active** | if the main customer is active or not |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID    | id of the main customer |
|$CUSTOMER_ID   | id of the customer linked to this main customer |

### Exemple

    {loop type="group_order_main_customer" name="main_customer_loop" customer_id=$customer_id active=true}
        {assign "isMainCustomer" 1}
    {/loop}


[group_order_sub_order]

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | id of a sub order |
|**sub_customer** | id of a sub customer |
|**group_order** | id of the main order |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID    | id of the sub order |
|$SUB_CUSTOMER_ID    | id of the sub customer |
|$GROUP_ORDER_ID    | id of the main order |
|$PRODUCT_IDS    | ids of the product of this sub order |
|$ORDER_NUMBER    | Ref of the main order |
|$DATE    | Creation date |
|$AMOUNT    | total price of this sub order |

## Smarty plugin

### groupOrderSubCustomerName

#### Input arguments

|Argument |Description |
|---      |--- |
|**item_id** | id of a cart item |
|**order_product_id** | id of an order product |

#### Output arguments

|Variable   |Description |
|---        |--- |
|$subCustomerName    | first name and last name of a sub customer |




