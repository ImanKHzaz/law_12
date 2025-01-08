<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLawsuitNumberFromLawsuitsTable extends Migration
{
    public function up()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            if (Schema::hasColumn('lawsuits', 'lawsuit_number')) {
                $table->dropColumn('lawsuit_number');
            }
        });
    }

    public function down()
    {
        Schema::table('lawsuits', function (Blueprint $table) {
            $table->integer('lawsuit_number')->default(0);
        });
    }
}
