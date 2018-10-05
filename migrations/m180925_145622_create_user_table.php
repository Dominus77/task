<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180925_145622_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'username' => $this->string()->notNull()->unique()->comment('Username'),
            'email' => $this->string()->notNull()->unique()->comment('Email'),
            'email_confirm_token' => $this->string()->comment('Email Confirm Token'),
            'password_hash' => $this->string()->notNull()->comment('Hash Password'),
            'password_reset_token' => $this->string()->unique()->comment('Password Token'),
            'auth_key' => $this->string(32)->notNull()->comment('Authorization Key'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Status'),
            'last_visit' => $this->integer()->comment('Last Visit'),
            'created_at' => $this->integer()->notNull()->comment('Created'),
            'updated_at' => $this->integer()->notNull()->comment('Updated'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
