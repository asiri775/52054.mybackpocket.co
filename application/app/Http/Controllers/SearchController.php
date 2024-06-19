<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Client\Request;

class SearchController extends Controller
{

    public function products()
    {
        $data = ['results' => [], 'more' => true];
        $query = $_GET['q'] ?? "";
        $products = Product::where('name', 'LIKE', '%' . $query . '%')->limit(10)->get();
        foreach ($products as $product) {
            $data['results'][] = [
                'id' => $product->id,
                'text' => $product->name
            ];
        }
        return response()->json($data);
    }

    public function vendors()
    {
        $data = ['results' => [], 'more' => true];
        $query = $_GET['q'] ?? "";
        $vendors = Vendor::where('name', 'LIKE', '%' . $query . '%')->limit(10)->get();
        foreach ($vendors as $vendor) {
            $data['results'][] = [
                'id' => $vendor->id,
                'text' => $vendor->name
            ];
        }
        return response()->json($data);
    }

}
