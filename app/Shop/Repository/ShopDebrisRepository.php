<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Repository;

use Cai\Foundation\Repository;

class ShopDebrisRepository extends Repository
{
    protected $_connection = 'shop';

    protected $_table = 'shop_debris';

    protected function buildSearchQuery($uid)
    {
        $query = $this->table()->where('uid',$uid);

        return $query;
    }

    /*
     * 查询玩家碎片数量
     *
     * */
    public function searchDebrisResult($uid)
    {
        $fields = ['id','uid','debris_count','created_at'];
        $query = $this->buildSearchQuery($uid);

        if($fields){
            $query = $query->select($fields);
        }
        return $query->get();
    }

    /*
     * 查询玩家碎片数量是否充足
     *
     * */
    public function searchDebrisCountResult($uid,$count)
    {


        $query = $this->table()->where('uid',$uid)->where('debris_count','>=',$count)->get();

        return $query;
    }

    /*
     * 修改玩家碎片数量
     *
     * */
    public function updateDebrisExchange($uid,$Count)
    {

        return $this->table()->where('uid', $uid)->decrement('debris_count', $Count);

    }


}
