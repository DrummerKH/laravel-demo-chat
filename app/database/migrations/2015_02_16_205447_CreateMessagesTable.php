<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
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
        \Illuminate\Support\Facades\Schema::create('messages', function(\Illuminate\Database\Schema\Blueprint $table)
        {
            $table->increments('id');
            $table->integer('from_user_id');
            $table->integer('to_user_id');
            $table->mediumText('message');
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