<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\DB;

trait HandlesSafeDelete
{
    /**
     * Pārbauda, vai ieraksts tiek izmantots citās tabulās pirms dzēšanas.
     *
     * @param array<int, array{table:string,column:string,label:string}> $references
     * @return array<int, string>
     */
    protected function detectReferenceUsage(int|string $id, array $references): array
    {
        $usedIn = [];

        foreach ($references as $reference) {
            $count = DB::table($reference['table'])
                ->where($reference['column'], $id)
                ->count();

            if ($count > 0) {
                $usedIn[] = $reference['label'];
            }
        }

        return $usedIn;
    }

    /**
     * Dzēš ierakstu no tabulas, īslaicīgi atspējojot ārējo atslēgu pārbaudes.
     */
    protected function deleteWithForeignKeyChecksDisabled(string $table, string $primaryKey, int|string $id): void
    {
        [$disableStatement, $enableStatement] = $this->getForeignKeyCheckStatements();

        if ($disableStatement !== null) {
            DB::statement($disableStatement);
        }

        try {
            DB::table($table)->where($primaryKey, $id)->delete();
        } finally {
            if ($enableStatement !== null) {
                DB::statement($enableStatement);
            }
        }
    }

    /**
     * @return array{0:string|null,1:string|null}
     */
    protected function getForeignKeyCheckStatements(): array
    {
        return match (DB::getDriverName()) {
            'mysql' => ['SET FOREIGN_KEY_CHECKS=0', 'SET FOREIGN_KEY_CHECKS=1'],
            'sqlite' => ['PRAGMA foreign_keys = OFF', 'PRAGMA foreign_keys = ON'],
            default => [null, null],
        };
    }

    /**
     * @param array<int, string> $usedIn
     */
    /**
     * Izveido ziņojumu pēc ieraksta dzēšanas, brīdinot par saistītajām tabulām, ja tādas ir.
     *
     * @param array<int, string> $usedIn
     */
    protected function buildDeleteMessage(string $entityName, array $usedIn): string
    {
        if ($usedIn === []) {
            return $entityName . ' ieraksts dzēsts.';
        }

        return $entityName . ' ieraksts dzēsts. Brīdinājums: ieraksts tika izmantots citās tabulās (' . implode(', ', $usedIn) . ').';
    }
}
