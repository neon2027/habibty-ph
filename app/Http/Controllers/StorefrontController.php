<?php

namespace App\Http\Controllers;

use App\Support\StorefrontCatalog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StorefrontController extends Controller
{
    public function __invoke(Request $request, StorefrontCatalog $catalog): Response
    {
        return Inertia::render('Welcome', [
            'products' => $catalog->all(),
            'cart' => $catalog->cartSummary($request->session()->get('cart', [])),
        ]);
    }
}
