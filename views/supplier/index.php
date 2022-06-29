<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo Html::jsFile('@web/assets/59fa11c1/jquery.js'); ?>

<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

<!--        <p>-->
<!--            --><?//= Html::a(Yii::t('app', 'Create Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
<!--        </p>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'id' => 'grid',
        'columns' => [
            [
                'class'=>\yii\grid\CheckboxColumn::className(),
                'name'=>'id',
                'headerOptions' => ['width'=>'30'],
                'footer' => Html::a('export csv', "javascript:void(0);", ['class' => 'btn btn-success gridview','style' => 'float:left']) ,
//                'footer' => Html::a('删除', ['supplier/export', 'ids' => $post['id']],['data-confirm'=>'确定要删除吗？'])  ,
                'footerOptions' => ['colspan' => 3],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'footerOptions' => ['class'=>'hide']],
            ['attribute' => 'name', 'footerOptions' => ['class'=>'hide']],
            ['attribute' => 'code', 'footerOptions' => ['class'=>'hide']],
            [
                'attribute' => 't_status',
                'value' => function ($model) {
                    return \app\models\Supplier::dropDown('t_status', $model->t_status);
                },
                'filter' => \app\models\Supplier::dropDown('t_status'),
                'footerOptions' => ['class'=>'hide']
            ],
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, \app\models\Supplier $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                }
//            ],
        ],

    ]);

    ?>


</div>

<script type="text/javascript">
    $(".gridview").on("click", function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");;

        if(keys == "") {
            alert("Please select export data");
            return;
        }

        var url = '<?= Yii::$app->urlManager->createUrl(['supplier/csv'])?>&ids='+keys;

        window.location = url;

    });
</script>
