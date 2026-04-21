<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Schema migrated from legacy database/bag_biz.sql (structure only; seed data is separate).
 */
final class InitialSchema extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('SET NAMES utf8mb4');

        $this->execute(<<<'SQL'
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute(<<<'SQL'
CREATE TABLE `suppliers` (
  `suppliers_id` int(11) NOT NULL AUTO_INCREMENT,
  `suppliers_name` varchar(100) NOT NULL,
  `stock_brand` varchar(100) NOT NULL,
  PRIMARY KEY (`suppliers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute(<<<'SQL'
CREATE TABLE `stock_inventory` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_name` varchar(100) NOT NULL,
  `stock_brand` varchar(100) NOT NULL,
  `stock_category` varchar(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `stock_description` varchar(10000) NOT NULL,
  `stock_img` varchar(200) NOT NULL,
  `stock_price` int(11) NOT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute(<<<'SQL'
CREATE TABLE `cart_item` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `item_img` varchar(100) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_price` varchar(100) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute(<<<'SQL'
CREATE TABLE `purchase` (
  `purchase_id` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `purchase_date` date NOT NULL,
  `payment_resit` varchar(200) NOT NULL,
  `purchase_validation` varchar(100) NOT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `User_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute(<<<'SQL'
CREATE TABLE `purchase_item` (
  `purchase_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_img` varchar(200) NOT NULL,
  `stock_name` varchar(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `stock_price` varchar(100) NOT NULL,
  `purchase_date` date NOT NULL,
  PRIMARY KEY (`purchase_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute(<<<'SQL'
CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `purchase_id` varchar(100) NOT NULL,
  `delivery_agent` varchar(100) NOT NULL,
  `delivery_status` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `payment_status` varchar(100) NOT NULL,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

        $this->execute('ALTER TABLE `cart_item` AUTO_INCREMENT = 121');
        $this->execute('ALTER TABLE `delivery` AUTO_INCREMENT = 21');
        $this->execute('ALTER TABLE `purchase_item` AUTO_INCREMENT = 80');
        $this->execute('ALTER TABLE `stock_inventory` AUTO_INCREMENT = 32');
        $this->execute('ALTER TABLE `suppliers` AUTO_INCREMENT = 15');
        $this->execute('ALTER TABLE `users` AUTO_INCREMENT = 23');
    }

    public function down(): void
    {
        $this->execute('DROP TABLE IF EXISTS `delivery`');
        $this->execute('DROP TABLE IF EXISTS `purchase_item`');
        $this->execute('DROP TABLE IF EXISTS `purchase`');
        $this->execute('DROP TABLE IF EXISTS `cart_item`');
        $this->execute('DROP TABLE IF EXISTS `stock_inventory`');
        $this->execute('DROP TABLE IF EXISTS `suppliers`');
        $this->execute('DROP TABLE IF EXISTS `users`');
    }
}
