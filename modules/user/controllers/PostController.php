<?php

namespace app\modules\user\controllers;

use app\models\Category;
use app\models\ImageUpload;
use app\models\Post;
use app\models\PostSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Post models.
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $searchModel = new PostSearch();
        $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Post();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->created_on = date('Y-m-d');
            $model->viewed = 0;
            $model->is_deleted = false;
            $model->user_id = Yii::$app->user->id;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = $this->findModel($id);
        $model->is_deleted = true;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSetCategory($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $post = $this->findModel($id);
        $selectedCategory = $post->category?->id;
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'name');

        if (Yii::$app->request->isPost) {
            $category = Yii::$app->request->post('category');
            if ($post->saveCategory($category)) {
                return $this->redirect(['view', 'id' => $post->id]);
            }
        }

        return $this->render('category', [
            'post' => $post,
            'selectedCategory' => $selectedCategory,
            'categories' => $categories
        ]);
    }

    public function actionSetImage($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ImageUpload;

        if (Yii::$app->request->isPost)
        {
            $post = $this->findModel($id);
            $file = UploadedFile::getInstance($model, 'image');

            if($post->saveImage($model->uploadFile($file, $post->image_id)))
            {
                return $this->redirect(['view', 'id'=>$post->id]);
            }
        }

        return $this->render('image', ['model'=>$model]);
    }
}
