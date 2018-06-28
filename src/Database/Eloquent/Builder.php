<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/6/28
 * Time: 上午10:01
 */

namespace OkamiChen\TableShard\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as Basic;

/**
 * Description of Builder
 * @date 2018-6-28 14:36:19
 * @author xl
 */
class Builder extends Basic
{

    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create(array $attributes = [])
    {
        return tap($this->newModelInstance($attributes), function ($instance) {
            if (method_exists($instance, 'isTableShard')) {
                if ($instance->isTableShard()) {
                    $instance->setTable($instance->getShardTable());
                }
            }
            $instance->save();
        });
    }


    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure $column
     * @param  mixed $operator
     * @param  mixed $value
     * @param  string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {

        if (method_exists($this->model, 'isTableShard')) {
            if ($this->model->isTableShard()) {
                $key = $this->model->getShardKey();
                $keys = array_keys($column);
                if (in_array($key, $keys)) {
                    $table = $this->model->getShardTable($column[$key]);
                    $this->model->setTable($table);
                    $this->query->from($table);
                }
            }
        }

        if ($column instanceof \Closure) {
            $column($query = $this->model->newModelQuery());
            $this->query->addNestedWhereQuery($query->getQuery(), $boolean);
        } else {
            $this->query->where(...func_get_args());
        }

        return $this;
    }
}