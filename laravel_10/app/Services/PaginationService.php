<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PaginationService
{
    /**
     * Pagination manuelle optimisée.
     *
     * @param Builder      $query
     * @param string       $countTable
     * @param int          $page
     * @param int          $perPage
     * @param Builder|null $countQuery
     * @param string       $countColumn
     */
    public function paginate(
        Builder $query,
        string $countTable,
        int $page = 1,
        int $perPage = 15,
        ?Builder $countQuery = null,
        string $countColumn = '*'
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Sécurisation
        |--------------------------------------------------------------------------
        */

        $page = max(
            $page,
            1
        );

        $perPage = min(
            max(
                $perPage,
                1
            ),
            100
        );


        /*
        |--------------------------------------------------------------------------
        | QUERY COUNT
        |--------------------------------------------------------------------------
        */

        if ($countQuery === null) {

            $countQuery =
                DB::table($countTable);

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        if ($countColumn === '*') {

            $total =
                $countQuery->count();

        } else {

            $total =
                $countQuery->count(
                    $countColumn
                );

        }


        /*
        |--------------------------------------------------------------------------
        | LAST PAGE
        |--------------------------------------------------------------------------
        */

        $lastPage =
            $total > 0
                ? (int) ceil(
                    $total / $perPage
                )
                : 1;


        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        if ($page > $lastPage) {

            $page = $lastPage;

        }


        /*
        |--------------------------------------------------------------------------
        | OFFSET
        |--------------------------------------------------------------------------
        */

        $offset =
            ($page - 1) * $perPage;


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $data =
            $query

                ->offset(
                    $offset
                )

                ->limit(
                    $perPage
                )

                ->get();


        /*
        |--------------------------------------------------------------------------
        | FROM
        |--------------------------------------------------------------------------
        */

        $from =
            $total > 0
                ? $offset + 1
                : 0;


        /*
        |--------------------------------------------------------------------------
        | TO
        |--------------------------------------------------------------------------
        */

        $to =
            $total > 0
                ? min(
                    $offset + $data->count(),
                    $total
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | RESPONSE STANDARD
        |--------------------------------------------------------------------------
        */

        return [

            'success' => true,

            'data' =>
                $data,

            'meta' => [

                'current_page' =>
                    $page,

                'per_page' =>
                    $perPage,

                'total' =>
                    $total,

                'last_page' =>
                    $lastPage,

                'from' =>
                    $from,

                'to' =>
                    $to,

                'has_more_pages' =>
                    $page < $lastPage,

            ],

        ];

    }
}
