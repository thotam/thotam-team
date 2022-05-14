<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrNhomSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_nhom_san_phams', function (Blueprint $table) {
            $table->string('hr_key', 20);
            $table->foreign('hr_key')->references('key')->on('hrs')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('nhom_san_pham_id')->unsigned();
            $table->primary(['hr_key', 'nhom_san_pham_id']);
            $table->foreign('nhom_san_pham_id')->references('id')->on('nhom_san_phams')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_nhom_san_phams');
    }
}
