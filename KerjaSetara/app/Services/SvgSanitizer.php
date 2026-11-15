<?php

namespace App\Services;

use Exception;

class SvgSanitizer
{
    /**
     * Validate and sanitize raw SVG content using SvgSanitizerService.
     *
     * @throws Exception
     */
    public static function sanitize(string $svgContent): string
    {
        $result = app(SvgSanitizerService::class)->validateAndSanitize($svgContent);

        if (! $result['valid']) {
            throw new Exception($result['error'] ?? 'File SVG tidak valid atau mengandung elemen berbahaya.');
        }

        return $result['sanitized'];
    }
}
