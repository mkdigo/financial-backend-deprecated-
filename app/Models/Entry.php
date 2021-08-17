<?php

namespace App\Models;

use App\Models\User;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function debit()
  {
    return $this->belongsTo(Account::class, 'debit_id');
  }

  public function credit()
  {
    return $this->belongsTo(Account::class, 'credit_id');
  }
}
