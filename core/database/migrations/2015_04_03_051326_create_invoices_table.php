<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('client_id');
            $table->string('number');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->integer('status');
            $table->float('discount');
            $table->text('terms');
            $table->text('notes');
            $table->string('currency');

            $table->engine = 'InnoDB';
            $table->unique('number');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
		Schema::drop('invoices');
	}

}
