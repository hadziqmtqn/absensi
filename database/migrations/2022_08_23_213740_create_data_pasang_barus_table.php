<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPasangBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_pasang_barus', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 100);
            $table->string('nama_pelanggan');
            $table->string('no_hp', 100)->nullable();
            $table->string('alamat')->nullable();
            $table->string('acuan_lokasi')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['0','1','2','3'])->nullable()->default('0'); // 0 = Open, 1 = In Progress, 2 = Pending, 3 = Success
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
        Schema::dropIfExists('data_pasang_barus');
    }
}
