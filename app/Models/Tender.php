<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tender extends Model
{
  protected $fillable = ['name', 'domain', 'database'];

  public function users(): HasMany
  {
    return $this->hasMany(User::class);
  }
}
