<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('translations', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('image_name');
      $table->text('original_text');
      $table->text('translated_text');
      $table->string('source_lang', 7);
      $table->string('target_lang', 7);
      $table->boolean('public')->default(false);
      $table->timestamps();
      $table->unsignedBigInteger('user_id');
      $table
        ->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('translations');
  }
};
