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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 250)->nullable();
            $table->string('email', 250)->unique();
            $table->longText('avatar')->nullable();
            $table->string('password',250)->nullable();
            $table->integer('role')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token_verify',250)->nullable();
            $table->boolean('is_active')->default(0);
            $table->integer('count_system_mail_daily')->default(0);
            $table->string('facebook_id',250)->nullable();
            $table->string('google_id',250)->nullable();
            $table->string('github_id',250)->nullable();
            $table->string('gitlab_id',250)->nullable();
            $table->string('twitter_id',250)->nullable();
            $table->string('linkedin_id',250)->nullable();
            $table->timestamps();
//            $table->uuid('foreign_id');
//            $table->foreign('foreign_id')->references('id')->on('foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
