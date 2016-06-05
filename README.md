# nono
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nimmneun/nono/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nimmneun/nono/?branch=master)

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

$app = new Nono\Application(
    dirname(__DIR__),
    new Nono\Request(),
    $router
);

// Send output to browser.
$app->respond();
```
