<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhomThanhviensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhom_thanhviens', function (Blueprint $table) {
            $table->string('hr_key', 10);
            $table->foreign('hr_key')->references('key')->on('hrs')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('nhom_id')->unsigned();
            $table->primary(['hr_key', 'nhom_id']);
            $table->foreign('nhom_id')->references('id')->on('nhoms')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhom_thanhviens');
    }
}
