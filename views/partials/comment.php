<?php if (!empty($comments)): ?>

    <?php foreach ($comments as $comment): ?>
        <div class="bottom-comment">
            <div class="comment-img">

            </div>

            <div class="comment-text">
                <a id="<?= 'comment-' . $comment->id; ?>" class="replay btn pull-right"
                   onclick="addUserName(event)"> Replay</a>
                <h5 id="<?= 'comment-login-' . $comment->id; ?>"><?= $comment->user?->login; ?></h5>

                <p class="comment-date">
                    <?= $comment->getDate(); ?>
                </p>

                <p class="para"><?= $comment->text; ?></p>
            </div>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<?php if (!Yii::$app->user->isGuest): ?>
    <div class="leave-comment">
        <h4>Leave a reply</h4>
        <?php if (Yii::$app->session->getFlash('comment')): ?>
            <div class="alert alert-success" role="alert">
                <?= Yii::$app->session->getFlash('comment'); ?>
            </div>
        <?php endif; ?>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => ['site/comment', 'id' => $post->id],
            'options' => ['class' => 'form-horizontal contact-form', 'role' => 'form']]) ?>
        <div class="form-group">
            <div class="col-md-12">
                <?= $form->field($commentForm, 'comment')->textarea(['id' => 'new-comment', 'class' => 'form-control', 'placeholder' => 'Write Message'])->label(false) ?>
            </div>
        </div>
        <button type="submit" class="btn send-btn">Add comment</button>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
<?php endif; ?>