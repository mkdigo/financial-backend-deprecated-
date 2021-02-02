<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubgroupsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('subgroups', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->unsignedBigInteger('group_id');
      $table->unsignedBigInteger('user_id');
      $table->timestamps();

      $table->foreign('group_id')->references('id')->on('groups');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('subgroups');
  }
}
