<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('popularity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_hash');
            $table->integer('model_id');
            $table->string('model_type');
            $table->date('date');
            $table->timestamps();

            $table->index(['client_hash', 'model_id', 'model_type', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
}
