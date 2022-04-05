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
            [['is_deleted'], 'boolean'],
            [['created_on'], 'safe'],
            [['image_id', 'category_id'], 'default', 'value' => null],
            [['image_id', 'category_id'], 'integer'],
            [['title', 'content'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
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
        ];
    }

    public static function getAll($pageSize = 5)
    {
        $query = Post::find();
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>$pageSize]);
        $posts = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data['posts'] = $posts;
        $data['pagination'] = $pagination;

        return $data;
    }

    public static function getPopular()
    {
        return Post::find()->orderBy('viewed desc')->limit(3)->all();
    }

    public static function getRecent()
    {
        return Post::find()->orderBy('created_on asc')->limit(4)->all();
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->created_on);
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
        return ($image) ? $image->url : '/no-image.png';
    }

    public function viewedCounter()
    {
        $this->viewed += 1;
        return $this->save(false);
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if($category != null)
        {
            $this->link('category', $category);
            return true;
        }
    }
}
