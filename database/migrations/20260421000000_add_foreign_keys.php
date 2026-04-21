<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Adds foreign key constraints that were missing from the legacy schema.
 *
 * cart_item.id          → users.id
 * purchase.id           → users.id
 * purchase_item.id      → users.id
 * purchase_item.purchase_id → purchase.purchase_id
 * purchase_item.stock_id    → stock_inventory.stock_id
 * delivery.id           → users.id
 * delivery.purchase_id  → purchase.purchase_id
 */
final class AddForeignKeys extends AbstractMigration
{
    public function up(): void
    {
        // cart_item → users
        $this->execute('ALTER TABLE `cart_item`
            ADD CONSTRAINT `fk_cart_item_user`
            FOREIGN KEY (`id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE');

        // purchase → users
        $this->execute('ALTER TABLE `purchase`
            ADD CONSTRAINT `fk_purchase_user`
            FOREIGN KEY (`id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE');

        // purchase_item → purchase
        $this->execute('ALTER TABLE `purchase_item`
            ADD CONSTRAINT `fk_purchase_item_purchase`
            FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`purchase_id`)
            ON DELETE CASCADE ON UPDATE CASCADE');

        // purchase_item → users
        $this->execute('ALTER TABLE `purchase_item`
            ADD CONSTRAINT `fk_purchase_item_user`
            FOREIGN KEY (`id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE');

        // purchase_item → stock_inventory (kept as RESTRICT so accidental stock deletion is blocked)
        $this->execute('ALTER TABLE `purchase_item`
            ADD CONSTRAINT `fk_purchase_item_stock`
            FOREIGN KEY (`stock_id`) REFERENCES `stock_inventory` (`stock_id`)
            ON DELETE RESTRICT ON UPDATE CASCADE');

        // delivery → users
        $this->execute('ALTER TABLE `delivery`
            ADD CONSTRAINT `fk_delivery_user`
            FOREIGN KEY (`id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE ON UPDATE CASCADE');

        // delivery → purchase
        $this->execute('ALTER TABLE `delivery`
            ADD CONSTRAINT `fk_delivery_purchase`
            FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`purchase_id`)
            ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(): void
    {
        $this->execute('ALTER TABLE `delivery` DROP FOREIGN KEY `fk_delivery_purchase`');
        $this->execute('ALTER TABLE `delivery` DROP FOREIGN KEY `fk_delivery_user`');
        $this->execute('ALTER TABLE `purchase_item` DROP FOREIGN KEY `fk_purchase_item_stock`');
        $this->execute('ALTER TABLE `purchase_item` DROP FOREIGN KEY `fk_purchase_item_user`');
        $this->execute('ALTER TABLE `purchase_item` DROP FOREIGN KEY `fk_purchase_item_purchase`');
        $this->execute('ALTER TABLE `purchase` DROP FOREIGN KEY `fk_purchase_user`');
        $this->execute('ALTER TABLE `cart_item` DROP FOREIGN KEY `fk_cart_item_user`');
    }
}
