<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<!--main content start-->
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php

                foreach($posts as $post):?>
                    <article class="post post-list">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="post-thumb">
                                <a href="<?= Url::toRoute(['site/view','id'=>$post->id]);?>"><img src="<?= $post->getImage();?>" alt="" class="pull-left"></a>

                                <a href="<?= Url::toRoute(['site/view','id'=>$post->id]);?>" class="post-thumb-overlay text-center">
                                    <div class="text-uppercase text-center">View Post</div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="post-content">
                                <header class="entry-header text-uppercase">
                                    <h6><a href="<?= Url::toRoute(['site/category','id'=>$post->category->id]);?>"> <?= $post->category?->name?></a></h6>

                                    <h1 class="entry-title"><a href="<?= Url::toRoute(['site/view','id'=>$post->id]);?>">Home is peaceful place</a></h1>
                                </header>
                                <div class="entry-content">
                                    <p><?= $post->description?>
                                    </p>
                                </div>
                                <div class="social-share">
                                    <span class="social-share-title pull-left text-capitalize">By <?= $post->author->name;?> On <?= $post->getDate();?></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach;?>

                <?php
                echo LinkPager::widget([
                    'pagination' => $pagination,
                ]);
                ?>
            </div>
            <?= $this->render('/partials/sidebar', [
                'popular'=>$popular,
                'recent'=>$recent,
                'categories'=>$categories
            ]);?>
        </div>
    </div>
</div>
<!-- end main content-->