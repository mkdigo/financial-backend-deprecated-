<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subgroup extends Model
{
  use HasFactory;

  protected $guarded = [];


  // Relationships

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function group()
  {
    return $this->belongsTo(Group::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
