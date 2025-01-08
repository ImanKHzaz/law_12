<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use App\Models\Lawsuit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialRecordController extends Controller
{
    public function index()
    {
        $financialRecords = FinancialRecord::whereHas('lawsuit', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
        return view('financial_records.index', compact('financialRecords'));
    }

    public function create()
    {
        $lawsuits = Lawsuit::where('user_id', Auth::id())->doesntHave('financialRecord')->get();
        return view('financial_records.create', compact('lawsuits'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'lawsuit_id' => 'required|exists:lawsuits,id|unique:financial_records',
            'claim_value' => 'required|numeric',
            'amount_paid' => 'required|numeric',
            'amount_remaining' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $financialRecord = FinancialRecord::create($request->all());


        return redirect()->route('lawsuits.index', ['lawsuit_id' => $request->lawsuit_id])
            ->with('success', 'Financial record created successfully. You can now create a document.');
    }

    public function edit(FinancialRecord $financialRecord)
    {
        $lawsuits = Lawsuit::where('user_id', Auth::id())->get();
        return view('financial_records.edit', compact('financialRecord', 'lawsuits'));
    }

    public function update(Request $request, FinancialRecord $financialRecord)
    {
        $request->validate([
            'claim_value' => 'required|numeric',
            'amount_paid' => 'required|numeric',
            'amount_remaining' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $financialRecord->update($request->all());

        return redirect()->route('financial_records.index')->with('success', 'Financial record updated successfully.');
    }

    public function destroy(FinancialRecord $financialRecord)
    {
        $financialRecord->delete();
        return redirect()->route('financial_records.index')->with('success', 'Financial record deleted successfully.');
    }
}
