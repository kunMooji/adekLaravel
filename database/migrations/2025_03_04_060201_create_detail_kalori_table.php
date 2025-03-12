<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailKaloriTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('detail_kalori')) {
            Schema::create('detail_kalori', function (Blueprint $table) {
                $table->id('id_detailkalori');
                $table->char('id_user', 5);
                $table->char('id_menu', 5);
                $table->date('tanggal');
                $table->integer('jumlah');
                $table->integer('total_kalori');
                $table->integer('total_minum')->nullable();
                $table->double('total_protein');
                $table->double('total_karbohidrat');
                $table->double('total_lemak');
                $table->double('total_gula');
                $table->timestamps();
    
                $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('id_user')->references('id_user')->on('data_pengguna')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }
    public function down()
    {
        Schema::dropIfExists('detail_kalori');
    }
}