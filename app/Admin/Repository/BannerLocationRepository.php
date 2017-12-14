<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 13:25
 */
namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class BannerLocationRepository extends Repository
{
    protected $_table = 'banner_location';
    protected $_connection = 'system';

    const TYPE_CAROUSEL = 1; //轮播图

    public function getAll()
    {
        return $this->table()->get();
    }

    public function getByLocation($location)
    {
        return $this->table()->where('location', $location)->first();
    }

    public function add($name, $location, $type = self::TYPE_CAROUSEL)
    {
        if (!$type) $type = self::TYPE_CAROUSEL;
        return $this->table()->insertGetId([
            'name' => $name,
            'location' => $location,
            'type' => $type,
        ]);
    }

    public function update($id, $name, $location, $type = self::TYPE_CAROUSEL)
    {
        if (!$type) $type = self::TYPE_CAROUSEL;
        return $this->table()->where('id', $id)->update([
            'name' => $name,
            'location' => $location,
            'type' => $type,
        ]);
    }

    public function delete($id)
    {
        return parent::destroy($id);
    }
}