<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255); // Title (string, required, max:255)
            $table->text('description'); // Description (text, required)
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open'); // Status (enum, default 'open')
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

