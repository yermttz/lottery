<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entires', function (Blueprint $table) {
            $table->id();
            $table->biginteger('ticketid');
            $table->biginteger('customerid');
            $table->integer('entires');
            $table->integer('fractions');
            $table->string('action'); // add - del
            $table->boolean('active');
            $table->text('description')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('entires');
    }
};