<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lawsuit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('user_id', Auth::id())->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $lawsuits = Lawsuit::where('user_id', Auth::id())->get();
        return view('clients.create', compact('lawsuits'));
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

        // الحصول على معرف القضية الأحدث للمستخدم الحالي
        $lawsuit = Lawsuit::where('user_id', $user->id)->latest()->first();
        if (!$lawsuit) {
            return redirect()->back()->withErrors(['error' => 'No lawsuit found for the current user.']);
        }

        // تنسيق اسم الموكل لتضمينه في تسمية الملفات
        $clientName = preg_replace('/\s+/', '_', $request->full_name); // استبدال المسافات بالشرطة السفلية
        $clientName = preg_replace('/[^A-Za-z0-9_]/', '_', $clientName); // استبدال الأحرف غير الصالحة

        // إعداد المتغيرات الفارغة
        $idFrontImage = null;
        $idBackImage = null;
        $powerOfAttorney = null;

        // التحقق من تحميل الملفات وتسمية وحفظ كل ملف إذا تم رفعه
        if ($request->hasFile('id_front_image')) {
            $idFrontImageName = "id_front_{$lawsuit->id}_{$user->id}_{$clientName}." . $request->file('id_front_image')->getClientOriginalExtension();
            $idFrontImage = $request->file('id_front_image')->storeAs('id_images', $idFrontImageName, 'public');
        }

        if ($request->hasFile('id_back_image')) {
            $idBackImageName = "id_back_{$lawsuit->id}_{$user->id}_{$clientName}." . $request->file('id_back_image')->getClientOriginalExtension();
            $idBackImage = $request->file('id_back_image')->storeAs('id_images', $idBackImageName, 'public');
        }

        if ($request->hasFile('power_of_attorney')) {
            $powerOfAttorneyName = "power_of_attorney_{$lawsuit->id}_{$user->id}_{$clientName}." . $request->file('power_of_attorney')->getClientOriginalExtension();
            $powerOfAttorney = $request->file('power_of_attorney')->storeAs('attorney_documents', $powerOfAttorneyName, 'public');
        }

        Client::create([
            'lawsuit_id' => $lawsuit->id,
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'id_front_image' => $idFrontImage,
            'id_back_image' => $idBackImage,
            'power_of_attorney' => $powerOfAttorney,
            'notes' => $request->notes,
        ]);


        return redirect()->route('defendants.create', ['lawsuit_id' => $lawsuit->id])->with('success', 'Client created successfully. You can now add a court record.');
    }




    public function update(Request $request, Client $client)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'id_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'power_of_attorney' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('id_front_image')) {
            $idFrontImage = $request->file('id_front_image')->store('id_images');
            $client->id_front_image = $idFrontImage;
        }
        if ($request->hasFile('id_back_image')) {
            $idBackImage = $request->file('id_back_image')->store('id_images');
            $client->id_back_image = $idBackImage;
        }
        if ($request->hasFile('power_of_attorney')) {
            $powerOfAttorney = $request->file('power_of_attorney')->store('attorney_documents');
            $client->power_of_attorney = $powerOfAttorney;
        }

        $client->update([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('clients');
    }


    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $lawsuits = Lawsuit::where('user_id', Auth::id())->get();
        return view('clients.edit', compact('client', 'lawsuits'));
    }


    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
