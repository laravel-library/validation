# Scene Validator
> Validator for Laravel framework with customized scenarios.

##### Simple install
```shell
$ composer require xgbnl/validator --dev-main
```

##### Create scene validator
```shell
$ php artisan make:validator UserValidator
```

##### Define validation scenarios
```php
// define
public function scenes():array
{
    return [
        'store' => 'name,phone,age,email',
        'update' =>['name','age'],
    ];
}

// call
public function store(UserValidator $userValidator):mixed
{
    $userValidator->withScene('store')->validatedForm(); // returns ResourceDTO
    
    $userValidator->withScene('update')->validatedRaw(); // returns array
    
    // do something...
}
```

##### Extend the custom validation rule method
```php
// define rule method
public function passwordRules():array
{
    return [
        'password'=> 'required|string|min:6',
        'password_confirm' =>'required|same:password'
    ];
}

// use
public function update(UserValidator $userValidator):mixed
{
    $userValidator->withScene('update')
        ->withRule('password')
        ->validateForm();
        
    // do something...
}

```