<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/27
 * Time: 上午10:26
 */

namespace OkamiChen\TableShard\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait TableShard {

    protected $tableShard   = true;

    /**
     * 是否启用分表支持
     * @return boolean
     */
    public function isTableShard(){
        return $this->tableShard;
    }

    /**
     * 启用分表
     * @return boolean
     */
    public function enableTableShard(){
        $this->tableShard   = true;
        return $this->tableShard;
    }

    /**
     * 禁用分表
     */
    public function disableTableShard(){
        $this->tableShard   = false;
        return $this->tableShard;
    }

    /**
     * 分表个数
     * @return int
     */
    public function getShardNum(){
        return 8;
    }

    /**
     * 分表参考键
     * @return string
     */
    public function getShardKey(){
        return 'id';
    }

    /**
     * 分表规则
     */
    public function getShardTable($value=null){
        $key    = $this->getShardKey();
        $value  = $value ? $value : $this->$key;
        return $this->table.'_'. sprintf('%02d', $value % $this->getShardNum());
    }

    /**
     * 自定义模型查询
     *
     * @param  \OkamiChen\TableShard\Database\Query\Builder  $query
     * @return \OkamiChen\TableShard\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        $conn       = $query->getConnection();
        $grammar    = $query->getGrammar();
        $processor  = $query->getProcessor();
        $query      = $this->getConfigQuery();
        $query      = new $query($conn, $grammar, $processor);
        $builder    = $this->getConfigBuilder();
        return new $builder($query);
    }

    /**
     *
     * @return \OkamiChen\TableShard\Database\Query\Builder
     */
    protected function getConfigQuery(){
        $class      = \OkamiChen\TableShard\Database\Query\Builder::class;
        return config('table_shard.query', $class);
    }

    /**
     *
     * @return \OkamiChen\TableShard\Database\Eloquent\Builder
     */
    protected function getConfigBuilder(){
        $class      = \OkamiChen\TableShard\Database\Eloquent\Builder::class;
        return config('table_shard.builder', $class);
    }

    /**
     * 自定义一对一模型
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