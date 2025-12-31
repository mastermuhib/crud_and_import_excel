<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableAdministrators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('administrators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_role')->nullable();
            $table->string('admin_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('profile')->nullable();
            $table->string('password')->nullable();
            $table->string('confirm_password')->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->text('address')->nullable();
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
        Schema::dropIfExists('administrators');
    }
}
