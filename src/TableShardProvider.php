<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/27
 * Time: 上午10:26
 */

namespace OkamiChen\TableShard;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;

class TableShardProvider extends EventServiceProvider {

    public function register(){

    }

    public function boot(){

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'okami-chen-table-shard');
        }

        Event::listen('eloquent.retrieved: *', function($name, $event){
            $model  = array_shift($event);
            if(method_exists($model, 'getShardTable')){
                $table  = $model->getShardTable();
                $model->setTable($table);
            }
            return $model;
        });

        Event::listen('eloquent.saving: *', function($name, $event){
            $model  = array_shift($event);
            if(method_exists($model, 'getShardTable')){
                $table  = $model->getShardTable();
                $model->setTable($table);
            }
            return $model;
        });
    }
}