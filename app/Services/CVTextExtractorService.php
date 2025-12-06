<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;

class CVTextExtractorService
{
    /**
     * Extract text from a CV file (PDF, DOC, or DOCX)
     *
     * @param string $filePath Path to the file in storage
     * @return string Extracted text
     * @throws \Exception
     */
    public function extractText(string $filePath): string
    {
        $fullPath = Storage::disk('public')->path($filePath);
        
        if (!file_exists($fullPath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => $this->extractFromPdf($fullPath),
            'doc', 'docx' => $this->extractFromWord($fullPath),
            default => throw new \Exception("Unsupported file type: {$extension}"),
        };
    }

    /**
     * Extract text from PDF file
     */
    private function extractFromPdf(string $filePath): string
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
            
            // Clean up the text
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            
            return $text;
        } catch (\Exception $e) {
            throw new \Exception("Failed to extract text from PDF: " . $e->getMessage());
        }
    }

    /**
     * Extract text from Word document (DOC or DOCX)
     */
    private function extractFromWord(string $filePath): string
    {
        try {
            $phpWord = IOFactory::load($filePath);
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $childElement) {
                            if (method_exists($childElement, 'getText')) {
                                $text .= $childElement->getText() . "\n";
                            }
                        }
                    }
                }
            }

            // Clean up the text
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            
            return $text;
        } catch (\Exception $e) {
            throw new \Exception("Failed to extract text from Word document: " . $e->getMessage());
        }
    }
}


