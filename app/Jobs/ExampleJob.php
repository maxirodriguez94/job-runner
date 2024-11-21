<?php
namespace App\Jobs;

class ExampleJob
{
    public function process($param1, $param2)
    {
        
        sleep(5);
        echo "Trabajo procesado con $param1 y $param2\n";
    }
}
