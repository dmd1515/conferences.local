<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->nullable(); // Add total_price column
        });
    }

    public function down()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('total_price'); // Drop the column if rolling back
        });
    }

};
