<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->string("first_phone");
            $table->string("second_phone");
            $table->string("last_phone");
            $table->string("address");
            $table->string("working_days_ar");
            $table->string("working_days_en");
            $table->string("working_hours_ar");
            $table->string("working_hours_en");
            $table->string("facebook");
            $table->string("twitter");
            $table->string("youtube");
            $table->string("instgram");
            $table->string("linked_in");
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
        Schema::dropIfExists('contacts');
    }
}
