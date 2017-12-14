<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Repository;

use Cai\Foundation\Repository;

class ShopDebrisLogsRepository extends Repository
{
    protected $_connection = 'shop';
    protected $_table = 'shop_debris_logs';
    protected function buildSearchQuery($uid)
    {
        $query = $this->table()->where('uid',$uid);

        return $query;
    }
    /*
     * 查询碎片兑换记录
     *
     * */
    public function searchDebrisLogsResult($uid,$pageIndex,$pageSize,$sort)
    {
        $fields = ['id','uid','product_id','debris_count','created_at'];
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
     * 添加碎片兑换记录
     *
     * */
    public function insertDebrisLogs($uid, $productId, $debris_count, $created_at)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'product_id' => $productId,
            'debris_count' => $debris_count,
            'created_at' => $created_at,
        ]);
    }
}
