<?php
    $this->title = 'Cabinet';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class=cabinet-index>
    <div>
        <?= \yii\helpers\Html::encode($this->title) ?>
    </div>
    <h2>Attach profile</h2>
    <?= yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['cabinet/network/attach'],
    ]); ?>
</div>
