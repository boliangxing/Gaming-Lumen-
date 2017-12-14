<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Http\Controllers;
use App\Shop\Repository\ShopDebrisRepository;
use App\Shop\Repository\ShopDebrisLogsRepository;
use App\Shop\Repository\ShopPackageRepository;
use App\Shop\Repository\ShopProductRepository;
use Cai\Foundation\Controller;
use DB;
class DebrisController extends Controller
{
    public function debris()
    {
        $uid = $this->getUserId();
        $result = (new ShopDebrisRepository())->searchDebrisResult($uid);
        return $this->data($result);
    }

    public function debrisLogs()
    {
        $uid = $this->getUserId();
        $pageIndex = $this->request->get('page',1);
        $pageSize = $this->request->get('size',1);
        $sort = $this->request->get('sort','asc');
        $result = (new ShopDebrisLogsRepository())->searchDebrisLogsResult($uid,$pageIndex,$pageSize,$sort);
        return $this->data($result);
    }

    public function exChangeDebris(){
        $uid =1;// $this->getUserId();
        DB::connection('shop')->select("XA START '$xid'");
        $productID = $this->request->get('productID',1);
        $sort = $this->request->get('sort','asc');

        $r = (new ShopProductRepository())->searchProductByIdResult($productID,$sort);
        $count=$r[0]->cprice/100;

        $r2 = (new ShopDebrisRepository())->searchDebrisCountResult($uid,$count);
        if(!empty($r2[0]->uid)){

            DB::beginTransaction();
            try {

                $created_at = date('Y-m-d H:i:s');
                (new ShopDebrisRepository())->updateDebrisExchange($uid,$count);
                (new ShopPackageRepository())->updatePackageById($uid,$count);
                $productId=1;
                (new ShopDebrisLogsRepository())->insertDebrisLogs($uid, $productId, $count, $created_at);
                DB::commit();
                $json = array('success' => '兑换成功');
                return response()->json($json);
            }catch (Exception $e){
                DB::rollBack();
            }
        }else{
            $json = array('success' => '碎片不足 ');
            return response()->json($json);
        }

    }



}