<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Repository;

use Cai\Foundation\Repository;

class ShopPackageRepository extends Repository
{
    protected $_connection = 'shop';

    protected $_table = 'shop_package';

    protected function buildSearchQuery($uid)
    {

        $query = $this->table()->where('uid',$uid);

        return $query;
    }

    /*
     * 查询玩家背包
     *
     * */
    public function searchPackageResult($uid)
    {
        $fields = ['id','uid','product_id','product_count','updated_at'];
        $query = $this->buildSearchQuery($uid);

        if($fields){
            $query = $query->select($fields);
        }
        return $query->get();
    }

    /*
     * 查询玩家背包内是否存在某个商品
     *
     * */
    public function searchPackageByIdResult($uid,$productId)
    {
        $fields = ['id','uid','product_id','product_count','updated_at'];
        $query = $this->table()->where('uid', $uid)->where('product_id', $productId)->get();;

        if($fields){
            $query = $query->select($fields);
        }
        return $query->get();
    }

    /*
     * 修改玩家背包内某商品数量
     *
     * */
    public function updatePackageById($uid,$product_count){
       return $this->table()
           ->where('uid', $uid)
           ->where('product_id', 1)
           ->increment('product_count', $product_count);
    }

    /*
     * 添加玩家背包道具
     *
     * */
    public function insertPackageById($uid, $productId, $product_count, $updated_at)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'product_id' => $productId,
            'product_count' => $product_count,
            'updated_at' => $updated_at,
        ]);
    }
}
