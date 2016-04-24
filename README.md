# nono
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nimmneun/nono/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nimmneun/nono/?branch=master)

```php
// www/index.php
require_once '../vendor/autoload.php';

// Define routes using a closure ...
$routes['/'] = function () {
    echo 'Welcome to the index page =)';
};

// ... or the Controller::method style. 
$routes['/hello/{name}'] = 'GreetingController::sayHello';

$app = new Nono\Application(
    new Nono\Request(),
    new Nono\Router($routes)
);

// Send output to browser.
$app->respond();
```

... Application should/could probably named Response.
