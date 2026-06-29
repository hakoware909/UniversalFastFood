<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('CustomerID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Reference to users table
            $table->string('full_name'); // Combined name field
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('payment')->nullable(); // Payment method field
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
