<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use Illuminate\Http\Request;

class BidController extends Controller
{
  public function create(Tender $tender)
  {
    // Load tender with requirements
    $tender->load(['documents.requirements']);

    return Inertia('Bids/Create', [
      'tender' => $tender
    ]);
  }

  public function store(Request $request, Tender $tender)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'executive_summary' => 'required|string',
      'price' => 'required|numeric|min:0',
      'requirements' => 'required|array',
      'requirements.*' => 'required|string',
      'attachments' => 'sometimes|array',
      'attachments.*' => 'file|mimes:pdf|max:10240'
    ]);

    $bid = $tender->bids()->create([
      'user_id' => auth()->id(),
      'title' => $validated['title'],
      'executive_summary' => $validated['executive_summary'],
      'price' => $validated['price'],
      'submission_date' => now(),
      'status' => 'draft'
    ]);

    // Store requirement responses
    foreach ($validated['requirements'] as $requirementId => $response) {
      $bid->requirements()->create([
        'requirement_id' => $requirementId,
        'response' => $response,
        'status' => 'pending'
      ]);
    }

    // Handle attachments
    if ($request->hasFile('attachments')) {
      foreach ($request->file('attachments') as $file) {
        $bid->addMedia($file)
          ->toMediaCollection('attachments');
      }
    }

    return redirect()->route('bids.show', $bid)
      ->with('success', 'Bid created successfully.');
  }

  public function show(Bid $bid)
  {
    $bid->load([
      'tender',
      'requirements.requirement',
      'sections',
      'media'
    ]);

    return Inertia('Bids/Show', [
      'bid' => $bid
    ]);
  }

  public function submit(Bid $bid)
  {
    // Validate all requirements are fulfilled
    if ($bid->requirements()->where('status', '!=', 'fulfilled')->exists()) {
      return back()->with('error', 'All requirements must be fulfilled before submission.');
    }

    $bid->update([
      'status' => 'submitted',
      'submission_date' => now()
    ]);

    return back()->with('success', 'Bid submitted successfully.');
  }
}
