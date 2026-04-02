<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait NormalizesDateRanges
{
    protected function normalizeDateRange(Request $request, string $fromKey, string $toKey): array
    {
        $from = $this->normalizeDateValue($request->input($fromKey));
        $to = $this->normalizeDateValue($request->input($toKey));

        if ($from !== null && $to !== null && $to < $from) {
            $to = $from;
        }

        $request->merge([
            $fromKey => $from,
            $toKey => $to,
        ]);

        return [$from, $to];
    }

    protected function normalizeDateValue(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $trimmed) ? $trimmed : null;
    }
}