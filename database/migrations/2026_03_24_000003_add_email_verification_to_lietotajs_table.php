<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Palaiž migrāciju uz priekšu
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::table('lietotajs', function (Blueprint $table) {
            // E-pasta verifikācijas kodi un stāvoklis
            $table->string('email_verification_code')->nullable()->comment('Verifikācijas kods e-pastam');
            $table->timestamp('email_verification_code_expires_at')->nullable()->comment('Verifikācijas koda beigas');
            $table->timestamp('email_verified_at')->nullable()->comment('E-pasta verifikācijas laiks');
        });
    }

    /**
     * Atsaucina migrāciju atpakaļ
     * 
     * @return void
     */
    public function down(): void
    {
        Schema::table('lietotajs', function (Blueprint $table) {
            // Dzēš verifikācijas laukus, ja migrācija tiek atgriezta atpakaļ.
            $table->dropColumn(['email_verification_code', 'email_verification_code_expires_at', 'email_verified_at']);
        });
    }
};
