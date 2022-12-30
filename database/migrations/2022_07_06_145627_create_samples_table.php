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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->char('kata_id', 25)->unique();
            $table->foreign('kata_id')
                ->references('id')
                ->on('katas')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->jsonb('args_list');
            $table->jsonb('expected_list');
            $table->jsonb('eval_list')->nullable();
            $table->string('status')->nullable();
            $table->jsonb('function_names')->nullable();
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
        Schema::dropIfExists('samples');
    }
};
