<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/27
 * Time: 上午10:26
 */

namespace OkamiChen\TableShard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class TableShardProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'okami-chen-table-shard');
        }
    }
}