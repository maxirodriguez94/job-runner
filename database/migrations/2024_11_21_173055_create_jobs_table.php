<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('class_name'); 
            $table->string('method_name'); 
            $table->json('parameters')->nullable();
            $table->enum('status', ['running', 'completed', 'cancelled','failed'])->default('running'); // Columna 'status'
            $table->integer('retries')->default(0); 
            $table->timestamp('started_at')->nullable(); 
            $table->timestamp('completed_at')->nullable(); 
            $table->string('error_message')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}

