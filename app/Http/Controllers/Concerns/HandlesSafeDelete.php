<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\DB;

trait HandlesSafeDelete
{
    /**
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

    protected function deleteWithForeignKeyChecksDisabled(string $table, string $primaryKey, int|string $id): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            DB::table($table)->where($primaryKey, $id)->delete();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
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
