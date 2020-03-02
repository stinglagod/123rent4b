<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\Product;
use backend\models\ProductSearch;
use common\models\Category;
use yii\web\NotFoundHttpException;

class CatalogController extends \yii\web\Controller
{
    /**
     * Выводит главный каталог
     * @param null $alias алиас текущей категории
     * @return string
     */
    public function actionIndex($categoryAlias='/',$productAlias=null)
    {
//        return $categoryAlias;
        $category='';
        $htmRightDetail='';
        $root=Category::getRoot()->id;
        $menuCatalogItems=Category::findOne($root)->tree();
        $categoryAlias='/'.$categoryAlias;
        //    TODO: Сделать, что бы алиас категории заканчивался слешом / . Пока костыльь
        $categoryAlias=substr($categoryAlias, 0, -1);
        $category=Category::findCategory($categoryAlias);




        if ($productAlias) {
            return $this->actionViewProduct($menuCatalogItems[0],$category,$productAlias);
        } else if ($categoryAlias) {
            return $this->actionViewCategory($menuCatalogItems[0],$category );
        } else {
            return $this->actionViewMainPage();
        }

    }

    public function actionViewProduct($menuCatalogItems,$category,$productAlias=null)
    {
//        TODO: выдать 404 ошибку если товар не найден
        $product=$this->findProduct($productAlias);
        return $this->render('_productDetail',[
            'model'=>$product,
            'category'=>$category,
        ]);
//        return $this->renderPartial('_productDetail',[
//            'product'=>$product
//        ]);
    }
    /*
     * @param Category $category
     */
    public function actionViewCategory($menuCatalogItems,$category)
    {
        /** @var Category $category*/
        $searchModel = new ProductSearch();
        $params=\Yii::$app->request->queryParams;
        $params['alias']=$category->alias;
        $productsDataProvider = $searchModel->search($params);
        return $this->render('_categoryDetail',[
            'category'=>$category,
            'menuCatalogItems'=>$menuCatalogItems,
            'productsDataProvider'=>$productsDataProvider,
        ]);
    }

    public function actionViewMainPage()
    {
        return $this->renderPartial('_mainPage',[
            'product'=>''
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProduct($id)
    {
        if (($model = \common\models\Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

}
