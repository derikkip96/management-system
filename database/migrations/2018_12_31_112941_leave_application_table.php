<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LeaveApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('leave_application', function (Blueprint $table) {
          $table->increments('id');
          $table->string('type');
          $table->string('startDate');
          $table->string('endDate');
          $table->integer('reliever');
          $table->string('leave_days');
          $table->string('releiver_approval')->default('pending');
          $table->string('HOD')->default('pending');
          $table->string('HR')->default('pending');
          $table->string('MD')->default('pending');
          $table->integer('user_id')->unsigned();
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->softDeletes();
          $table->timestamps();

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
