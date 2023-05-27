<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->string("bin_iin");
            $table->string("card_brand")->nullable();
            $table->string("card_type")->nullable();
            $table->string("card_level")->nullable();
            $table->string("issuer_name_bank")->nullable();
            $table->string("issuer_bank_website")->nullable();
            $table->string("issuer_bank_phone")->nullable();
            $table->string("iso_country_name")->nullable();
            $table->string("iso_country_code")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bins');
    }
};
