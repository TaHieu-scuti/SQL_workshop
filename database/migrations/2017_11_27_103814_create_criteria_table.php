<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('CriteriaID');
           $table->string('Name');
           $table->string('CanonicalName');
           $table->integer('ParentID');
           $table->string('CountryCode');
           $table->string('TargetType');
           $table->string('Status');
           $table->string('JapaneseName')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('criteria');
    }
}
