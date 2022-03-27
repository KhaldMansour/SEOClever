<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('rate');
            $table->decimal('total')->default(0);
            $table->string('url');
            $table->integer('service_id')->unsigned()
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
            $table->integer('cart_id')->unsigned()
                ->references('id')
                ->on('carts')
                ->onDelete('cascade');
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
        Schema::dropIfExists('cart_items');
    }
}
