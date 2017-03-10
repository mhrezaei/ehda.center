<?php

namespace App\Http\Controllers\Front;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function index()
    {
        $categories = Folder::where('posttype_id', 2)
            ->where('locale', getLocale())->orderBy('title', 'asc')->get();

        return view('front.products.folders.0', compact('categories'));
    }
}
