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
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'login' => $this->string(),
            'password_hash' => $this->string(),
            'created_on' => $this->dateTime(),
            'is_deleted' => $this->boolean(),
            'role_id' => $this->integer(),
        ]);

        $this->createTable('roles', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'claim' => $this->integer(),
        ]);

        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'content' => $this->string(),
            'is_deleted' => $this->boolean(),
            'created_on' => $this->dateTime(),
            'image_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);

        $this->createTable('images', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
        ]);

        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'is_deleted' => $this->boolean(),
            'created_on' => $this->dateTime(),
            'user_id' => $this->integer(),
            'post_id' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-users-role_id',
            'users',
            'role_id'
        );

        $this->createIndex(
            'idx-posts-image_id',
            'posts',
            'image_id'
        );

        $this->createIndex(
            'idx-posts-category_id',
            'posts',
            'category_id'
        );

        $this->createIndex(
            'idx-comments-user_id',
            'comments',
            'user_id'
        );

        $this->createIndex(
            'idx-comments-post_id',
            'comments',
            'post_id'
        );

        $this->addForeignKey(
            'fk-users-role_id',
            'users',
            'role_id',
            'roles',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-posts-image_id',
            'posts',
            'image_id',
            'images',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-posts-category_id',
            'posts',
            'category_id',
            'categories',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-comments-user_id',
            'comments',
            'user_id',
            'users',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-comments-post_id',
            'comments',
            'post_id',
            'posts',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-users-role_id', 'users');
        $this->dropIndex('idx-posts-image_id', 'posts');
        $this->dropIndex('idx-posts-category_id', 'posts');
        $this->dropIndex('idx-comments-user_id', 'comments');
        $this->dropIndex('idx-comments-post_id', 'comments');

        $this->dropTable('users');
        $this->dropTable('users');
        $this->dropTable('users');
        $this->dropTable('users');
        $this->dropTable('users');
    }
}
