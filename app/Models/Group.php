<?php

namespace App\Models;

use App\Models\User;
use App\Models\Account;
use App\Models\Subgroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
  use HasFactory;

  protected $guarded = [];


  // Relationships

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  public function subgroups()
  {
    return $this->hasMany(Subgroup::class);
  }
}
