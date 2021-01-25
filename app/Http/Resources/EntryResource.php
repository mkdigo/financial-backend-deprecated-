<?php

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
{
  /**
  * Transform the resource into an array.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return array
  */
  public function toArray($request)
  {
    $debitName = Account::findOrFail($this->debit_id);
    $creditName = Account::findOrFail($this->credit_id);
    return [
      "id" => $this->id,
      "date" => $this->date,
      "debit_id" => $this->debit_id,
      "debit_name" => $debitName->name,
      "credit_id" => $this->credit_id,
      "credit_name" => $creditName->name,
      "value" => $this->value,
      "note" => $this->note,
    ];
  }
}
