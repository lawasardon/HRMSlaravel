<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('id_number')->nullable();
            $table->date('date_filed');
            $table->decimal('amount', 10, 2);
            $table->decimal('interest', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('terms_of_loan');
            $table->decimal('deduction_per_salary', 10, 2);
            $table->string('reason_of_loan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('remarks', ['pending', 'paid'])->default('pending');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
            $table->foreign('id_number')->references('id_number')->on('employee')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan');
    }
}
