<?php

namespace App\Jobs;

use App\Models\TenderDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzeTenderDocument implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(
    private TenderDocument $document
  )
  {
  }

  public function handle(DocumentAnalysisService $analyzer)
  {
    try {
      // Set the tenant connection
      $tenant = $this->document->tender->user->tenant;
      config(['database.connections.tenant.database' => $tenant->database]);

      // Perform analysis
      $result = $analyzer->analyze($this->document->getFirstMedia('document'));

      // Update document with results
      $this->document->update([
        'content' => $result['text'],
        'analyzed_at' => now()
      ]);

      // Create requirements
      foreach ($result['requirements'] as $requirement) {
        $this->document->requirements()->create($requirement);
      }
    } catch (\Exception $e) {
      logger()->error('Document analysis failed', [
        'document_id' => $this->document->id,
        'error' => $e->getMessage()
      ]);
      throw $e;
    }
  }
}
