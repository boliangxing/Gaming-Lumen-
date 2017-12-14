<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Extend;

class IP
{
    protected $resolver;

    public function __construct(\Ip2Region $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getRegion($ip)
    {
        $regionData = $this->resolver->binarySearch($ip);

        if ($regionData['city_id'] == 0) {
            return [
                'city_id' => 0,
                'region' => '未知',
            ];
        }

        $region = $regionData['region'];
        $regionParts = explode('|', $region);

        return [
            'city_id' => $regionData['city_id'],
            'region' => $regionParts[2] . ', ' . $regionParts[3],
        ];
    }
}