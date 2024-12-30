<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenderDocument extends Model implements HasMedia
{
  use InteractsWithMedia;

  protected $fillable = [
    'name',
    'type',
    'content',
    'analyzed_at',
  ];

  protected $casts = [
    'analyzed_at' => 'datetime',
  ];

  public function tender(): BelongsTo
  {
    return $this->belongsTo(Tender::class);
  }

  public function requirements(): HasMany
  {
    return $this->hasMany(Requirement::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('document')
      ->singleFile()
      ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
  }
}
