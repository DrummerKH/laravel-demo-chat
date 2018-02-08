<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @access   public
     * @return   void
     */
    public function up()
    {
        // Create the users table.
        //
        Schema::create('users', function(\Illuminate\Database\Schema\Blueprint $table)
        {
            $table->increments('id');
            $table->string('role')->default('user');
            $table->string('email');
            $table->string('password');
            $table->string('username');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @access   public
     * @return   void
     */
    public function down()
    {
        Schema::drop('users');
    }
}