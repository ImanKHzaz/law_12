<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Lawsuit;
use App\Models\Client;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('documents.index', compact('documents'));
    }

    public function create(Request $request)
    {
        $lawsuitId = $request->lawsuit_id;
        $lawsuit = Lawsuit::find($lawsuitId);


        return view('documents.create', compact('lawsuitId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lawsuit_id' => 'required|exists:lawsuits,id',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $lawsuitId = $request->input('lawsuit_id');
        $attachments = $request->file('attachments');

        if ($attachments) {
            foreach ($attachments as $attachment) {
                $name = time() . '_' . $attachment->getClientOriginalName();
                $path = $attachment->storeAs('attachments', $name, 'public');

                Document::create([
                    'lawsuit_id' => $lawsuitId,
                    'file_name' => $name,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('financial_records.create')->with('success', 'تم حفظ الوثائق بنجاح.');
    }


    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $lawsuits = Lawsuit::all();
        $clients = Client::all();
        return view('documents.edit', compact('document', 'lawsuits', 'clients'));
    }
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // إضافة دعم لنوع الملف وورد
        ]);

        $data = [
            'attachments' => $document->attachments,
        ];

        if ($request->hasfile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('attachments'), $name);
                $data['attachments'][] = $name;
            }
        }

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
