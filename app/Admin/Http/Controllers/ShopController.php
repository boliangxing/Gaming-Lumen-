<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Admin\Http\Controllers;

use App\Admin\Repository\ShopRepository;
use Cai\Foundation\BackendController;

class ShopController extends BackendController
{
    /**
     * @var UserRepository
     */
    protected $repository;

//    public function __construct(UserRepository $repository)
//    {
//        parent::__construct();
//
//        $this->repository = $repository;
//    }

//    public function getProductList()
//    {
//        $pageIndex = $this->request->get('page',1);
//        $pageSize = $this->request->get('size',1);
//        $result = (new ShopRepository())->searchProductResult([],$pageIndex,$pageSize,[]);
//        return $this->data($result);
//    }

    public function getExchangeList()
    {
        $this->config = require __DIR__.'/../../Conf/product.php';

        $taskList = $this->config['productList'];
        $startTime = $this->request->get('startTime',date('Y-m-d').' 00:00:00');
        $endTime = $this->request->get('endTime',date('Y-m-d').' 23:59:59');
        $pageIndex = $this->request->get('page',1);
        $pageSize = $this->request->get('size',1);

        $result = (new ShopRepository())->searchExchangeResult([],$pageIndex,$pageSize,$startTime,$endTime,[]);
        foreach ($result as $s){
            $s->productid=$taskList[$s->productid];
        }
        return $this->data($result);
    }

}