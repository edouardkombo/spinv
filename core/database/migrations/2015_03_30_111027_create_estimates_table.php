<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estimates', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('client_id');
            $table->string('estimate_no')->unique();
            $table->date('estimate_date');
            $table->string('currency');
            $table->text('notes');
            $table->text('terms');

            $table->engine = 'InnoDB';
			$table->timestamps();
		});

        Schema::table('estimates', function($table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('estimates');
	}

}
