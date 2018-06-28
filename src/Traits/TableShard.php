<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/27
 * Time: 上午10:26
 */

namespace OkamiChen\TableShard\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use OkamiChen\TableShard\Database\Eloquent\Builder;
use OkamiChen\TableShard\Database\Query\Builder as Query;

trait TableShard {


    protected $tableShard   = true;

    public function isTableShard(){
        return $this->tableShard;
    }

    /**
     * 是否支持分片
     * @return boolean
     */
    public function enableTableShard(){
        $this->tableShard   = true;
        return $this->tableShard;
    }

    /**
     * 禁用分片
     */
    public function disableTableShard(){
        $this->tableShard   = false;
        return $this->tableShard;
    }

    /**
     * 分表数量,最好为8的倍数
     * @return int
     */
    public function getShardNum(){
        return 8;
    }

    /**
     * 根据那个表取余
     * @return string
     */
    public function getShardKey(){
        return 'id';
    }

    protected $step = 1;
    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        }
        $query  = $this->newQuery();
        return $query->$method(...$parameters);
    }

    /**
     * 按照指定的数字取余数
     */
    public function getShardTable($value=null){
        $key    = $this->getShardKey();
        $value  = $value ? $value : $this->$key;
        return $this->table.'_'. sprintf('%02d', $value % $this->getShardNum());
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \OkamiChen\TableShard\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        $conn       = $query->getConnection();
        $grammar    = $query->getGrammar();
        $processor  = $query->getProcessor();
        $builder    = new Query($conn, $grammar, $processor);
        return new Builder($builder);
    }
    /**
     * Define a one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function haveOne($related, $foreignKey = null, $localKey = null)
    {
        $model          = new $related();
        $tableName      = $this->getShardTable();
        $model->setTable($tableName);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return new HasOne($model->newQuery(), $this, $foreignKey, $localKey);
        //return new HasOne($model->newQuery(), $this, $model->getTable().'.'.$foreignKey, $localKey);
    }
}