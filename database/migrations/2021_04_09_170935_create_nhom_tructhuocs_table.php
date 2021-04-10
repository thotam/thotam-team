<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhomTructhuocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhom_tructhuocs', function (Blueprint $table) {
            $table->bigInteger('nhom_quan_ly_id')->unsigned();
            $table->foreign('nhom_quan_ly_id')->references('id')->on('nhoms')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('nhom_truc_thuoc_id')->unsigned();
            $table->primary(['nhom_quan_ly_id', 'nhom_truc_thuoc_id']);
            $table->foreign('nhom_truc_thuoc_id')->references('id')->on('nhoms')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhom_tructhuocs');
    }
}
