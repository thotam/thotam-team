<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhoms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->bigInteger('chinhanh_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('kenh_kinh_doanh_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('nhom_san_pham_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('phan_loai_id')->unsigned()->nullable()->default(null);
            $table->integer('order')->nullable()->default(null);
            $table->boolean('active')->nullable()->default(null);
            $table->unsignedBigInteger('created_by')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->unsignedBigInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('phan_loai_id')->references('id')->on('phan_loai_nhoms')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('kenh_kinh_doanh_id')->references('id')->on('kenh_kinh_doanhs')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('chinhanh_id')->references('id')->on('chinhanhs')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('nhom_san_pham_id')->references('id')->on('nhom_san_phams')->onDelete('SET NULL')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhoms');
    }
}
