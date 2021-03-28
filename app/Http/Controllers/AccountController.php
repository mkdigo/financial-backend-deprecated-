<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Resources\AccountResource;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /*
    Groups
      1 - Assets
      2 - Liabilities
      3 - Owner's Equity
      4 - Income Statement

    Subgroups
      1 - Current Assets
      2 - Property
      3 - Long Term Assets
      4 - Current Liabilities
      5 - Long Term Liabilities
      6 - Owner's Equity
      7 - Revenues
      8 - Expenses
  */

  public function index(Request $request)
  {
    $validateData = $request->only('group_id', 'subgroup_id');

    $validator = Validator::make($validateData, [
      'group_id' => 'integer|nullable',
      'subgroup_id' => 'integer|nullable',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => implode(' ', $validator->messages()->all())
      ]);
    }

    $accounts = auth('api')->user()->accounts();
    if($request->group_id) $accounts = $accounts->where('group_id', $request->group_id);
    if($request->subgroup_id) $accounts = $accounts->where('subgroup_id', $request->subgroup_id);
    $accounts = $accounts->orderBy('name')->get();

    $groups = auth('api')->user()->groups()->orderBy('name')->get();
    $subgroups = auth('api')->user()->subgroups()->orderBy('name')->get();

    return [
      'success' => true,
      'data' => [
        'accounts' => AccountResource::collection($accounts),
        'groups' => $groups,
        'subgroups' => $subgroups
      ]
    ];
  }

  public function store(Request $request)
  {
    $data = $request->only(['name', 'group_id', 'subgroup_id']);

    $validator = Validator::make($data, [
      'name' => 'required|string|max:191',
      'group_id' => 'required|integer',
      'subgroup_id' => 'required|integer',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => implode(' ', $validator->messages()->all())
      ]);
    }

    try {
      $account = auth('api')->user()->accounts()->create($data);

      return response()->json([
        'success' => true,
        'data' => new AccountResource($account)
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->getMessage()
      ]);
    }
  }

  public function update(Request $request, $id)
  {
    $data = $request->only(['name', 'group_id', 'subgroup_id']);

    $validator = Validator::make($data, [
      'name' => 'required|string|max:191',
      'group_id' => 'required|integer',
      'subgroup_id' => 'required|integer',
    ]);

    if($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => implode(' ', $validator->messages()->all())
      ]);
    }

    try {
      $account = auth('api')->user()->accounts()->findOrFail($id);
      $account->update($data);

      return response()->json([
        'success' => true,
        'data' => new AccountResource($account)
      ]);
    } catch(Exception $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->getMessage()
      ]);
    }
  }

  public function destroy($id)
  {
    try {
      $account = auth('api')->user()->accounts()->findOrFail($id);
      $account->delete();

      return response()->json([
        'success' => true
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->getMessage()
      ]);
    }
  }
}
