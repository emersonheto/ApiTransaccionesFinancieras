# API de Gestión de Transacciones Financieras

Este proyecto es una API REST construida con Laravel para gestionar cuentas y procesar transacciones financieras. Soporta múltiples tipos de transacciones (depósitos, retiros, transferencias), manejo de comisiones y reglas de saldo mínimo.

## Requisitos

- PHP 7.4 o superior
- Composer
- MySQL (o cualquier base de datos soportada por Laravel)
- Git
- Node.js (opcional, si necesitas compilar assets con Laravel Mix)

## Instalación

Sigue estos pasos para levantar el proyecto en tu máquina local.

### Clonar el Repositorio
~~~bash  
git clone https://github.com/emersonheto/ApiTransaccionesFinancieras.git

cd ApiTransaccionesFinancieras
composer install
cp .env.example .env

~~~


### Modifica el archivo .env para agregar tus credenciales de base de datos:
~~~bash 
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_la_base_de_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
~~~
### Generar la Key de la Aplicación
~~~bash 
php artisan key:generate
~~~
### Migrar la Base de Datos
Ejecuta las migraciones para crear las tablas necesarias:

~~~bash 
php artisan migrate
~~~
### Seed
~~~bash 
Ejecuta para crear las cuentas iniciales

php artisan db:seed --class=CuentaSeeder
~~~
### Ejecutar el Servidor
Inicia el servidor local de Laravel:
~~~bash 
php artisan serve
~~~

## Importar a POSTMAN
Descargar el archivo json apiRestTransacciones.postman_collection.json y importarlo en POSTMAN para realizar las pruebas
[apiRestTransacciones.postman_collection.json](https://github.com/user-attachments/files/16984048/apiRestTransacciones.postman_collection.json)

![image](https://github.com/user-attachments/assets/f2188576-1543-4dcb-a5d4-beab7b1f82fd)

![image](https://github.com/user-attachments/assets/d5f08726-263e-47dc-aa64-47beca4853dd)



### Endpoints Disponibles
1. Listar Cuentas
Método: GET
Ruta: /cuentas
2. Procesar Depósitos
Método: POST
Ruta: /cuentas/{id}/depositar
3. Procesar Retiros
Método: POST
Ruta: /cuentas/{id}/retirar
4. Procesar Transferencias
Método: POST
Ruta: /cuentas/{id}/transferir
5. Ver Detalle de Cuenta
Método: GET
Ruta: /cuentas/{id}
