<?php

use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() : void
    {
        Schema::create('option_values', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Option::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('option_value_product', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(OptionValue::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down() : void
    {
        if(!app()->isProduction()) {
            Schema::dropIfExists('option_value_product');
            Schema::dropIfExists('option_values');
        }
    }
};
