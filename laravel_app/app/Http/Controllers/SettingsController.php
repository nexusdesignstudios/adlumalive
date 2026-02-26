<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function uploadFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:png,ico|max:1024', // Max 1MB
        ]);

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            // Store in the public/app/assets directory or root public path
            // We want it to be accessible as /favicon.png or /favicon.ico
            
            // Move to public_html/favicon.png (via storage link or direct move if simple)
            // Since we are in a hybrid setup, let's try to put it where the frontend expects it.
            // Usually /favicon.png in the root of public_html.
            
            // We will save it to the "public" disk which maps to storage/app/public
            // And also copy it to the public_html root if possible, or just serve it from storage.
            
            // For simplicity in this specific environment where public_html is the root:
            $destinationPath = public_path(); // This points to laravel_app/public usually
            // But our web root is D:\backup adluma\public_html
            
            // We need to write to D:\backup adluma\public_html\favicon.png
            // We can define a relative path or use the absolute path we know.
            
            $targetPath = base_path('../public_html/favicon.png');
            
            // Ensure we handle the file move correctly
            move_uploaded_file($file->getPathname(), $targetPath);
            
            return response()->json(['message' => 'Favicon updated successfully', 'url' => '/favicon.png?t='.time()]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
