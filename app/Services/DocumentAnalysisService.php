<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

class DocumentAnalysisService
{
  public function analyze(UploadedFile $file)
  {
    $text = $this->extractText($file);
    $requirements = $this->extractRequirements($text);

    return [
      'text' => $text,
      'requirements' => $requirements
    ];
  }

  private function extractText(UploadedFile $file): string
  {
    if ($file->getMimeType() === 'application/pdf') {
      return (new Pdf())
        ->setPdf($file->getPathname())
        ->text();
    }

    // For other document types, convert to image and use OCR
    return (new TesseractOCR($file->getPathname()))
      ->run();
  }

  private function extractRequirements(string $text): array
  {
    $patterns = $this->getRequirementPatterns();
    $requirements = [];

    foreach ($patterns as $type => $typePatterns) {
      foreach ($typePatterns as $pattern) {
        if (preg_match_all($pattern, $text, $matches)) {
          foreach ($matches[0] as $match) {
            $requirements[] = [
              'type' => $type,
              'description' => trim($match),
              'status' => 'pending'
            ];
          }
        }
      }
    }

    return $requirements;
  }

  private function getRequirementPatterns(): array
  {
    return [
      'document' => [
        '/(?:must|shall|required to) (?:provide|submit|present) [^.!?]*(?:[.!?]|$)/',
        '/(?:certificate|documentation|proof) of [^.!?]*(?:[.!?]|$)/'
      ],
      'certification' => [
        '/(?:ISO|certification|certified|accredited) [^.!?]*(?:[.!?]|$)/',
        '/must be (?:certified|accredited) [^.!?]*(?:[.!?]|$)/'
      ],
      'experience' => [
        '/(?:\d+|one|two|three|four|five) years? (?:of )?experience [^.!?]*(?:[.!?]|$)/',
        '/similar projects? [^.!?]*(?:[.!?]|$)/',
        '/track record [^.!?]*(?:[.!?]|$)/'
      ],
      'financial' => [
        '/financial (?:capacity|capability|standing) [^.!?]*(?:[.!?]|$)/',
        '/annual (?:turnover|revenue) [^.!?]*(?:[.!?]|$)/',
        '/bank (?:statement|reference|guarantee) [^.!?]*(?:[.!?]|$)/'
      ],
      'technical' => [
        '/technical (?:capability|capacity|expertise) [^.!?]*(?:[.!?]|$)/',
        '/(?:equipment|machinery|tools) [^.!?]*(?:[.!?]|$)/',
        '/(?:methodology|approach|method statement) [^.!?]*(?:[.!?]|$)/'
      ]
    ];
  }
}
