<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Job;

class RunJob extends Command
{
    protected $signature = 'job:run {className} {methodName} {parameters?*} {--retries=3}';
    protected $description = 'Ejecuta una clase y método dinámicamente';

    public function handle()
    {
        $className = trim($this->argument('className'));
        $methodName = trim($this->argument('methodName'));
        $parameters = $this->argument('parameters') ?? [];
        $retryAttempts = (int) $this->option('retries');


        Log::info("Iniciando trabajo: {$className}::{$methodName}", ['parameters' => $parameters]);

        $job = Job::create([
            'class_name' => $className,
            'method_name' => $methodName,
            'parameters' => json_encode($parameters),
            'status' => 'running',
            'started_at' => now(),
        ]);
        dd($job);
        $attempt = 0;
        $success = false;

        while ($attempt < $retryAttempts) {
            $attempt++;
            try {
          
                Log::info("Intento {$attempt} para ejecutar: {$className}::{$methodName}");

         
                if (!class_exists($className)) {
                    throw new \Exception("Clase no encontrada: {$className}");
                }

   
                if (!method_exists($className, $methodName)) {
                    throw new \Exception("Método no encontrado: {$methodName}");
                }

           
                $instance = new $className();
                call_user_func_array([$instance, $methodName], $parameters);

            
                $job->update([
                    'status' => 'completed',
                    'retries' => $attempt,
                    'completed_at' => now(),
                ]);

                // Log de éxito
                Log::info("Trabajo completado correctamente: {$className}::{$methodName} (Intento {$attempt})");
                $this->info("Trabajo ejecutado correctamente: {$className}::{$methodName}");
                $success = true;
                break; // Salir del bucle si el trabajo se ejecutó con éxito
            } catch (\Exception $e) {
                // Actualizar estado del trabajo en caso de error
                $job->update([
                    'retries' => $attempt,
                    'error_message' => $e->getMessage(),
                ]);

                // Log de error
                Log::error("Error en intento {$attempt} para {$className}::{$methodName}: {$e->getMessage()}");

                if ($attempt >= $retryAttempts) {
                    // Actualizar estado a "failed" si se alcanzan todos los intentos
                    $job->update(['status' => 'failed']);
                    Log::error("Trabajo fallido después de {$retryAttempts} intentos: {$className}::{$methodName}");
                } else {
                    // Esperar antes del próximo intento
                    Log::info("Esperando 2 segundos antes del próximo intento...");
                    sleep(2);
                }
            }
        }

        // Log de finalización del trabajo
        if (!$success) {
            Log::warning("El trabajo {$className}::{$methodName} no pudo completarse después de {$retryAttempts} intentos.");
        } else {
            Log::info("El trabajo {$className}::{$methodName} se completó exitosamente en {$attempt} intentos.");
        }

        return $success ? 0 : 1;
    }
}
