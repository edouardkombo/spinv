<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estimate_items', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('estimate_id');
            $table->unsignedInteger('product_id');
            $table->float('quantity');
            $table->double('column', 15, 2);
            $table->integer('tax_id')->nullable()->unsigned();

            $table->engine = 'InnoDB';
            $table->foreign('estimate_id')->references('id')->on('estimates')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('tax_settings')->onDelete('cascade');

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
		Schema::drop('estimate_items');
	}

}
