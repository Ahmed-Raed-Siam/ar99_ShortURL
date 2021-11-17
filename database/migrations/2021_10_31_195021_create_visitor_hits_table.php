<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorHitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_hits', function (Blueprint $table) {
            $table->id();
            $table->longText('visitor_id');
            $table->foreignId('visited_page_id')->constrained('links', 'id')->cascadeOnDelete();
            $table->integer('hits')->default(0);
            $table->string('ip');
            $table->string('user_agent');
            $table->dateTime('visited_at')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_hits');
    }
}
