# Documentación: Comando `job:run`

## Introducción

El comando `job:run` permite ejecutar dinámicamente clases y métodos dentro de una aplicación Laravel. Además, registra cada trabajo en la base de datos, rastrea su estado y maneja errores con reintentos automáticos.

---

## Cómo funciona

1. **Definición de argumentos y opciones**:
   - `className`: El nombre completo de la clase (incluyendo el namespace).
   - `methodName`: El nombre del método que se ejecutará dentro de la clase.
   - `parameters`: Una lista opcional de parámetros para el método.
   - `--retries`: Número de intentos en caso de error (por defecto: 3).

2. **Registro en la tabla `jobs`**:
   - El comando registra el trabajo en la base de datos con información básica como la clase, el método, los parámetros y el estado inicial (`running`).

3. **Ejecución dinámica del método**:
   - Se valida que la clase y el método existan.
   - Se instancia la clase y se llama al método especificado utilizando `call_user_func_array`.

4. **Manejo de errores y reintentos**:
   - Si ocurre un error, se intenta ejecutar nuevamente hasta agotar el número máximo de reintentos configurados.
   - Se registra el error en los logs y en la base de datos.

5. **Estado final del trabajo**:
   - Si el trabajo se completa correctamente, su estado se actualiza a `completed`.
   - Si falla después de todos los intentos, su estado se actualiza a `failed`.

---

## Tabla `jobs`

El comando utiliza la tabla `jobs` para registrar y rastrear los trabajos. Su estructura incluye:

- **`id`**: Identificador único del trabajo.
- **`class_name`**: Nombre completo de la clase ejecutada.
- **`method_name`**: Método llamado dentro de la clase.
- **`parameters`**: Parámetros pasados al método, almacenados en formato JSON.
- **`status`**: Estado del trabajo (`running`, `completed`, `cancelled`, `failed`).
- **`retries`**: Número de intentos realizados.
- **`started_at`**: Marca de tiempo cuando se inició el trabajo.
- **`completed_at`**: Marca de tiempo cuando el trabajo se completó.
- **`error_message`**: Mensaje de error en caso de fallo.
- **`timestamps`**: Columnas de Laravel para `created_at` y `updated_at`.

---

## Uso del comando

# Uso del comando `job:run`

El comando se ejecuta desde la terminal y recibe los siguientes argumentos y opciones:

```bash
php artisan job:run {className} {methodName} {parameters?*} {--retries=3}

```
# Ejemplo 1: Ejecutar un trabajo básico
Clase:

```bash
namespace App\Jobs;

class SendNotificationJob
{
    public function send($userId, $message)
    {
        echo "Enviando notificación al usuario {$userId}: {$message}\n";
    }
}

```
#  Comando:
```bash
php artisan job:run "App\Jobs\SendNotificationJob" "process" "123" "Hola, este es un mensaje de prueba" --retries=3

```
Flujo:

La clase App\Jobs\SendNotificationJob se instancia.
El método send se ejecuta con los parámetros 123 y "Hola, este es un mensaje de prueba".
Si falla, el comando intentará reintentar hasta 3 veces.

#  Comando:Ejemplo 2: Registrar un trabajo en la base de datos
Clase:


```bash
namespace App\Jobs;

class GenerateReportJob
{
    public function process($reportId, $startDate, $endDate)
    {
        echo "Generando reporte {$reportId} desde {$startDate} hasta {$endDate}\n";
    }
}

```
```bash
php artisan job:run "App\Jobs\GenerateReportJob" "process" "42" "2024-01-01" "2024-12-31" --retries=5


```





