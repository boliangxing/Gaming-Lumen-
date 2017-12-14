<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Repository;

use Cai\Foundation\Repository;

class ShopPurchaseLogsRepository extends Repository
{
    protected $_connection = 'shop';

    protected $_table = 'shop_purchase_logs';

    protected function buildSearchQuery($uid)
    {
        $query = $this->table()->where('uid',$uid);

        return $query;
    }

    /*
     * 查询某玩家道具交易记录
     *
     * */
    public function searchPurchaseLogsResult($uid,$pageIndex,$pageSize,$sort)
    {
        $fields = ['id','uid','product_id','product_count','product_cprice','created_at'];
        $query = $this->buildSearchQuery($uid)->forPage($pageIndex,$pageSize);;
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
     * 添加某玩家道具交易记录
     *
     * */
    public function insertPurchaseLogsById($uid, $productId, $product_count,$product_cprice, $created_at)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'product_id' => $productId,
            'product_count' => $product_count,
            'product_cprice' => $product_cprice,
            'created_at' => $created_at,
        ]);
    }
}
