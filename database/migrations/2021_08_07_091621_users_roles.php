<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_roles',function (Blueprint $table){
            $table->increments('id')->unsigned();
            $table->integer('user_id',unsigned:true);
            $table->integer('role_id',unsigned:true);

            $table->foreign('user_id')->references("id")->on('users')->
                onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references("id")->on("roles")->
                onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_roles');
    }
}
