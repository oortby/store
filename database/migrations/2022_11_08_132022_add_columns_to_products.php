<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() : void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('on_home_page')
                ->comment('На главной странице')
                ->default(false);
            $table->integer('sorting')
                ->comment('Сортировка')
                ->default(999);

        });
    }


};
