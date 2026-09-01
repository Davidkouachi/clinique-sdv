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

// use App\Models\produit;

class produitController extends Controller
{
    // public function produit_new()
    // {
    //     $today = Carbon::today();
    //     $prods = produit::whereDate('created_at', $today)
    //                         ->orderBy('created_at', 'desc')
    //                         ->get();

    //     return view('assurance.nouveau.produit',['prods' => $prods]);
    // }

    // public function insert_produit(Request $request)
    // {

    // }

    // public function update_produit(Request $request, $id)
    // {

    // }
}
