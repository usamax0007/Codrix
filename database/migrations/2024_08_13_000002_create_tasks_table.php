<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('summary');
            $table->text('description')->nullable();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['to_do', 'in_progress', 'testing', 'done'])->default('to_do');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
