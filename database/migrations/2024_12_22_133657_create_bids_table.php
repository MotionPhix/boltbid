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
    Schema::create('bids', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
      $table->foreignId('user_id')->constrained();
      $table->string('title');
      $table->text('executive_summary');
      $table->decimal('price', 15, 2);
      $table->date('submission_date');
      $table->enum('status', ['draft', 'review', 'submitted', 'accepted', 'rejected'])
        ->default('draft');
      $table->timestamps();
    });

    Schema::create('bid_sections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bid_id')->constrained()->cascadeOnDelete();
      $table->string('title');
      $table->text('content');
      $table->integer('order');
      $table->enum('status', ['draft', 'review', 'complete'])->default('draft');
      $table->timestamps();
    });

    Schema::create('bid_requirements', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bid_id')->constrained()->cascadeOnDelete();
      $table->foreignId('requirement_id')->constrained();
      $table->text('response');
      $table->enum('status', ['pending', 'fulfilled', 'non_compliant'])
        ->default('pending');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bid_requirements');
    Schema::dropIfExists('bid_sections');
    Schema::dropIfExists('bids');
  }
};
