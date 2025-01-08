<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lawsuit_id')->unique();
            $table->decimal('claim_value', 10, 2);
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('amount_remaining', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            // التأكيد على الربط مع جدول القضايا
            $table->foreign('lawsuit_id')->references('id')->on('lawsuits')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_records');
    }
}
