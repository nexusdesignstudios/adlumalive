<?php

namespace App\Http\Controllers;

use App\Models\TrustedBrand;
use Illuminate\Http\Request;

class TrustedBrandController extends Controller
{
    public function index()
    {
        return response()->json(TrustedBrand::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|string',
        ]);

        $brand = TrustedBrand::create($data);
        return response()->json($brand, 201);
    }

    public function show(TrustedBrand $trustedBrand)
    {
        return response()->json($trustedBrand);
    }

    public function update(Request $request, TrustedBrand $trustedBrand)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image_url' => 'nullable|string',
        ]);

        $trustedBrand->update($data);
        return response()->json($trustedBrand);
    }

    public function destroy(TrustedBrand $trustedBrand)
    {
        $trustedBrand->delete();
        return response()->json(null, 204);
    }
}
