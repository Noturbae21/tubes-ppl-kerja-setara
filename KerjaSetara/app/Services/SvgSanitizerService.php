<?php

namespace App\Services;

use DOMDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SvgSanitizerService
{
    /**
     * Maximum allowed SVG file size in bytes (100 KB).
     */
    public const MAX_SIZE_BYTES = 102400; // 100 * 1024

    /**
     * Dangerous tags forbidden in safe SVG files.
     */
    protected const DISALLOWED_TAGS = [
        'script',
        'foreignobject',
        'iframe',
        'frame',
        'frameset',
        'object',
        'embed',
        'applet',
        'audio',
        'video',
        'canvas',
        'meta',
        'link',
        'form',
        'input',
        'button',
        'textarea',
        'select',
        'style', // Prevent CSS injection, external fonts/urls
    ];

    /**
     * Allowed SVG tags (safe vector graphics elements).
     */
    protected const ALLOWED_TAGS = [
        'svg',
        'g',
        'path',
        'circle',
        'rect',
        'line',
        'polyline',
        'polygon',
        'ellipse',
        'defs',
        'use',
        'title',
        'desc',
        'lineargradient',
        'radialgradient',
        'stop',
        'clippath',
        'mask',
        'symbol',
        'marker',
        'pattern',
        'text',
        'tspan',
    ];

    /**
     * Validate and sanitize an uploaded SVG file or SVG string.
     *
     * @return array{
     *     valid: bool,
     *     error: ?string,
     *     sanitized: ?string,
     *     filename: ?string,
     *     size_bytes: int,
     *     dimensions: ?string
     * }
     */
    public function validateAndSanitize(UploadedFile|string $input, ?string $originalName = null): array
    {
        $rawContent = '';
        $sizeBytes = 0;
        $fileName = $originalName ?? 'icon.svg';

        if ($input instanceof UploadedFile) {
            $sizeBytes = $input->getSize();
            $fileName = $input->getClientOriginalName();

            if ($sizeBytes > self::MAX_SIZE_BYTES) {
                return [
                    'valid' => false,
                    'error' => 'Batas ukuran file SVG maksimal 100 KB (ukuran file: ' . round($sizeBytes / 1024, 1) . ' KB).',
                    'sanitized' => null,
                    'filename' => $fileName,
                    'size_bytes' => $sizeBytes,
                    'dimensions' => null,
                ];
            }

            $rawContent = file_get_contents($input->getRealPath()) ?: '';
        } else {
            $rawContent = $input;
            $sizeBytes = strlen($rawContent);

            if ($sizeBytes > self::MAX_SIZE_BYTES) {
                return [
                    'valid' => false,
                    'error' => 'Batas ukuran konten SVG maksimal 100 KB.',
                    'sanitized' => null,
                    'filename' => $fileName,
                    'size_bytes' => $sizeBytes,
                    'dimensions' => null,
                ];
            }
        }

        // 1. Basic structural check
        if (! str_contains($rawContent, '<svg') || ! str_contains($rawContent, '</svg>')) {
            return [
                'valid' => false,
                'error' => 'Format file tidak valid. Berkas harus berupa dokumen SVG yang valid.',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        // 2. Reject XXE (XML External Entity) attacks
        if (preg_match('/<!ENTITY/i', $rawContent) || preg_match('/<!DOCTYPE[^>]*\[/i', $rawContent)) {
            return [
                'valid' => false,
                'error' => 'File SVG ditolak: Terdeteksi deklarasi DTD/Entitas eksternal yang tidak aman.',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        // 3. Reject JavaScript event handlers (on* attributes like onload, onerror, onclick, etc.)
        if (preg_match('/\bon[a-z0-9_-]+\s*=/i', $rawContent)) {
            return [
                'valid' => false,
                'error' => 'File SVG ditolak: Terdeteksi event handler JavaScript berbahaya (on* attributes).',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        // 4. Reject dangerous tags via regex fast-check
        foreach (self::DISALLOWED_TAGS as $forbiddenTag) {
            if (preg_match('/<\s*' . $forbiddenTag . '\b/i', $rawContent)) {
                return [
                    'valid' => false,
                    'error' => "File SVG ditolak: Mengandung elemen terlarang '<{$forbiddenTag}>'.",
                    'sanitized' => null,
                    'filename' => $fileName,
                    'size_bytes' => $sizeBytes,
                    'dimensions' => null,
                ];
            }
        }

        // 5. Reject dangerous protocols in attributes (href, xlink:href, src, etc.)
        if (preg_match('/(javascript|vbscript|data:text\/html|data:application\/javascript)\s*:/i', $rawContent)) {
            return [
                'valid' => false,
                'error' => 'File SVG ditolak: Mengandung URI skrip atau skema berbahaya.',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        // 6. Reject external network URLs in attributes (http://, https://)
        if (preg_match('/(href|xlink:href|src)\s*=\s*["\']https?:\/\//i', $rawContent)) {
            return [
                'valid' => false,
                'error' => 'File SVG ditolak: Referensi jaringan eksternal (http/https) tidak diizinkan demi keamanan.',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        // 7. Parse and clean using DOMDocument
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $previousErrors = libxml_use_internal_errors(true);
        $loaded = $dom->loadXML($rawContent, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);

        if (! $loaded) {
            return [
                'valid' => false,
                'error' => 'File SVG tidak dapat diurai sebagai format XML yang valid.',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        $root = $dom->documentElement;
        if (! $root || strtolower($root->nodeName) !== 'svg') {
            return [
                'valid' => false,
                'error' => 'Root elemen file harus berupa elemen <svg>.',
                'sanitized' => null,
                'filename' => $fileName,
                'size_bytes' => $sizeBytes,
                'dimensions' => null,
            ];
        }

        // Sanitize DOM recursively
        $this->sanitizeNode($root);

        // Extract dimensions if available
        $width = $root->getAttribute('width');
        $height = $root->getAttribute('height');
        $viewBox = $root->getAttribute('viewBox');

        $dimensions = null;
        if ($width && $height) {
            $dimensions = "{$width} × {$height} px";
        } elseif ($viewBox) {
            $parts = preg_split('/[\s,]+/', trim($viewBox));
            if (count($parts) === 4) {
                $dimensions = "{$parts[2]} × {$parts[3]} px";
            }
        }

        // Ensure default viewBox or dimensions exist for consistent rendering
        if (! $root->hasAttribute('viewBox') && $width && $height) {
            $root->setAttribute('viewBox', "0 0 {$width} {$height}");
        }
        if (! $root->hasAttribute('xmlns')) {
            $root->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        }

        $sanitizedContent = $dom->saveXML($root);

        return [
            'valid' => true,
            'error' => null,
            'sanitized' => $sanitizedContent,
            'filename' => $fileName,
            'size_bytes' => strlen($sanitizedContent),
            'dimensions' => $dimensions ?? '24 × 24 px',
        ];
    }

    /**
     * Recursively sanitize DOM nodes and attributes.
     */
    protected function sanitizeNode(\DOMNode $node): void
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return;
        }

        $nodeName = strtolower($node->nodeName);

        // Remove unallowed elements
        if (! in_array($nodeName, self::ALLOWED_TAGS, true)) {
            $node->parentNode?->removeChild($node);
            return;
        }

        // Sanitize attributes
        if ($node->hasAttributes()) {
            $attributesToRemove = [];
            foreach ($node->attributes as $attr) {
                $attrName = strtolower($attr->name);
                $attrValue = strtolower(trim($attr->value));

                // Remove event handlers (on*)
                if (str_starts_with($attrName, 'on')) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }

                // Remove unsafe URI schemes
                if (str_contains($attrValue, 'javascript:') || str_contains($attrValue, 'data:') || str_contains($attrValue, 'vbscript:')) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }

                // Remove external URLs
                if (str_starts_with($attrValue, 'http://') || str_starts_with($attrValue, 'https://')) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }
            }

            foreach ($attributesToRemove as $attrName) {
                $node->removeAttribute($attrName);
            }
        }

        // Process children
        $children = iterator_to_array($node->childNodes);
        foreach ($children as $child) {
            $this->sanitizeNode($child);
        }
    }

    /**
     * Store sanitized SVG to public disk storage.
     *
     * @param  string  $sanitizedSvg Safe SVG content
     * @param  string  $slugName     Base slug for filename
     * @return string Relative storage path (e.g. 'facilities/icons/akses-kursi-roda.svg')
     */
    public function storeSvg(string $sanitizedSvg, string $slugName): string
    {
        $cleanSlug = Str::slug($slugName);
        $fileName = "facilities/icons/{$cleanSlug}-" . Str::random(6) . '.svg';

        Storage::disk('public')->put($fileName, $sanitizedSvg);

        // Also ensure direct public path mirror if available for resilient serving
        $publicDir = public_path('storage/facilities/icons');
        if (! is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        @file_put_contents(public_path('storage/' . $fileName), $sanitizedSvg);

        return $fileName;
    }
}
