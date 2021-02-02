<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('entries', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->date('date');
      $table->unsignedBigInteger('debit_id');
      $table->unsignedBigInteger('credit_id');
      $table->unsignedBigInteger('value');
      $table->longText('note')->nullable();
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('debit_id')->references('id')->on('accounts');
      $table->foreign('credit_id')->references('id')->on('accounts');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('entries');
  }
}
