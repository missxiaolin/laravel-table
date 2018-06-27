# laravel-table
Laravel Table Sharding

##例子

```php
use Illuminate\Database\Eloquent\Model;

use OkamiChen\TableShard\Traits\TableShard;


class User extends Model {
    
    use TableShard;

}
```