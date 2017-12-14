<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Repository;

use Cai\Foundation\Repository;

class ShopProductRepository extends Repository
{
    protected $_connection = 'shop';

    protected $_table = 'shop_product';


    protected function buildSearchQuery(array $search)
    {
        $query = $this->table();

        return $query;
    }

    /*
     * 查询商品列表
     *
     * */
    public function searchProductResult($search,$pageIndex,$pageSize,$sort)
    {
        $fields = ['product_id','product_name','category','product_pic','updated_at'];
        $query = $this->buildSearchQuery($search)->forPage($pageIndex,$pageSize);
        if(is_array($sort)){
            foreach($sort as $field=>$order){
                if(!in_array($order,['asc','desc']))
                    $query = $query->orderBy($field,$order);
            }
        }
        if($fields){
            $query = $query->select($fields);
        }
        return $query->get();
    }

    /*
     * 查询某商品详细信息
     *
     * */
    public function searchProductByIdResult($productID,$sort)
    {
        //$fields = ['product_id','product_name','category','product_pic','updated_at'];
        $query = $this->table()->where('product_id',$productID)->sharedLock()->get();
        if(is_array($sort)){
            foreach($sort as $field=>$order){
                if(!in_array($order,['asc','desc']))
                    $query = $query->orderBy($field,$order);
            }
        }
//        if($fields){
//            $query = $query->select($fields);
//            var_dump($query);die;
//        }
        return $query;
    }


}
