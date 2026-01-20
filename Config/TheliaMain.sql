
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- group_order_main_customer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_order_main_customer`;

CREATE TABLE `group_order_main_customer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `customer_id` INTEGER NOT NULL,
    `active` TINYINT DEFAULT 1,
    PRIMARY KEY (`id`),
    INDEX `fi_group_order_customer_id` (`customer_id`),
    CONSTRAINT `fk_group_order_customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group_order
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_order`;

CREATE TABLE `group_order`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `main_customer_id` INTEGER NOT NULL,
    `order_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_group_order_main_customer_id` (`main_customer_id`),
    INDEX `fi_group_order_order_id` (`order_id`),
    CONSTRAINT `fk_group_order_main_customer_id`
        FOREIGN KEY (`main_customer_id`)
        REFERENCES `group_order_main_customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_group_order_order_id`
        FOREIGN KEY (`order_id`)
        REFERENCES `order` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group_order_sub_customer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_order_sub_customer`;

CREATE TABLE `group_order_sub_customer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `main_customer_id` INTEGER NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255),
    `address1` VARCHAR(255) NOT NULL,
    `address2` VARCHAR(255),
    `address3` VARCHAR(255),
    `city` VARCHAR(255) NOT NULL,
    `zip_code` VARCHAR(255) NOT NULL,
    `country_id` INTEGER NOT NULL,
    `login` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `group_order_email_unique` (`email`),
    UNIQUE INDEX `group_order_login_unique` (`login`),
    INDEX `fi_group_order_sub_main_id` (`main_customer_id`),
    INDEX `fi_group_order_country_id` (`country_id`),
    CONSTRAINT `fk_group_order_sub_main_id`
        FOREIGN KEY (`main_customer_id`)
        REFERENCES `group_order_main_customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_group_order_country_id`
        FOREIGN KEY (`country_id`)
        REFERENCES `country` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group_order_sub_order
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_order_sub_order`;

CREATE TABLE `group_order_sub_order`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `sub_customer_id` INTEGER NOT NULL,
    `group_order_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_group_order_sub_order_sub_customer_id` (`sub_customer_id`),
    INDEX `fi_group_order_sub_order_id` (`group_order_id`),
    CONSTRAINT `fk_group_order_sub_order_sub_customer_id`
        FOREIGN KEY (`sub_customer_id`)
        REFERENCES `group_order_sub_customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_group_order_sub_order_id`
        FOREIGN KEY (`group_order_id`)
        REFERENCES `group_order` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group_order_product
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_order_product`;

CREATE TABLE `group_order_product`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `sub_order_id` INTEGER NOT NULL,
    `order_product_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_group_order_product_sub_order_id` (`sub_order_id`),
    INDEX `fi_group_order_order_product_id` (`order_product_id`),
    CONSTRAINT `fk_group_order_product_sub_order_id`
        FOREIGN KEY (`sub_order_id`)
        REFERENCES `group_order_sub_order` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_group_order_order_product_id`
        FOREIGN KEY (`order_product_id`)
        REFERENCES `order_product` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group_order_cart_item
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_order_cart_item`;

CREATE TABLE `group_order_cart_item`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `sub_customer_id` INTEGER NOT NULL,
    `cart_item_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_group_order_cart_sub_customer_id` (`sub_customer_id`),
    INDEX `fi_group_order_cart_cart_item_id` (`cart_item_id`),
    CONSTRAINT `fk_group_order_cart_sub_customer_id`
        FOREIGN KEY (`sub_customer_id`)
        REFERENCES `group_order_sub_customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_group_order_cart_cart_item_id`
        FOREIGN KEY (`cart_item_id`)
        REFERENCES `cart_item` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
