# nono
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nimmneun/nono/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nimmneun/nono/?branch=master)

```php
// minimal www/index.php

require_once '../vendor/autoload.php';

$app = new Nono\Application();

// Define get/post/put ... routes using a closure ...
$app->get('/{name}?', function ($request, $name = null) { echo "Welcome home " . $name; });

// or using the Controller::method style.
$app->get('/hello/{name}', 'GreetingController::sayHello');

// Send outpout to browser / console.
$app->respond();
```
