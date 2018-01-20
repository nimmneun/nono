# nono
[![Build Status](https://travis-ci.org/nimmneun/nono.svg?branch=master)](https://travis-ci.org/nimmneun/nono)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nimmneun/nono/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nimmneun/nono/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nimmneun/nono/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nimmneun/nono/?branch=master)

```php
<?php require_once '../vendor/autoload.php';

// instantiate new app
$app = new \Nono\Application();

// add route with controller::method action
$app->post('/profile', 'ProfileController::create');

// or using a closure as action
$app->get('/{name}?', function ($request, $name = 'World') {
    echo "Hello {$name}!";
});

// run app and generate output
$app->respond();
```
