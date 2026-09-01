<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

// use App\Models\societe;

class societeController extends Controller
{
    // public function societe_new()
    // {
    //     $socies = societe::orderBy('created_at', 'desc')->get();

    //     return view('assurance.nouveau.societe',['socies' => $socies]);
    // }

    // public function insert_societe(Request $request)
    // {

    // }

    // public function update_societe(Request $request, $id)
    // {

    // }
}
