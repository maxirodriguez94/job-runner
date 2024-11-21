<?php

use Symfony\Component\Process\Process;

if ( !function_exists( 'runBackgroundJob' ) ) {
    /**
    *
    * @param string $className
    * @param string $methodName
    * @param array $parameters
    * @param int $retryAttempts
    * @return void
    */

    function runBackgroundJob( string $className, string $methodName, array $parameters = [], int $retryAttempts = 3 ): void {
        $command = PHP_BINARY . ' ' . base_path( 'background_job_runner.php' );
        $input = json_encode( compact( 'className', 'methodName', 'parameters', 'retryAttempts' ) );

        if ( strncasecmp( PHP_OS, 'WIN', 3 ) === 0 ) {

            $process = new Process( [ 'cmd', '/C', "echo {$input} | {$command}" ] );
        } else {

            $process = new Process( [ "echo '{$input}' | {$command} > /dev/null 2>&1 &" ] );
        }

        $process->start();
    }
}
