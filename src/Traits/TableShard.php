<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/27
 * Time: 上午10:26
 */

namespace OkamiChen\TableShard\Traits;


trait TableShard {

    public $shardNum    = 8;

    public $shardKey    = 'id';

    /**
     * 按照指定的数字取余数
     * @return string
     */
    public function getShardTable(){
        $key    = $this->shardKey;
        $value  = $this->$key;
        return $this->table.'_'. sprintf('%02d', $value % $this->shardNum);
    }

}