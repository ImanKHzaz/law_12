<?php

namespace App\Http\Controllers;

use App\Models\CourtRecord;
use App\Models\Lawsuit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtRecordController extends Controller
{
    public function index()
    {
        // جلب القضايا المرتبطة بالمستخدم الحالي فقط، مع بيانات الموكل المرتبطة
        $lawsuits = Lawsuit::where('user_id', Auth::id())->with('client')->get();
        return view('lawsuits.index', compact('lawsuits'));
    }



    public function create(Request $request)
    {
        $lawsuitId = $request->lawsuit_id;
        $lawsuit = Lawsuit::find($lawsuitId);

        if (!$lawsuit) {
            return redirect()->back()->with('error', 'Lawsuit not found.');
        }

        $clientId = $request->client_id;

        return view('court_records.create', compact('lawsuitId', 'clientId'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'lawsuit_id' => 'required|exists:lawsuits,id|unique:court_records',

            'court_name' => 'required|string|in:دمشق,ببيلا,جرمانا,داريا',
            'court_number' => 'required|integer|min:1',
            'case_status' => 'required|string|in:انتظار,قيد الدراسة,تم التسجيل,تم الفصل',
            'decision_number' => 'nullable|string',
            'base_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $courtRecord = CourtRecord::create([
            'lawsuit_id' => $request->lawsuit_id,

            'user_id' => Auth::id(),
            'court_name' => $request->court_name,
            'court_number' => $request->court_number,
            'case_status' => $request->case_status,
            'decision_number' => $request->decision_number ?? null,
            'base_number' => $request->base_number ?? null,
            'notes' => $request->notes,
        ]);

        return redirect()->route('documents.create', ['lawsuit_id' => $request->lawsuit_id])->with('success', 'Court record created successfully. You can now create a financial record.');
    }



    public function show(CourtRecord $courtRecord)
    {
        return view('court_records.show', compact('courtRecord'));
    }

    public function edit(CourtRecord $courtRecord)
    {
        $lawsuits = Lawsuit::where('user_id', Auth::id())->get();
        return view('court_records.edit', compact('courtRecord', 'lawsuits'));
    }

    public function update(Request $request, CourtRecord $courtRecord)
    {
        $request->validate([
            'lawsuit_id' => 'required|exists:lawsuits,id|unique:court_records,lawsuit_id,' . $courtRecord->id,
            'court_name' => 'required|string|in:دمشق,ببيلا,جرمانا,داريا',
            'court_number' => 'required|integer|min:1',
            'case_status' => 'required|string|in:انتظار,قيد الدراسة,تم التسجيل,تم الفصل',
            'decision_number' => 'nullable|string',
            'base_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $courtRecord->update([
            'lawsuit_id' => $request->lawsuit_id,
            'court_name' => $request->court_name,
            'court_number' => $request->court_number,
            'case_status' => $request->case_status,
            'decision_number' => $request->decision_number ?? null,
            'base_number' => $request->base_number ?? null,
            'notes' => $request->notes,
        ]);

        return redirect()->route('court_records.index')->with('success', 'Court record updated successfully.');
    }

    public function destroy(CourtRecord $courtRecord)
    {
        $courtRecord->delete();
        return redirect()->route('court_records.index')->with('success', 'Court record deleted successfully.');
    }
}
