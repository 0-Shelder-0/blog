<?php

use yii\db\Migration;

/**
 * Class m220404_153136_initial
 */
class m220404_153136_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'login' => $this->string(),
            'password_hash' => $this->string(),
            'created_on' => $this->dateTime(),
            'is_deleted' => $this->boolean(),
            'role_id' => $this->integer(),
        ]);

        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'claim' => $this->integer(),
        ]);

        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'content' => $this->string(),
            'is_deleted' => $this->boolean(),
            'created_on' => $this->dateTime(),
            'image_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);

        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
        ]);

        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'is_deleted' => $this->boolean(),
            'created_on' => $this->dateTime(),
            'user_id' => $this->integer(),
            'post_id' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-user-role_id',
            'user',
            'role_id'
        );

        $this->createIndex(
            'idx-post-image_id',
            'post',
            'image_id'
        );

        $this->createIndex(
            'idx-post-category_id',
            'post',
            'category_id'
        );

        $this->createIndex(
            'idx-comment-user_id',
            'comment',
            'user_id'
        );

        $this->createIndex(
            'idx-comment-post_id',
            'comment',
            'post_id'
        );

        $this->addForeignKey(
            'fk-user-role_id',
            'user',
            'role_id',
            'role',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-post-image_id',
            'post',
            'image_id',
            'image',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-post-category_id',
            'post',
            'category_id',
            'category',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-comment-user_id',
            'comment',
            'user_id',
            'user',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-comment-post_id',
            'comment',
            'post_id',
            'post',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user-role_id', 'user');
        $this->dropIndex('idx-post-image_id', 'post');
        $this->dropIndex('idx-post-category_id', 'post');
        $this->dropIndex('idx-comment-user_id', 'comment');
        $this->dropIndex('idx-comment-post_id', 'comment');

        $this->dropForeignKey('fk-user-role_id', 'user');
        $this->dropForeignKey('fk-post-image_id', 'post');
        $this->dropForeignKey('fk-post-category_id', 'post');
        $this->dropForeignKey('fk-comment-user_id', 'comment');
        $this->dropForeignKey('fk-comment-post_id', 'comment');

        $this->dropTable('user');
        $this->dropTable('role');
        $this->dropTable('post');
        $this->dropTable('category');
        $this->dropTable('image');
        $this->dropTable('comment');
    }
}
