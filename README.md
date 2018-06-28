# laravel-table
Laravel Table Sharding

## 创建
```php
use Illuminate\Database\Eloquent\Model;

use OkamiChen\TableShard\Traits\TableShard;


class User extends Model {
    
    use TableShard;

}
```
## 查询
```php
User::where(['user_id'=>18])->first();
```