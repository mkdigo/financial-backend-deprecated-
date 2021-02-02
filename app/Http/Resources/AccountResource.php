<?php

namespace App\Http\Resources;

use App\Models\Group;
use App\Models\Subgroup;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
  /**
  * Transform the resource into an array.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return array
  */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'group_id' => $this->group_id,
      'group' => Group::findOrFail($this->group_id)->name,
      'subgroup_id' => $this->subgroup_id,
      'subgroup' => Subgroup::findOrFail($this->subgroup_id)->name,
    ];
  }
}
