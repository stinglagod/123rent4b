<?php

namespace backend\urls;

use rent\entities\Shop\Category\Category;
use rent\readModels\Shop\CategoryReadRepository;
use yii\base\InvalidParamException;
use yii\base\BaseObject;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\UrlNormalizerRedirectException;
use yii\web\UrlRuleInterface;
use Yii;

class CategoryUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = 'shop/catalog';

    private $repository;
    private $cache;

    public function __construct(CategoryReadRepository $repository, Cache $cache, $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
        $this->cache = $cache;
    }

    public function parseRequest($manager, $request)
    {

        $layout=null;
        if (preg_match('#^shop/order/catalog#is', $request->pathInfo, $matches)) {
            $this->prefix='shop/order/catalog';
            $layout ='order';
        }

        if (preg_match('#^' . $this->prefix . '/(.*[a-z0-9_-])$#is', $request->pathInfo, $matches)) {

            $path = $matches['1'];

            $result = $this->cache->getOrSet(['category_route', 'path' => $path], function () use ($path) {
                if (!$category = $this->repository->findBySlug($this->getPathSlug($path))) {
                    return ['id' => null, 'path' => null];
                }
                return ['id' => $category->id, 'path' => $this->getCategoryPath($category)];
            }, null, new TagDependency(['tags' => ['categories']]));

            if (empty($result['id'])) {
                if ($layout) {
                    return ['shop/catalog/category404', ['layout'=>$layout]];
                } else {
                return false;
                }
            }

            if ($path != $result['path']) {
                throw new UrlNormalizerRedirectException(['shop/catalog/category', 'id' => $result['id'],'layout'=>$layout], 301);
            }

            return ['shop/catalog/category', ['id' => $result['id'],'layout'=>$layout]];
        }
        return false;
    }

    public function createUrl($manager, $route, $params)
    {
        if ($route == 'shop/catalog/category') {
            if (empty($params['id'])) {
                throw new InvalidParamException('Empty id.');
            }
            $id = $params['id'];

            $url = $this->cache->getOrSet(['category_route', 'id' => $id], function () use ($id) {
                if (!$category = $this->repository->find($id)) {
                    return null;
                }
                return $this->getCategoryPath($category);
            }, null, new TagDependency(['tags' => ['categories']]));

            if (!$url) {
                throw new InvalidParamException('Undefined id.');
            }

            $url = $this->prefix . '/' . $url;
            unset($params['id']);
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }

            return $url;
        }
        return false;
    }

    private function getPathSlug($path): string
    {
        $chunks = explode('/', $path);
        return end($chunks);
    }

    private function getCategoryPath(Category $category): string
    {
        $chunks = ArrayHelper::getColumn($category->getParents()->andWhere(['>', 'depth', 0])->all(), 'slug');
        $chunks[] = $category->slug;
        return implode('/', $chunks);
    }
}