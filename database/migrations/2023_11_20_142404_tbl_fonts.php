<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblFonts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_fonts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('letter', 45);
            $table->text('font', 200);
            $table->string('ascii', 45);
            $table->string('height', 45);
            $table->string('width', 45);
            $table->text('extra')->nullable();
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
        Schema::dropIfExists('tbl_fonts');
    }
}
