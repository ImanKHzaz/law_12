<?php

namespace App\Http\Controllers;

use App\Models\Lawsuit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LawsuitController extends Controller
{
    public function index()
    {
        $lawsuits = Lawsuit::where('user_id', Auth::id())->get();
        return view('lawsuits.index', compact('lawsuits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lawsuit_subject' => 'required|string|max:255',
            'lawsuit_type' => 'required|string',
        ]);

        $user = Auth::user();
        $nextCaseNumber = Lawsuit::where('user_id', $user->id)->max('next_case_number') + 1;
        $userCaseNumber = Lawsuit::where('user_id', $user->id)->max('user_case_number') + 1;

        $lawsuit = Lawsuit::create([
            'user_id' => Auth::id(),
            'lawsuit_subject' => $request->lawsuit_subject,
            'lawsuit_type' => $request->lawsuit_type,
            'next_case_number' => $nextCaseNumber,
            'user_case_number' => $userCaseNumber,
        ]);

        return redirect()->route('clients.create', ['lawsuit_id' => $lawsuit->id])->with('success', 'Lawsuit created successfully. You can now add clients.');
    }

    public function create()
    {
        // حساب next_case_number و user_case_number جديدين عند عرض نموذج الإنشاء
        $user = Auth::user();
        $nextCaseNumber = Lawsuit::where('user_id', $user->id)->max('next_case_number') + 1;
        $userCaseNumber = Lawsuit::where('user_id', $user->id)->max('user_case_number') + 1;

        return view('lawsuits.create', compact('nextCaseNumber', 'userCaseNumber'));
    }

    public function edit($id)
    {
        $lawsuit = Lawsuit::find($id);

        // احصل على القيم المطلوبة
        $nextCaseNumber = $lawsuit->next_case_number;
        $userCaseNumber = $lawsuit->user_case_number;

        return view('lawsuits.edit', compact('lawsuit', 'nextCaseNumber', 'userCaseNumber'));
    }


    public function show($id)
    {
        $lawsuit = Lawsuit::with(['clients', 'courtRecord', 'financialRecord'])->find($id);

        if (!$lawsuit) {
            return redirect()->route('lawsuits.index')->with('error', 'القضية غير موجودة.');
        }

        return view('lawsuits.show', compact('lawsuit'));
    }





    public function update(Request $request, Lawsuit $lawsuit)
    {
        $request->validate([
            'lawsuit_type' => 'required|string',
            'lawsuit_subject' => 'required|string|max:255',
        ]);

        $lawsuit->update([
            'lawsuit_type' => $request->lawsuit_type,
            'lawsuit_subject' => $request->lawsuit_subject,
            'next_case_number' => $lawsuit->id + 1,
            'user_case_number' => $lawsuit->user_case_number,
        ]);

        return redirect()->route('lawsuits.index');
    }

    public function destroy(Lawsuit $lawsuit)
    {
        $lawsuit->delete();
        return redirect()->route('lawsuits.index');
    }
}
