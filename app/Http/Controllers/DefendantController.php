<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Defendant;
use App\Models\Lawsuit;
use Illuminate\Support\Facades\Auth;


class DefendantController extends Controller
{
    public function create()
    {
        $lawsuits = Lawsuit::all(); // احصل على جميع القضايا لعرضها في النموذج
        return view('defendants.create-defendant', compact('lawsuits'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'id_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'power_of_attorney' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        $lawsuit = Lawsuit::where('user_id', $user->id)->latest()->first();
        if (!$lawsuit) {
            return redirect()->back()->withErrors(['error' => 'No lawsuit found for the current user.']);
        }

        $defendantName = preg_replace('/\s+/', '_', $request->full_name);
        $defendantName = preg_replace('/[^A-Za-z0-9_]/', '_', $defendantName);

        $idFrontImage = null;
        $idBackImage = null;
        $powerOfAttorney = null;

        if ($request->hasFile('id_front_image')) {
            $idFrontImageName = "id_front_{$lawsuit->id}_{$user->id}_{$defendantName}." . $request->file('id_front_image')->getClientOriginalExtension();
            $idFrontImage = $request->file('id_front_image')->storeAs('id_images', $idFrontImageName, 'public');
        }

        if ($request->hasFile('id_back_image')) {
            $idBackImageName = "id_back_{$lawsuit->id}_{$user->id}_{$defendantName}." . $request->file('id_back_image')->getClientOriginalExtension();
            $idBackImage = $request->file('id_back_image')->storeAs('id_images', $idBackImageName, 'public');
        }

        if ($request->hasFile('power_of_attorney')) {
            $powerOfAttorneyName = "power_of_attorney_{$lawsuit->id}_{$user->id}_{$defendantName}." . $request->file('power_of_attorney')->getClientOriginalExtension();
            $powerOfAttorney = $request->file('power_of_attorney')->storeAs('attorney_documents', $powerOfAttorneyName, 'public');
        }

        Defendant::create([
            'lawsuit_id' => $lawsuit->id,
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'id_front_image' => $idFrontImage,
            'id_back_image' => $idBackImage,
            'power_of_attorney' => $powerOfAttorney,
            'notes' => $request->notes,
        ]);

        return redirect()->route('court_records.create', ['lawsuit_id' => $lawsuit->id])->with('success', 'Defendant created successfully. You can now add a court record.');
    }
}
