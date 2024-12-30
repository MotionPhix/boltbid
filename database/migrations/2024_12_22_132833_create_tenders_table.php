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
    Schema::create('tenders', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->foreignId('user_id')->constrained();
      $table->string('title');
      $table->text('description');
      $table->string('client');
      $table->string('sector');
      $table->decimal('budget', 15, 2);
      $table->date('deadline');
      $table->enum('status', ['new', 'analyzing', 'bidding', 'submitted', 'won', 'lost']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tenders');
  }
};
