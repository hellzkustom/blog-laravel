<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCharcterIdToStreetFighterVs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('street_fighter_vs', function (Blueprint $table) {
          $table->string('character');  //カラム追加  //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('street_fighter_vs', function (Blueprint $table) {
           $table->dropColumn('character');  //
        });
    }
}
