<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableDpt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nkk')->nullable();
            $table->string('nik')->nullable();
            $table->string('name')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('birthday')->nullable();
            $table->string('status')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('members');
    }

   }
