# PSR-7 Authentication via Aura.Auth

Currently only works with Zend Expressive. If other PSR-7 based libraries / frameworks integrates two (the next PSR's according to my opinion)  [TemplateRendererInterface](https://github.com/zendframework/zend-expressive-template) and [RouterInterface](https://github.com/zendframework/zend-expressive-router), this library will work for everyone.

[Experimental Version].

Some @todo

* Do we need to use some sort of event handling system? The idea is to trigger events once a user is logged-in/logout from the system.
* How can the error messages handled nicely. Currently there is only one `error` variable.
* Write some tests once things get finalized.

```
composer require htk/expressive-auth
```

## Configuration

Currently supports only Aura.Di. You may want to add / configure for other dependency injection containers.

```php
class_alias('Aura\Di\ContainerConfig', 'Aura\Di\Config');

$configClasses = [    
    // Aura.Auth Configuration    
    Aura\Auth\_Config\Common::class,        
    Hkt\ExpressiveAuth\Di\AuthConfig::class,

    // Modify Route accordingly

    Hkt\ExpressiveAuth\Di\RouteConfig::class,

    // more config classes ...
];
```

In your `App\Di\Config` you may want to add the adapter you are going to use. Please see [Aura.Auth](https://github.com/auraphp/Aura.Auth#adapters).

Example below is for `Aura\Auth\Adapter\PdoAdapter`.

```php
$di->set('aura/auth:adapter', $di->lazyNew('Aura\Auth\Adapter\PdoAdapter'));
$di->params['Aura\Auth\Adapter\PdoAdapter']['pdo'] = $di->lazyGet('Aura\Sql\ExtendedPdo');
$di->params['Aura\Auth\Verifier\PasswordVerifier'] = array(
    'algo' => PASSWORD_DEFAULT,
);
```

## Routes

The current login and logout routes are `/login` and `/logout` respectively. You are not limited to modify to your needs.

## After logged in Redirect To

Once the user is logged in, it redirects to a route named `hkt/expressive-auth:home`. You can register a route or modify via the `Di/Config`. It looks something like

```php
// After logged in redirect user to route named `home`
$di->params['Hkt\ExpressiveAuth\Action\LoginAction']['redirectTo'] = 'home';

// After logged in redirect user to route named `login`
$di->params['Hkt\ExpressiveAuth\Action\LogoutAction']['redirectTo'] = 'login';
```

## Templates

The love for templates differ for users and projects. You can choose anything that supports [zend-expressive-template](https://github.com/zendframework/zend-expressive-template)

In your expressive `config/autoload/templates.global.php` file you can set the path to template as

```php
    'templates' => [        
        'paths' => [
            // ....
            'hkt/expressive-auth'   => ['templates/auth'],
        ]
    ]
```

For the sake of simplicity here is a `/templates/auth/login.phtml` template.

```php
<form method="post" enctype="multipart/form-data" action="<?= $this->url('hkt/expressive-auth:login'); ?>">
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-default">Login</button>
</form>
```
