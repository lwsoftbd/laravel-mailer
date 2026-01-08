<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('smtps', function (Blueprint $table) {
            $table->id();
            $table->string('mailer')->default('smtp');
            $table->string('host');
            $table->integer('port');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->nullable();
            $table->string('from_address');
            $table->string('from_name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('smtps');
    }
};
