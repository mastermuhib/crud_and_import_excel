<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableRoleMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('role_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id')->nullable();
            $table->integer('menu_id')->nullable(); 
            $table->integer('is_view')->default(1); 
            $table->integer('is_edit')->default(1);
            $table->integer('is_add')->default(1);
            $table->integer('is_delete')->default(1); 
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
        Schema::dropIfExists('role_menus');
    }
}
