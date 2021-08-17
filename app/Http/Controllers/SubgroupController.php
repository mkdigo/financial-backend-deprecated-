<?php

namespace App\Http\Controllers;

use App\Models\Subgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubgroupController extends Controller
{
  public function index()
  {
    return [
      'success' => true,
      'data' => auth('api')->user()->subgroups()->get()
    ];
  }

  public function store(Request $request) {
    $data = $request->only(['name', 'group_id']);
    $validator = Validator::make($data, [
      'name' => 'required|string|max:191',
      'group_id' => 'required|integer',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => implode(' ', $validator->messages()->all())
      ]);
    }

    try {
      auth('api')->user()->subgroups()->create($data);

      return response()->json([
        'success' => true
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update(Request $request, Subgroup $subgroup)
  {
    $data = $request->only(['name', 'group_id']);
    $validator = Validator::make($data, [
      'name' => 'required|string|max:191',
      'group_id' => 'required|integer',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => implode(' ', $validator->messages()->all())
      ]);
    }

    try {
      auth('api')->user()->subgroups()->update($data);

      return response()->json([
        'success' => true
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function destroy(Subgroup $subgroup)
  {
    try {
      $subgroup->delete();

      return response()->json([
        'success' => true
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }
}
