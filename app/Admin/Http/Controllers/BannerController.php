<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 12:00
 */
namespace App\Admin\Http\Controllers;

use App\Admin\Repository\BannerLocationRepository;
use App\Admin\Repository\BannerRepository;
use Cai\Foundation\BackendController;
use Cai\Facades\Storage;

class BannerController extends BackendController
{
    protected $locationRepository;
    protected $repository;

    public function __construct(BannerLocationRepository $bannerLocationRepository, BannerRepository $bannerRepository)
    {
        $this->locationRepository = $bannerLocationRepository;
        $this->repository = $bannerRepository;

        parent::__construct();
    }

    public function getLocationList()
    {
        $list = $this->locationRepository->getAll();
        return $this->data($list);
    }

    public function addLocation()
    {
        $name = $this->request->input('name');
        $location = $this->request->input('location');
        $type = $this->request->input('type');

        if(!$this->locationRepository->isValueUnique('location', $location))
            return $this->fail('location项不能重复');

        if (!$type) $type = BannerLocationRepository::TYPE_CAROUSEL;

        $id = $this->locationRepository->add($name, $location, $type);
        if ($id){
            $date = [
                'id' => $id,
                'name' => $name,
                'location' => $location,
                'type' => $type,
            ];
            return $this->data($date);
        }
        else{
            return $this->fail('系统内部错误');
        }
    }

    public function updateLocation()
    {
        $id = $this->request->input('id');
        $name = $this->request->input('name');
        $location = $this->request->input('location');
        $type = $this->request->input('type');

        $data = $this->locationRepository->getById($id);
        if (!$data)
            return $this->fail('数据不存在');

        $locationTempData = $this->locationRepository->getByLocation($location);

        if($locationTempData && $locationTempData->id != $data->id)
            return $this->fail('location项不能重复');

        if (!$type) $type = BannerLocationRepository::TYPE_CAROUSEL;
        if ($this->locationRepository->update($id, $name, $location, $type)){
            $date = [
                'id' => $id,
                'name' => $name,
                'location' => $location,
                'type' => $type,
            ];
            return $this->data($date);
        }
        else{
            return $this->fail('没有数据更新');
        }
    }

    public function deleteLocation()
    {
        $id = $this->request->input('id');
        if ($this->locationRepository->destroy($id)) {
            return $this->success();
        }
        else{
            return $this->fail('系统内部错误');
        }
    }

    public function getAll(){
        return $this->data($this->repository->getAll());
    }

    public function getByLocation(){
        $location = $this->request->input('location');
        return $this->data($this->repository->getByLocation($location));
    }

    public function add()
    {
        $this->validate($this->request, [
            'img' => 'required|mimes:jpeg,jpg,bmp,png',
        ], [
            'img.mimes' => '必须是图片',
        ]);

        //$name = $this->request->input('name');
        $title = $this->request->input('title');
        $location = $this->request->input('location');
        $beginTime = $this->request->input('begin_time');
        $expireTime = $this->request->input('expire_time');
        $url = $this->request->input('url');
        $index = $this->request->input('index');

        if(!$this->locationRepository->getByLocation($location))
            return $this->fail('错误的location');

        $imgPath = Storage::uploadNewsImage($this->request->file('img'));
        $imgUri = $imgPath;

        if ($this->repository->add($title, $location, $beginTime, $expireTime, $imgUri, $url, $index)) {
            /*$data = [
                'name' => $name,
                'location' => $location,
                'begin_time' => $beginTime,
                'expire_time' => $expireTime,
                'title' => $title,
                'img_uri' => $imgUri,
                'url' => $url,
                'index' => $index,
            ];
            return $this->data($data);*/
            return $this->success();
        }
        return $this->fail('添加错误');
    }

    public function update()
    {
        $this->validate($this->request, [
            'img' => 'mimes:jpeg,jpg,bmp,png',
        ], [
            'img.mimes' => '必须是图片',
        ]);

        $id = $this->request->input('id');
        $title = $this->request->input('title');
        $location = $this->request->input('location');
        $beginTime = $this->request->input('begin_time');
        $expireTime = $this->request->input('expire_time');
        $url = $this->request->input('url');
        $index = $this->request->input('index');

        if(!$this->locationRepository->getByLocation($location))
            return $this->fail('错误的location');

        $imgUri = null;
        if ($this->request->file('img'))
        {
            $imgPath = Storage::uploadNewsImage($this->request->file('img'));
            $imgUri = $imgPath;
        }

        if ($this->repository->update($id, $title, $location, $beginTime, $expireTime, $imgUri, $url, $index)){
            return $this->success();
        }
        else{
            return $this->fail('没有数据更新');
        }
    }


    //TEST
    public function testC()
    {
        $a = $this->repository->getByLocationWithinValidTime('home_slide', '2017-10-26 13:13:03');

        $data = [];
        $index = [];
        foreach ($a as $banner){
            $data[] = (array)$banner;
            $index[] = $banner->index;
        }
        array_multisort($index, SORT_DESC, $data);

    }
}