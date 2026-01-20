SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE group_order_sub_customer ADD UNIQUE (email);
ALTER TABLE group_order_sub_customer ADD UNIQUE (login);

SET FOREIGN_KEY_CHECKS = 1;
