<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenderController extends Controller
{
  public function index()
  {
    $tenders = auth()->user()->tenders()
      ->with('documents')
      ->latest()
      ->get();

    return Inertia('Tenders/Index', [
      'tenders' => $tenders
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string',
      'client' => 'required|string|max:255',
      'sector' => 'required|string|max:255',
      'budget' => 'required|numeric|min:0',
      'deadline' => 'required|date|after:today',
      'documents' => 'sometimes|array',
      'documents.*' => 'file|mimes:pdf,doc,docx|max:10240'
    ]);

    $tender = auth()->user()->tenders()->create($validated);

    if ($request->hasFile('documents')) {
      foreach ($request->file('documents') as $file) {
        $path = $file->store('tender-documents');
        $tender->documents()->create([
          'name' => $file->getClientOriginalName(),
          'path' => $path,
          'type' => $file->getClientMimeType()
        ]);
      }
    }

    return redirect()->route('tenders.show', $tender);
  }

  public function analyze(Request $request, DocumentAnalysisService $analyzer)
  {
    $request->validate([
      'document' => 'required|file|mimes:pdf,doc,docx|max:10240'
    ]);

    try {
      $result = $analyzer->analyze($request->file('document'));
      return response()->json($result);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
