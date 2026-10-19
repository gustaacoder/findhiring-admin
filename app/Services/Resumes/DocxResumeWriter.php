<?php

namespace App\Services\Resumes;

class DocxResumeWriter
{
    /** @param list<string> $paragraphs */
    public function write(array $paragraphs, string $targetPath): void
    {
        $directory = dirname($targetPath);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $archive = new \ZipArchive;

        if ($archive->open($targetPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create DOCX document at '.$targetPath);
        }

        $archive->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $archive->addFromString('_rels/.rels', $this->relationshipsXml());
        $archive->addFromString('word/document.xml', $this->documentXml($paragraphs));
        $archive->close();
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
            .'</Types>';
    }

    private function relationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
            .'</Relationships>';
    }

    /** @param list<string> $paragraphs */
    private function documentXml(array $paragraphs): string
    {
        $body = '';

        foreach ($paragraphs as $paragraph) {
            $body .= '<w:p><w:r><w:t xml:space="preserve">'.htmlspecialchars($paragraph, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</w:t></w:r></w:p>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            .'<w:body>'.$body.'</w:body>'
            .'</w:document>';
    }
}
