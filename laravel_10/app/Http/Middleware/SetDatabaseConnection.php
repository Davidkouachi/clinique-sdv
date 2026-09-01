<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie l'URL ou le chemin pour définir la DB
        if ($request->getHost() === 'espacemedicosociallapyramideducomplexe.net' 
            && $request->is('amitie*')) {
            // Clinique 2
            Config::set('database.connections.mysql.database', 'sogema5_cmsp2');
            Config::set('database.connections.mysql.username', 'sogema5_userCMSP');
            Config::set('database.connections.mysql.password', 'A3A7QyM{Iz,G');
        } else {
            // Clinique 1
            Config::set('database.connections.mysql.database', 'sogema5_cmsp');
            Config::set('database.connections.mysql.username', 'sogema5_userCMSP');
            Config::set('database.connections.mysql.password', 'A3A7QyM{Iz,G');
        }

        // Reconnecte la DB avec les nouvelles infos
        DB::purge('mysql');
        DB::reconnect('mysql');

        return $next($request);
    }
}
