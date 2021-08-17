<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAddCollumns extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('active')->default(1)->after('is_admin');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('active');
    });
  }
}
