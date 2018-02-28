<?php

/* @var $this yii\web\View */
/* @var $model  */

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \frontend\helpers\HighlightHelper;

$this->title = 'My Site';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Последняя новость</h2>
                <p><?=$title ?></p>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                    Подробнее
                </button>

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Актуальная новость</h4>
                            </div>
                            <div class="modal-body">
                                <?= $description ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <h2>Поиск по новостям</h2>
                <?php $form = ActiveForm::begin(['id' => 'search-form']) ?>
                <?= $form->field($model, 'keyword')?>
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-default'])?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php if ($result): ?>
                    <hr>
                    <h3>Результаты поиска для "<?=$model->keyword ?>":</h3>
                    <?php foreach ($result as $item): ?>
                        <br>
                        <p><?=$item['title'] ?></p>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#myModal<?=$item['id'] ?>">
                            Полная новость
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal<?=$item['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel<?=$item['id'] ?>"><?=$item['title'] ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <p><?=HighlightHelper::boldKeyword($model->keyword, $item['description']) ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
