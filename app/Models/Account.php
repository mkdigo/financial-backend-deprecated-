<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use App\Models\Subgroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
  use HasFactory;

  protected $guarded = [];


  // Relationships

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function groups()
  {
    return $this->hasMany(Group::class);
  }

  public function subgroups()
  {
    return $this->hasMany(Subgroup::class);
  }

  public function debits()
  {
    return $this->hasMany(Entry::class, 'debit_id');
  }

  public function credits()
  {
    return $this->hasMany(Entry::class, 'credit_id');
  }
}
