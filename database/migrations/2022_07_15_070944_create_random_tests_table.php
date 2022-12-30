<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('random_tests', function (Blueprint $table) {
            $table->id();
            $table->string('kata_id')->unique();
            $table->foreign('kata_id')
                ->references('id')
                ->on('katas')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->jsonb('scheme');
            $table->text('code')->nullable();
            $table->boolean('is_function')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('random_tests');
    }
};
