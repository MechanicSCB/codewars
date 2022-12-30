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
        Schema::create('katas', function (Blueprint $table) {
            $table->char('id', 25)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('published_at')->nullable();
            $table->string('approved_at')->nullable();
            $table->boolean('verified')->nullable();
            $table->string('url')->nullable();
            $table->unsignedTinyInteger('rank')->default(0)->index();
            $table->string('created_at_orig')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->longText('description');
            $table->unsignedInteger('total_attempts')->default(0);
            $table->unsignedInteger('total_completed')->default(0);
            $table->unsignedInteger('total_stars')->default(0);
            $table->integer('vote_score')->default(0);
            $table->boolean('contributors_wanted')->default(0);
            $table->unsignedInteger('unresolved_issues')->default(0);
            $table->unsignedInteger('unresolved_suggestions')->default(0);
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
        Schema::dropIfExists('katas');
    }
};
