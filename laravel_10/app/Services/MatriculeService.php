<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MatriculeService
{
    /**
     * Génère un identifiant numérique unique.
     *
     * @param string $table      Table dans laquelle vérifier l'unicité
     * @param string $column     Colonne dans laquelle vérifier l'unicité
     * @param string $prefix     Préfixe à ajouter
     * @param int    $length     Nombre de chiffres
     * @param string $separator  Séparateur entre préfixe et numéro
     *
     * @return string
     */
    public function generate(
        string $table,
        string $column,
        string $prefix = '',
        int $length = 6,
        string $separator = ''
    ): string {
        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        do {
            $number = random_int($min, $max);

            $value = $prefix . $separator . $number;

        } while (
            DB::table($table)
                ->where($column, $value)
                ->exists()
        );

        return $value;
    }

    /**
     * Génère une chaîne alphanumérique unique.
     */
    public function generateRandom(
        string $table,
        string $column,
        int $length = 6
    ): string {
        do {
            $value = Str::random($length);
        } while (
            DB::table($table)
                ->where($column, $value)
                ->exists()
        );

        return $value;
    }
}