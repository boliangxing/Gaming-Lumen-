<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class ShopRepository extends Repository
{
    protected $_connection = 'shop';

    protected $_table = 'shop_purchase_logs';


    protected function buildSearchQuery(array $search)
    {



        $query = $this->table();

        return $query;
    }


    public function searchExchangeResult($search,$pageIndex,$pageSize,$startTime,$endTime,$sort)
    {

        $fields = ['id', 'uid', 'product_id','product_count','created_at','product_cprice'];

        $query = $this->table()
            ->where('created_at','>=',$startTime)
            ->where('created_at','<=',$endTime)
            ->forPage($pageIndex,$pageSize);
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

}
