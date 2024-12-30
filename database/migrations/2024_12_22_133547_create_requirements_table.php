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
    Schema::create('requirements', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->foreignId('tender_document_id')->constrained()->cascadeOnDelete();
      $table->text('description');
      $table->enum('type', ['document', 'certification', 'experience', 'financial', 'technical']);
      $table->enum('status', ['pending', 'fulfilled', 'missing'])->default('pending');
      $table->string('document_ref')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('requirements');
  }
};
