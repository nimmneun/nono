# nono
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nimmneun/nono/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nimmneun/nono/?branch=master)

```php
// minimal www/index.php
require_once '../vendor/autoload.php';

$app = new Nono\Application();

$app->get('/{name}?', function ($request, $name = null) {
    echo "Welcome home " . $name;
});

$app->get('/hello/{name}', 'GreetingController::sayHello');


$app->respond();
```

```php
// www/index.php
require_once '../vendor/autoload.php';

// Predefine routes using a closure ...
$routes['GET']['/hello/{name}'] = function ($request, $name) {
    printf('Hello %s', $name);
};

// ... or the Controller::method style. 
$routes['GET']['/hello/{name}'] = 'GreetingController::sayHello';

// ... or add them via get, post, put, delete ...
$router = new Nono\Router($routes);
$router->get('/hello/{name}', 'SomeController::hello');
$router->post('/user', 'User::create');

$app = new Nono\Application();

// Send output to browser.
$app->respond();
```
