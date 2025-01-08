<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUserSpecificNumberFromLawsuitsTable extends Migration
{
    public function up()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            if (Schema::hasColumn('lawsuits', 'user_specific_number')) {
                $table->dropColumn('user_specific_number');
            }
        });
    }

    public function down()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            $table->integer('user_specific_number')->default(0);
        });
    }
}
