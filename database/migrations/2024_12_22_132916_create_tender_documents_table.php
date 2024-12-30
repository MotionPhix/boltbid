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
    Schema::create('tender_documents', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('path');
      $table->string('type');
      $table->text('content')->nullable();
      $table->timestamp('analyzed_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tender_documents');
  }
};
