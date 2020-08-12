<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoroscopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscopes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('zodiac_sign');

            $table->unsignedTinyInteger('overall_score')->comment('整體運勢的評分');
            $table->text('overall_detail')->comment('整體運勢的說明');

            $table->unsignedTinyInteger('love_score')->comment('愛情運勢的評分');
            $table->text('love_detail')->comment('愛情運勢的說明');

            $table->unsignedTinyInteger('business_score')->comment('事業運勢的評分');
            $table->text('business_detail')->comment('事業運勢的說明');

            $table->unsignedTinyInteger('finance_score')->comment('財運運勢的評分');
            $table->text('finance_detail')->comment('財運運勢的說明');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horoscopes');
    }
}
