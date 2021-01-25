<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Entry;
use Illuminate\Http\Request;
use App\Http\Resources\EntryResource;
use Illuminate\Support\Facades\Validator;

class EntryController extends Controller
{
  public function index ()
  {
    $entries = Entry::orderBy('date', 'desc')->get();
    return response()->json([
      'success' => true,
      'data' => EntryResource::collection($entries)
    ]);
  }

  public function store (Request $request)
  {
    $data = $request->only(['date', 'debit_id', 'credit_id', 'value', 'note']);

    $validator = Validator::make($data, [
      'date' => 'required|date',
      'debit_id' => 'required|integer',
      'credit_id' => 'required|integer',
      'value' => 'required|integer',
      'note' => 'nullable|string',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => implode(' ', $validator->messages()->all())
      ]);
    }

    try {
      $entry = Entry::create($data);

      return response()->json([
        'success' => true,
        'data' => new EntryResource($entry)
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update (Request $request, Entry $entry)
  {
    $data = $request->only(['date', 'debit_id', 'credit_id', 'value', 'note']);

    $validator = Validator::make($data, [
      'date' => 'required|date',
      'debit_id' => 'required|integer',
      'credit_id' => 'required|integer',
      'value' => 'required|integer',
      'note' => 'nullable|string',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => implode(' ', $validator->messages()->all())
      ]);
    }

    try {
      $entry->update($data);

      return response()->json([
        'success' => true,
        'data' => new EntryResource($entry)
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function destroy (Entry $entry)
  {
    try {
      $entry->delete();

      return response()->json([
        'success' => true,
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }
}