<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(User::TABLE_NAME, function (Blueprint $table) {
            $table->string(User::MICROSOFT_ID)->nullable();
            $table->text(User::ACCESS_TOKEN)->nullable();
            $table->text(User::REFRESH_TOKEN)->nullable();
            $table->timestamp(User::TOKEN_EXPIRES_IN)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(User::TABLE_NAME, function (Blueprint $table) {
            //
        });
    }
};
