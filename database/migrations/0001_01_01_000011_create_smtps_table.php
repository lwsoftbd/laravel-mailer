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
        Schema::create('smtps', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mailer')->nullable()->default('smtp');
            $table->string('hostDomain')->nullable()->default('127.0.0.1');
            $table->string('port')->nullable()->default('1025');
            $table->string('from_name')->nullable();
            $table->string('email_address')->nullable()->default('noreply@example.com');
            $table->string('password')->nullable();
            $table->enum('encryption', ['ssl', 'tls'])->nullable();
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp');
    }
};
