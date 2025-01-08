<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourtRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('court_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lawsuit_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->integer('record_number')->default(0);
            $table->enum('court_name', ['دمشق', 'ببيلا', 'جرمانا', 'داريا']);
            $table->integer('court_number')->default(1);
            $table->enum('case_status', ['انتظار', 'قيد الدراسة', 'تم التسجيل', 'تم الفصل']);
            $table->string('decision_number')->nullable();
            $table->string('base_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // إضافة المفاتيح الخارجية
            $table->foreign('lawsuit_id')->references('id')->on('lawsuits')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('court_records');
    }
}
