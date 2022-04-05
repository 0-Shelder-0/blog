<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CommentForm extends Model
{
    public $comment;

    public function rules()
    {
        return [
            [['comment'], 'required'],
            [['comment'], 'string', 'length' => [3,250]]
        ];
    }

    public function saveComment($post_id)
    {
        $comment = new Comment;

        $comment->text = $this->comment;
        $comment->is_deleted = false;
        $comment->created_on = date('Y-m-d');
        $comment->user_id = Yii::$app->user->id;
        $comment->post_id = $post_id;

        return $comment->save();
    }
}