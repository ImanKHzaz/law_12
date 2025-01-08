<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lawsuit_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('client_number')->default(0);
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('id_front_image');
            $table->string('id_back_image');
            $table->string('power_of_attorney');
            $table->text('notes')->nullable();
            $table->timestamps();

            // إضافة المفاتيح الخارجية
            $table->foreign('lawsuit_id')->references('id')->on('lawsuits')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
