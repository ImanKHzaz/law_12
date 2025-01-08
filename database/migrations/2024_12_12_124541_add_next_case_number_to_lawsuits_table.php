<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNextCaseNumberToLawsuitsTable extends Migration
{
    public function up()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            $table->integer('next_case_number')->default(0)->after('lawsuit_number');
        });
    }

    public function down()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            $table->dropColumn('next_case_number');
        });
    }
}
