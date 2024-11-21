# Guía para configurar el proyecto 

## 1. Clonar el repositorio
Primero, debes clonar el repositorio de GitHub utilizando el siguiente comando en tu terminal:

---

        git clone https://github.com/maxirodriguez94/job-runner.git

2. Ejecutar migraciones
Luego de clonar el repositorio, navega hasta el directorio del proyecto y ejecuta las migraciones para crear las tablas de la base de datos:

        php artisan migrate
        

---

2. Ejecutar Seeder
Para poblar las tablas con datos de ejemplo, ejecuta el siguiente comando para ejecutar los Seeders:

        php artisan db:seed

---
3. Instalar dependencias
Asegúrate de tener todas las dependencias necesarias instaladas. Puedes hacer esto ejecutando el siguiente comando:

        composer install

4.Configurar la base de datos SQLite
En el archivo .env, asegúrate de configurar la conexión de la base de datos para utilizar SQLite. Establece la siguiente línea:

        DB_CONNECTION=sqlite

5. Ejecutar el servidor
Finalmente, ejecuta el servidor local para acceder a la aplicación en tu navegador:

        php artisan serve

