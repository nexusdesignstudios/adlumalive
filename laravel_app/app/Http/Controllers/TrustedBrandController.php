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
        $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'image_url']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('trusted-brands', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $brand = TrustedBrand::create($data);
        return response()->json($brand, 201);
    }

    public function show(TrustedBrand $trustedBrand)
    {
        return response()->json($trustedBrand);
    }

    public function update(Request $request, TrustedBrand $trustedBrand)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image_url' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'image_url']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('trusted-brands', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $trustedBrand->update($data);
        return response()->json($trustedBrand);
    }

    public function destroy(TrustedBrand $trustedBrand)
    {
        $trustedBrand->delete();
        return response()->json(null, 204);
    }
}
