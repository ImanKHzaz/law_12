<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserCaseNumberToLawsuitsTable extends Migration
{
    public function up()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            $table->integer('user_case_number')->default(0)->after('next_case_number');
        });
    }

    public function down()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            $table->dropColumn('user_case_number');
        });
    }
}
