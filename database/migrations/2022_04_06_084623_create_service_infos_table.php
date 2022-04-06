<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_infos', function (Blueprint $table) {
            $table->id();
            $table->string('start_date');
            $table->string('approximate_time');
            $table->string('quality');
            $table->string('speed');
            $table->string('guarantee');
            $table->integer('rating');
            $table->text('description')->nullable();
            $table->integer('service_id')->unsigned()
                ->references('id')
                ->on('services')
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
        Schema::dropIfExists('service_infos');
    }
}
