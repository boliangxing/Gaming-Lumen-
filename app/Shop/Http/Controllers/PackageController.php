<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Http\Controllers;
use App\Shop\Repository\ShopPackageRepository;
use Cai\Foundation\Controller;

class PackageController extends Controller
{
    public function package()
    {
        $uid = 1;// $this->getUserId();
        $result = (new ShopPackageRepository())->searchPackageResult($uid);

        return $result;
    }

}