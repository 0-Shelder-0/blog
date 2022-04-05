<?php

use app\models\Claim;
use yii\db\Migration;

/**
 * Class m220405_175046_add_roles
 */
class m220405_175046_add_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('role', [
            'name' => 'user',
            'claim' => Claim::USER->value,
        ]);

        $this->insert('role', [
            'name' => 'admin',
            'claim' => Claim::ADMIN->value,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('role', ['claim' => Claim::USER->value]);
        $this->delete('role', ['claim' => Claim::ADMIN->value]);
    }
}
