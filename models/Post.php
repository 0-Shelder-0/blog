<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $content
 * @property int|null $viewed
 * @property bool|null $is_deleted
 * @property string|null $created_on
 * @property int|null $image_id
 * @property int|null $category_id
 * @property int|null $user_id
 *
 * @property Category $category
 * @property Comment[] $comments
 * @property Image $image
 * @property User $user
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['viewed', 'image_id', 'category_id', 'user_id'], 'default', 'value' => null],
            [['viewed', 'image_id', 'category_id', 'user_id'], 'integer'],
            [['is_deleted'], 'boolean'],
            [['created_on'], 'safe'],
            [['title', 'content'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'viewed' => 'Viewed',
            'is_deleted' => 'Is Deleted',
            'created_on' => 'Created On',
            'image_id' => 'Image ID',
            'category_id' => 'Category ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Image]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        $image = $this->hasOne(Image::className(), ['id' => 'image_id'])->one();
        return ($image) ? '/' . $image->url : '/no-image.png';
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPostComments()
    {
        return $this->getComments()->where(['is_deleted' => false])->all();
    }

    public static function getAll($pageSize = 5)
    {
        $query = Post::getNotDeletedPosts();
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $posts = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data['posts'] = $posts;
        $data['pagination'] = $pagination;

        return $data;
    }

    public static function getPopular()
    {
        return Post::getNotDeletedPosts()->orderBy('viewed desc')->limit(3)->all();
    }

    public static function getRecent()
    {
        return Post::getNotDeletedPosts()->orderBy('created_on asc')->limit(4)->all();
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->created_on);
    }

    public function viewedCounter()
    {
        $this->viewed += 1;
        return $this->save(false);
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if ($category != null) {
            $this->link('category', $category);
            return true;
        }

        return false;
    }

    public function saveImage($image_id)
    {
        if ($image_id !== null) {
            $this->image_id = $image_id;
            return $this->save(false);
        }

        return false;
    }

    private static function getNotDeletedPosts()
    {
        return Post::find()->where(['is_deleted' => false]);
    }
}
