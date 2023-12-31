<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category \rent\entities\Shop\Category\Category */
/* @var $searchModel \rent\forms\Shop\Search\SearchForm */
/* @var $order \rent\entities\Shop\Order\Order */

use rent\forms\manage\Client\Site\SiteChangeForm;
use yii\helpers\Html;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ChangeSiteWidget;

$this->title = $category->getSeoTitle();

$this->registerMetaTag(['name' =>'description', 'content' => $category->meta->description]);
$this->registerMetaTag(['name' =>'keywords', 'content' => $category->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
foreach ($category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = $category->name;

$this->params['active_category'] = $category;


?>


<?php if (trim($category->description)): ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= Yii::$app->formatter->asHtml($category->description, [
                'Attr.AllowedRel' => array('nofollow'),
                'HTML.SafeObject' => true,
                'Output.FlashCompat' => true,
                'HTML.SafeIframe' => true,
                'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]) ?>
        </div>
    </div>
<?php endif; ?>


<div class="catalog-search>">
    <?= $this->render('_search', [
        'searchForm'=>$searchModel
    ]) ?>
</div>

<div class="catalog-index">

    <p>
        <?= Html::a('Добавить Категорию', ['create','id'=>$category->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить Товар', ['product-create','category_id'=>$category->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Редактировать Категорию', ['update','id'=>$category->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Удалить категорию', ['delete','id'=>$category->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?=ChangeSiteWidget::widget()?>
                </div>
                <div class="box-body">
                    <?=
                    FancytreeWidget::widget([
                        'options' =>[
                            'source' => $tree,
                            'extensions' => ['dnd'],
                            'autoActivate'=> true,
                            'dnd' => [
                                'preventVoidMoves' => true,
                                'preventRecursiveMoves' => true,
                                'autoExpandMS' => 400,
                                'dragStart' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                'dragEnter' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                'dragDrop' => new JsExpression('function(node, data) {
                                    $.get("'.Url::toRoute(['shop/catalog/move']).'",{item: data.otherNode.data.id, action: data.hitMode, second: node.data.id}, function(response){
//                                        if (response.status=="success") {
//                                            data.otherNode.moveTo(node, data.hitMode);
//                                            reloadPjaxs("#pjax_alerts");
//                                        }
                                    });
                                }'),
                            ],
                            'activate' => new JsExpression('function(event,data) {
                                var slug = data.node.data.slug;
//                                if (slug=="root") {
//                                    document.location.href = "'.Url::toRoute(['shop/catalog']).'"
//                                } else {
                                    let url = window.location.href + "?";
                                    url = url.substr(0,url.indexOf("?"))+"/"+slug;
//                                    console.log(url);
                                    document.location.href = url;
//                                }
                            }'),
                            'init' => new JsExpression('function(e,data) {
                                console.log("init");
                            }'),
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-md-9">
            </div>
        </div>
        <div class="col-md-9">
            <?= $this->render('_list', [
                'dataProvider' => $dataProvider,
                'category' => $category,
                'order' => $order
            ]) ?>
        </div>
    </div>
</div>
