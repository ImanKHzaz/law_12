<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawsuitsTable extends Migration
{
    public function up()
    {
        Schema::create('lawsuits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('lawsuit_number')->default(0);
            $table->enum('lawsuit_type', ['Civil', 'Religious', 'Military', 'Criminal']);
            $table->string('lawsuit_subject');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lawsuits');
    }
}
