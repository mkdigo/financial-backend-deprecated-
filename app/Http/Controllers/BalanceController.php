<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Entry;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
  public function index(Request $request)
  {
    $validateData = $request->only('yearMonth');
    $validator = Validator::make($validateData, [
      "yearMonth" => "required|string|regex:/\d{4}-\d{2}/"
    ]);

    if($validator->fails()) return response()->json([
      "success" => false,
      "errors" => implode(' ', $validator->messages()->all())
    ]);

    $yearMonth = explode('-', $request->yearMonth);
    $date = date("Y-m-t", strtotime($yearMonth[0] . "-" . $yearMonth[1] . "-01"));

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
    $incomeStatement = [];
    $balance = [];

    try {
      // Income Statement
      $incomeStatementAccounts = auth('api')->user()->accounts()->where('group_id', 4)->orderBy('name')->get();

      $incomeStatement['revenues'] = [];
      $incomeStatement['expenses'] = [];
      $incomeStatement['amounts']['revenues'] = 0;
      $incomeStatement['amounts']['expenses'] = 0;

      foreach($incomeStatementAccounts as $account) {
        $debits = auth('api')->user()->entries()->where('debit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $credits = auth('api')->user()->entries()->where('credit_id', $account->id)->whereDate('date', '<=', $date)->get();

        if($account->subgroup_id === 7) {
          $amount = $credits->sum('value') - $debits->sum('value');
          if($amount !== 0) {
            $incomeStatement['revenues'][$account->name] = $amount;
            $incomeStatement['amounts']['revenues'] +=  $amount;
          }
        } else {
          $amount = $debits->sum('value') - $credits->sum('value');
          if($amount !== 0) {
            $incomeStatement['expenses'][$account->name] = $amount;
            $incomeStatement['amounts']['expenses'] +=  $amount;
          }
        }
      }
      $incomeStatement['amounts']['incomeBeforeTaxes'] = $incomeStatement['amounts']['revenues'] - $incomeStatement['amounts']['expenses'];


      // Balance

      // Assets
      $assetsAccounts = auth('api')->user()->accounts()->where('group_id', 1)->orderBy('name')->get();

      $balance['assets']['current'] = [];
      $balance['assets']['longTerm'] = [];
      $balance['assets']['property'] = [];
      $balance['liabilities']['current'] = [];
      $balance['liabilities']['longTerm'] = [];
      $balance['equity'] = [];

      $currentAssets = 0;
      $longTermAssets = 0;
      $property = 0;

      foreach($assetsAccounts as $account) {
        $debits = auth('api')->user()->entries()->where('debit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $credits = auth('api')->user()->entries()->where('credit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $amount = $debits->sum('value') - $credits->sum('value');

        if($account->subgroup_id === 1) {
          // Current Assets
          $balance['assets']['current'][$account->name] = $amount;
          $currentAssets += $amount;
        } else if($account->subgroup_id === 3) {
          // Long Term Assets
          $balance['assets']['longTerm'][$account->name] = $amount;
          $longTermAssets += $amount;
        } else {
          // Property
          $balance['assets']['property'][$account->name] = $amount;
          $property += $amount;
        }
      }
      $balance['amounts']['assets'] = $currentAssets + $longTermAssets + $property;
      $balance['amounts']['currentAssets'] = $currentAssets;
      $balance['amounts']['longTermAssets'] = $longTermAssets;
      $balance['amounts']['property'] = $property;

      // Liabilities
      $liabilitiesAccounts = auth('api')->user()->accounts()->where('group_id', 2)->orderBy('name')->get();

      $currentLiabilities = 0;
      $longTermLiabilities = 0;

      foreach($liabilitiesAccounts as $account) {
        $debits = auth('api')->user()->entries()->where('debit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $credits = auth('api')->user()->entries()->where('credit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $amount = $credits->sum('value') - $debits->sum('value');

        if($account->subgroup_id === 4) {
          // Current Liabilities
          $balance['liabilities']['current'][$account->name] = $amount;
          $currentLiabilities += $amount;
        } else {
          // Long Term Liabilities
          $balance['liabilities']['longTerm'][$account->name] = $amount;
          $longTermLiabilities += $amount;
        }
      }
      $balance['amounts']['currentLiabilities'] = $currentLiabilities;
      $balance['amounts']['longTermLiabilities'] = $longTermLiabilities;

      // Owner's Equity
      $liabilitiesAccounts = auth('api')->user()->accounts()->where('group_id', 3)->orderBy('name')->get();

      $equity = 0;

      foreach($liabilitiesAccounts as $account) {
        $debits = auth('api')->user()->entries()->where('debit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $credits = auth('api')->user()->entries()->where('credit_id', $account->id)->whereDate('date', '<=', $date)->get();
        $amount = $credits->sum('value') - $debits->sum('value');

        if(preg_match("/lucro/i", $account->name)) {
          $amount = $amount + $incomeStatement['amounts']['incomeBeforeTaxes'];
        }

        $balance['equity'][$account->name] = $amount;
        $equity += $amount;
      }
      $balance['amounts']['equity'] = $equity;
      $balance['amounts']['liabilities'] = $currentLiabilities + $longTermLiabilities + $equity;


      return response()->json([
        "success" => true,
        "balance" => $balance,
        "incomeStatement" => $incomeStatement
      ]);
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "errors" => $e->getMessage()
      ]);
    }
  }
}
