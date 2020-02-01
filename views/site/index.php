<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'balance',


        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

