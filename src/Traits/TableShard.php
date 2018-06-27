<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/27
 * Time: 上午10:26
 */

namespace OkamiChen\TableShard\Traits;


trait TableShard {

    /**
     * 分表数量,建议为8的倍数
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

    /**
     * 按照指定的数字取余数
     * @return string
     */
    public function getShardTable(){
        $key    = $this->getShardKey();
        $value  = $this->$key;
        return $this->table.'_'. sprintf('%02d', $value % $this->getShardNum());
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