# Laravel 5.1 - DDD

### Passo a passo de como organizar uma aplicação default Laravel para arquitetura DDD

 * 1º Recorte todos arquivos de dentro da pasta **app**;
 * 2º Crie uma pasta chamada **Core**(dentro da pasta app) e cole todos os arquivos recortados;
 * 3º Renomeie todos os namespaces dos arquivos contidos dentro da pasta **Core** para App\Core\
 * 4º Na mesma pasta do passo anterior, na pasta **Providers**, abra o RouteServiceProvider e procure pelo trecho;
  "require app_path('Http/routes.php');", observe que o path não é mais esse, pois agora esse arquivo está dentro da pasta Core,
  então mude o path para **require app_path('Core/Http/routes.php');**
 * 5º Voltando a raiz do projeto, pasta **bootstrap** abra o arquivo app.php;

 ```php
 $app->singleton(
     Illuminate\Contracts\Http\Kernel::class,
     App\Http\Kernel::class
 );

 $app->singleton(
     Illuminate\Contracts\Console\Kernel::class,
     App\Console\Kernel::class
 );

 $app->singleton(
     Illuminate\Contracts\Debug\ExceptionHandler::class,
     App\Exceptions\Handler::class
 );
 ```
 Logo de cara, como fizemos no passo anterior, acrescente no namespace o **Core**.

 Veja o resultado:
  ```php
  $app->singleton(
      Illuminate\Contracts\Http\Kernel::class,
      App\Core\Http\Kernel::class
  );

  $app->singleton(
      Illuminate\Contracts\Console\Kernel::class,
      App\Core\Console\Kernel::class
  );

  $app->singleton(
      Illuminate\Contracts\Debug\ExceptionHandler::class,
      App\Core\Exceptions\Handler::class
  );
  ```

* 6º Na pasta config(raiz do projeto), abra o arquivo app.php para renomear alguns Service Providers;

  De ->
  ```php
  App\Providers\AppServiceProvider::class,
  App\Providers\AuthServiceProvider::class,
  App\Providers\EventServiceProvider::class,
  App\Providers\RouteServiceProvider::class,
  ```

  Para ->
  ```php
  App\Core\Providers\AppServiceProvider::class,
  App\Core\Providers\AuthServiceProvider::class,
  App\Core\CProviders\EventServiceProvider::class,
  App\Core\CProviders\RouteServiceProvider::class,
  ```
* 7º Voltando a pasta app, vamos primeiramente criar um Dominio Base. para isso, crie a estrutura a seguir;
  * Domains
      * User
          * Entities


 * 8º Crie uma Entidade chamada **User**, e não esqueça que o namespace deve seguir no seguinte formato: **App\Domains\User\Entities**, assim respeitando a hierarquia montada;
 * 9º Agora vamos criar uma Application, para isso, permanecendo dentro da pasta **app**, monte a seguinte estutura;
  * Applications
      * User
          * Http
              * Controllers
                  * UserController.php
              * routes.php
          * Providers
              * UserServiceProvider.php


 O Arquivo UserServiceProvider, é o principal arquivo de configuração dessa Application, nela você vai apontar o caminho dos **Controllers** bem como o arquivo de **rota**;

 Abaixo, veja o arquivo completo.

 ```php
 <?php

 namespace App\Applications\User\Providers;

 use Illuminate\Routing\Router;
 use Illuminate\Support\ServiceProvider;

 class UserServiceProvider extends ServiceProvider
 {
     protected $namespace = 'App\Applications\User\Http\Controllers';

     public function boot(Router $router)
     {
         $this->registerRoutes($this->app['router']);
     }

     /**
      * Register the service provider.
      *
      * @return void
      */
     public function register()
     {
     }

     protected function registerRoutes(Router $router)
     {
         $router->group(['namespace' => $this->namespace], function ($router) {
             require app_path('Applications/User/Http/routes.php');
         });
     }
 }
 ```
 Feito isso, não esqueça de registrar o ServiceProvider no arquivo config/app.php.

```php
App\Applications\User\Providers\UserServiceProvider::class,
```
 * 10º Rode o projeto com o artisan ou com um servidor externo e veja se está tudo funcionando corretamente.
 Caso queira uma iteração maior, crie uma rota e um controller e teste a aplicação.
