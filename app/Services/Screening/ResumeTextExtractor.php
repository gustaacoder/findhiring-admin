<?php

namespace App\Services\Screening;

use App\Exceptions\ScreeningException;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Smalot\PdfParser\Parser;
use ZipArchive;

class ResumeTextExtractor
{
    private const MIN_TEXT_LENGTH = 40;

    public function extract(string $absolutePath, string $mimeType): string
    {
        if (! is_file($absolutePath)) {
            throw ScreeningException::resumeExtractionFailed('file not found.');
        }

        $text = match ($mimeType) {
            'application/pdf' => $this->extractFromPdf($absolutePath),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => $this->extractFromDocx($absolutePath),
            default => throw ScreeningException::resumeExtractionFailed('unsupported document type.'),
        };

        $text = $this->normalize($text);

        $limit = (int) config('findhiring.screening.max_resume_characters', 20000);

        if (mb_strlen($text) > $limit) {
            $text = mb_substr($text, 0, $limit);
        }

        if (mb_strlen(trim($text)) < self::MIN_TEXT_LENGTH) {
            throw ScreeningException::emptyResumeText();
        }

        return $text;
    }

    private function extractFromPdf(string $absolutePath): string
    {
        try {
            $parser = new Parser;
            $document = $parser->parseFile($absolutePath);

            return $document->getText();
        } catch (\Throwable $exception) {
            throw ScreeningException::resumeExtractionFailed('unable to parse PDF content.');
        }
    }

    private function extractFromDocx(string $absolutePath): string
    {
        $archive = new ZipArchive;

        if ($archive->open($absolutePath) !== true) {
            throw ScreeningException::resumeExtractionFailed('unable to open DOCX archive.');
        }

        $xml = $archive->getFromName('word/document.xml');
        $archive->close();

        if (! is_string($xml) || $xml === '') {
            throw ScreeningException::resumeExtractionFailed('DOCX document part is missing.');
        }

        $document = new DOMDocument;

        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            throw ScreeningException::resumeExtractionFailed('DOCX document part is not valid XML.');
        }

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $lines = [];

        foreach ($xpath->query('//w:p') as $paragraph) {
            if (! $paragraph instanceof DOMElement) {
                continue;
            }

            $parts = [];

            foreach ($xpath->query('.//w:t', $paragraph) as $textNode) {
                $parts[] = $textNode->textContent;
            }

            $line = trim(implode('', $parts));

            if ($line !== '') {
                $lines[] = $line;
            }
        }

        return implode("\n", $lines);
    }

    private function normalize(string $text): string
    {
        $text = str_replace(["\xc2\xa0", "\t"], ' ', $text);
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $text) ?? $text;
        $text = preg_replace('/\r\n?/', "\n", $text) ?? $text;

        $lines = array_map(
            fn (string $line): string => trim(preg_replace('/\s+/u', ' ', $line) ?? $line),
            explode("\n", $text),
        );

        return trim(implode("\n", array_filter($lines, fn (string $line): bool => $line !== '')));
    }
}
