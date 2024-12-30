<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bid extends Model
{
  protected $fillable = [
    'tender_id',
    'user_id',
    'title',
    'executive_summary',
    'price',
    'submission_date',
    'status'
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'submission_date' => 'date'
  ];

  public function tender(): BelongsTo
  {
    return $this->belongsTo(Tender::class);
  }

  public function sections(): HasMany
  {
    return $this->hasMany(BidSection::class)->orderBy('order');
  }

  public function requirements(): HasMany
  {
    return $this->hasMany(BidRequirement::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('attachments');
  }
}
