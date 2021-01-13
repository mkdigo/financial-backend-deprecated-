<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
  public function index()
  {
    return [
      'success' => true,
      'data' => auth('api')->user()->groups()->get()
    ];
  }

  public function store(Request $request) {
    $data = $request->only(['name']);
    $validator = Validator::make($data, [
      'name' => 'required|string|max:191',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'erros' => $validator->errors()
      ]);
    }

    try {
      auth('api')->user()->groups()->create($data);

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

  public function update(Request $request, Group $group)
  {
    $data = $request->only(['name']);
    $validator = Validator::make($data, [
      'name' => 'required|string|max:191',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'erros' => $validator->errors()
      ]);
    }

    try {
      auth('api')->user()->groups()->update($data);

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

  public function destroy(Group $group)
  {
    try {
      $group->delete();

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
