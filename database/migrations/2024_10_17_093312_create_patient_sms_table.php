<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('patient_sms', function (Blueprint $table) {
        $table->increments('id'); 
        $table->string('from_number'); 
        $table->string('to_number'); 
        $table->enum('direction', ['inbound', 'outbound']); 
        $table->text('message_body'); 
        $table->unsignedInteger('user_id')->nullable(); 
        $table->integer('patient_id'); 
        $table->timestamps(); 

        $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('CASCADE');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_sms');
    }
}