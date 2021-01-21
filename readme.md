# Sistema de envíos en Laravel 5.7 y Vue.js 2
Este proyecto se creo con el afan de aprender un framework de javascript de codigo abierto llamado vue.js
Vue nos permite la construccion de interfaces de usuario y aplicaciones de una sola pagina.

Para el backend escogi Laravel 5.7 debido a que tiene una interaccion directa con el mismo Vue.

# Comandos de instalacion composer y creacion de proyecto en Laravel 5.7
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

php composer-setup.php --version=1.8.0

php composer.phar

composer create-project laravel/laravel mi-proyecto-laravel 5.7.*
```

# Comandos necesarios 
```
--- Permite instalar dependencias.
npm install 

--- Permite ejecutar y procesar todo el proyecto de forma automatica siempre que guardemos.
npm run watch

--- Permite levantar el proyecto en el servidor http://127.0.0.1:8000.
php  artisan serve

--- Permite migrar las tablas de la base de datos.
php artisan migrate 

--- Permite la creación de la tabla.
php artisan make:migration "Aquí el nombre de la tabla"  
Ejemplo:
php artisan make:migration create_ejemplo_table

--- Permite la creación del modelo.
php artisan make:model "Aquí el nombre de la tabla"
Ejemplo:
php artisan make:model Ejemplo

--- Permite crear el controlador.
php artisan make:controller "Aquí el nombre del controlador"
Ejemplo:
php artisan make:controller EjemploController

--- Permite crear la carpeta con los archivos de autenticación.
php artisan make:auth 

--- Permite crear el archivo para filtrar peticiones HTTP.
php artisan make:middleware "Aquí el nombre del Middleware" 
Ejemplo:
php artisan make:middleware Administrador
```
Cabe recalcar que al usar --resource podemos crear todos los métodos dentro del controlador.
```
php artisan make:controller EjemploController --resource 
```

Si tenemos problemas con los datos o alguna dependencia es recomendable usar los siguientes comandos.
Observaciones: Se borrarán los datos ingresados en al base de datos.
```
php artisan config:clear

php artisan config:cache
```


