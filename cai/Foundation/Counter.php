<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

class Counter
{
    /**
     * 前缀组
     *
     * @var string
     */
    protected $prefixGroup;

    /**
     * @var \Redis
     */
    protected $store;

    public function __construct()
    {
        $this->store = \Cache::driver('redis')->getStore()->getRedis()->connection('default')->client();
    }

    /**
     * 增加计数
     *
     * @param $key
     * @param $field
     * @param int $num
     * @return int
     */
    public function incrBy($key, $field, $num = 1)
    {
        return $this->store->hIncrBy($this->prefix($key), $field, $num);
    }

    /**
     * 减小计数
     *
     * @param $key
     * @param $field
     * @param int $num
     * @return int
     */
    public function decrBy($key, $field, $num = 1)
    {
        // 保证最小值为0
        $lua = <<<'lua'
        if tonumber(redis.call('hget',KEYS[1], KEYS[2])) >= tonumber(KEYS[3]) then
            return redis.call('hincrby', KEYS[1], KEYS[2], -1 * KEYS[3])
        else
            return redis.call('hset', KEYS[1], KEYS[2], 0)
        end
lua;

        return $this->store->eval($lua, [$this->prefix($key), $field, $num], 3);
    }

    /**
     * 获取某些计数值
     *
     * @param $key
     * @param array ...$fields
     * @return array
     */
    public function mGet($key, ...$fields)
    {
        return $this->store->hMGet($this->prefix($key), $fields);
    }

    /**
     * 批量设置值
     *
     * @param $key
     * @param array ...$fields
     * @return bool
     */
    public function mSet($key, ...$fields)
    {
        return $this->store->hMset($this->prefix($key), $fields);
    }

    /**
     * 获取所有计数信息
     *
     * @param $key
     * @return array
     */
    public function getAll($key)
    {
        return $this->store->hGetAll($this->prefix($key));
    }

    protected function prefix($key)
    {
        return 'c_' . $this->prefixGroup . '_' . $key;
    }
}