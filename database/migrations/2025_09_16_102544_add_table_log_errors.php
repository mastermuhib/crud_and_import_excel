<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableLogErrors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('log_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('controller')->nullable();
            $table->string('line_error')->nullable();
            $table->text('exception')->nullable();
            $table->integer('type')->nullable();
            $table->bigInteger('id_user')->nullable();
            $table->integer('is_view')->default(0);
            $table->integer('is_solved')->default(0); 
            $table->timestamp('failed_at')->nullable();           
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
        Schema::dropIfExists('log_errors');
    }
}
