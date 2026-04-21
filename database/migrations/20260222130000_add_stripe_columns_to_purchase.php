<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStripeColumnsToPurchase extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('ALTER TABLE `purchase` MODIFY `payment_resit` VARCHAR(200) NULL DEFAULT NULL');
        $this->execute('ALTER TABLE `purchase` ADD COLUMN `stripe_checkout_session_id` VARCHAR(255) NULL DEFAULT NULL AFTER `payment_resit`');
        $this->execute('ALTER TABLE `purchase` ADD COLUMN `stripe_payment_intent_id` VARCHAR(255) NULL DEFAULT NULL AFTER `stripe_checkout_session_id`');
    }

    public function down(): void
    {
        $this->execute('UPDATE `purchase` SET `payment_resit` = \'\' WHERE `payment_resit` IS NULL');
        $this->execute('ALTER TABLE `purchase` DROP COLUMN `stripe_payment_intent_id`');
        $this->execute('ALTER TABLE `purchase` DROP COLUMN `stripe_checkout_session_id`');
        $this->execute('ALTER TABLE `purchase` MODIFY `payment_resit` VARCHAR(200) NOT NULL');
    }
}
