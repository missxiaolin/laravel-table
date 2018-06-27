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
     * 按照指定的数字取余数
     * @param $id
     * @param int $shard
     * @return string
     */
    public function getShardTable($id, $shard=8){
        return $this->table.'_'. sprintf('%02d', $id % 10);
    }

}