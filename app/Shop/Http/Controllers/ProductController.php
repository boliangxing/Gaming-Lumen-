<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Http\Controllers;
use App\Shop\Repository\ShopPurchaseLogsRepository;
use App\Shop\Repository\ShopProductRepository;
use Cai\Foundation\Controller;
use App\Shop\Http\Controllers\PackageController;
class ProductController extends Controller
{
    public function product()
    {
        $pageIndex = $this->request->get('page',1);
        $pageSize = $this->request->get('size',1);
        $sort = $this->request->get('sort','asc');
        $result = (new ShopProductRepository())->searchProductResult([],$pageIndex,$pageSize,$sort);
        return $this->data($result);
    }

    public function purchaseLogs()
    {
        $uid = $this->getUserId();
        $pageIndex = $this->request->get('page',1);
        $pageSize = $this->request->get('size',1);
        $sort = $this->request->get('sort','asc');
        $result = (new ShopPurchaseLogsRepository())->searchPurchaseLogsResult($uid,$pageIndex,$pageSize,$sort);
        return $this->data($result);
    }
    public function test(){

        $package = new PackageController();
        $result = $package->package();
        //var_dump($result);die;
        return $this->data($result);

    }

}