<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 13:25
 */
namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class BannerRepository extends Repository
{
    protected $_table = 'banner';
    protected $_connection = 'system';

    public function getAll()
    {
        return $this->table()->get();
    }

    public function getByLocation($location)
    {
        return $this->table()->where('location', $location)->get();
    }

    public function getByLocationWithinValidTime($location, $time)
    {
        if(!$time) $time = date('Y-m-d H:i:s');
        return $this->table()
            ->where('location', $location)
            ->where('begin_time', '<=', $time)
            ->where('expire_time', '>=', $time)
            ->get();
    }

    public function add($title, $location, $beginTime, $expireTime, $imgUri, $url, $index)
    {
        return $this->table()->insertGetId([
            'title' => $title,
            'location' => $location,
            'begin_time' => $beginTime,
            'expire_time' => $expireTime,
            'img_uri' => $imgUri,
            'url' => $url,
            'index' => $index,
        ]);
    }

    public function update($id, $title, $location, $beginTime, $expireTime, $imgUri, $url, $index)
    {
        if ($imgUri){
            return $this->table()->where('id', $id)->update([
                'title' => $title,
                'location' => $location,
                'begin_time' => $beginTime,
                'expire_time' => $expireTime,
                'img_uri' => $imgUri,
                'url' => $url,
                'index' => $index,
            ]);
        }
        else{
            return $this->table()->where('id', $id)->update([
                'title' => $title,
                'location' => $location,
                'begin_time' => $beginTime,
                'expire_time' => $expireTime,
                'url' => $url,
                'index' => $index,
            ]);
        }

    }

}