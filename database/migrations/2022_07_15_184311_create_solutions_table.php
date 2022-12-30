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
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->char('kata_id', 25);
            $table->foreign('kata_id')
                ->references('id')
                ->on('katas')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->longText('body');
            $table->foreignId('lang_id')->constrained()->onUpdate('cascade');
            $table->boolean('is_control')->default(0);
            $table->enum('status', ['sample_passed','sample_semi_passed','sample_failed', 'sample_sum_equals_zero'])->nullable();
            $table->unsignedInteger('variations')->default(0);
            $table->unsignedInteger('best_practice')->default(0);
            $table->unsignedInteger('clever')->default(0);
            $table->unsignedInteger('comments')->default(0);
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
        Schema::dropIfExists('solutions');
    }
};
