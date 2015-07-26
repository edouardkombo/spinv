<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('payments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->date('payment_date');
            $table->float('amount');
            $table->text('notes');
            $table->unsignedInteger('method');

            $table->engine = 'InnoDB';
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('method')->references('id')->on('payment_methods')->onDelete('cascade');
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
		Schema::drop('payments');
	}

}
