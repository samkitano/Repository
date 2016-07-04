# Repository pattern in Laravel 5.2

An implementation of [this](https://bosnadev.com/2015/03/07/using-repository-pattern-in-laravel-5/) awesome Blog article by [Bosnadev](https://bosnadev.com).

Based on [Bosnadev/Repositories](https://github.com/Bosnadev/Repositories) with some minor changes, mostly PSR-2 compliance.

You should, by all means, read the article and work it out for yourself, as the *Repository Pattern*
is a very important concept to properly implement a data access layer for any medium/large scale application.

## NOTE

As stated above, this package is a personal implementation of Bosniadev's for my projects and testings.

Thus, for the time being, you most definitely should use [that](https://github.com/Bosnadev/Repositories) package instead of this one,
and follow it's instructions.

## INSTALL

 ```bash
 composer require "samkitano/repository"
 ```

## Usage

```php
<?php namespace App\Repositories;

use Kitano\Repository\Contracts\RepositoryInterface;
use Kitano\Repository\Eloquent\Repository;

class UseRepository extends Repository {

    public function model() {
        return 'App\User';
    }
}
```


```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $primaryKey = 'user_id';

    protected $table = 'users';

    protected $casts = [
        "verified"       => 'boolean'
    ];
}
```


```php
<?php

namespace App\Http\Controllers;

use App\Repositories\UsersRepository as User;

class UsersController extends Controller {

    protected $user;

    public function __construct(User $user) {

        $this->user = $user;
    }

    public function index() {
        return response()->json($this->user->all());
    }
}
```

## Available Methods

The following methods are available:

##### Kitano\Repository\Contracts\RepositoryInterface

```php
public function all($columns = array('*'))
public function lists($value, $key = null)
public function paginate($perPage = 1, $columns = array('*'));
public function create(array $data)
public function update(array $data, $id, $attribute = "id")
public function delete($id)
public function find($id, $columns = array('*'))
public function findBy($field, $value, $columns = array('*'))
public function findAllBy($field, $value, $columns = array('*'))
public function findWhere($where, $columns = array('*'))
```

##### Kitano\Repository\Contracts\CriteriaInterface

```php
public function apply($model, Repository $repository)
```

### Example usage


Create a new user in repository:

```php
$this->user->create($input);
```

Update existing user:

```php
$this->user->update($input, $user_id);
```

Delete user:

```php
$this->user->delete($id);
```

Find user by user_id;

```php
$this->user->find($id);
```

you can also chose what columns to fetch:

```php
$this->user->find($id, ['name', 'email', 'created_at']);
```

Get a single row by a single column criteria.

```php
$this->user->findBy('email', $email);
```

Or you can get all rows by a single column criteria.
```php
$this->user->findAllBy('active', true);
```

Get all results by multiple fields

```php
$this->user->findWhere([
    'active' => true,
    ['created_at', '>', Carbon::yesterday()]
]);
```

## Criteria

```php
<?php

namespace App\Repositories\Criteria\Users;

use Carbon\Carbon;
use Kitano\Repository\Criteria\Criteria;
use Kitano\Repository\Contracts\RepositoryInterface as Repository;

class RegisteredToday extends Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $yesterday = Carbon::yesterday();
        $model     = $model->where('created_at', '>', $yesterday);

        return $model;
    }
}
```

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Criteria\Users\RegisteredToday;
use App\Repositories\UsersRepository as User;

class UsersController extends Controller {

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user) {

        $this->user = $user;
    }

    public function index() {
        $this->user->addCriteria(new RegisteredToday());
        return response()->json($this->user->all());
    }
}
```

## Credits

[Bosnadev/Repositories](https://github.com/Bosnadev/Repositories)

[This](https://github.com/prettus/l5-repository) great package by @andersao. [Here](https://github.com/anlutro/laravel-repository/) is another package I used as reference.

[This](http://shawnmc.cool/the-repository-pattern) article by Shawn McCool

[This](https://laracasts.com/lessons/repositories-simplified) Jeffrey Way's lesson from Laracasts
