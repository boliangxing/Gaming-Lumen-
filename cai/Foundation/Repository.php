<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

use Illuminate\Pagination\Paginator;

/**
 * 数据仓库
 *
 * @package Cai\Foundation
 */
abstract class Repository
{
    /**
     * @var string 表名
     */
    protected $_table;

    /**
     * @var string 数据库名
     */
    protected $_connection;

    /**
     * @var integer 每页数据量
     */
    const PAGE_SIZE = 15;

    /**
     * 查询标记
     *
     * @param $alias
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table($alias = '')
    {
        if ($alias) {
            return \DB::connection($this->_connection)->table(sprintf('%s AS %s', $this->_table, $alias));
        }

        return \DB::connection($this->_connection)->table($this->_table);
    }

    /**
     * 返回分页cursor metadata
     *
     * @param Paginator $paginator
     * @param string $cursorProperty
     * @return array
     */
    protected function paginate(Paginator $paginator, $cursorProperty = 'id')
    {
        return [
            'has_more' => $paginator->hasMorePages(),
            'last' => $paginator->last()->$cursorProperty,
        ];
    }

    /**
     * 空分页数据
     *
     * @return array
     */
    protected function emptyPagination()
    {
        return [
            'items' => [],
            'cursor' => [
                'has_more' => false,
                'last' => 0
            ],
        ];
    }

    /**
     * Raw Expression
     *
     * @param $expression
     * @return \Illuminate\Database\Query\Expression
     */
    protected function raw($expression)
    {
        return \DB::connection($this->_connection)->raw($expression);
    }

    /**
     * 根据ID查找数据
     *
     * @param $id
     * @return mixed|static
     */
    public function getById($id)
    {
        return $this->table()->find($id);
    }

    /**
     * 删除数据
     *
     * @param $id
     * @return int
     */
    public function destroy($id)
    {
        return $this->table()->delete($id);
    }

    protected function isIntegrityException(\PDOException $e)
    {
        if ($e->getCode() == 2300 &&
            strpos($e->getMessage(), 'Integrity constraint violation') !== false) {

            return true;
        }

        return false;
    }


    /**
     * 判断数据是否唯一
     *
     */
    public function isValueUnique($key, $value)
    {
        return !($this->table()->where($key, $value)->first());
    }
}