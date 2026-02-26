<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'title' => 'Project Alpha',
            'description' => 'A comprehensive digital transformation project for a leading retail brand.',
            'image_url' => 'https://via.placeholder.com/600x400',
            'link' => 'https://example.com/alpha',
            'category' => 'Web Development',
            'is_featured' => true,
        ]);

        Project::create([
            'title' => 'Project Beta',
            'description' => 'Brand strategy and identity design for a fintech startup.',
            'image_url' => 'https://via.placeholder.com/600x400',
            'link' => 'https://example.com/beta',
            'category' => 'Branding',
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Project Gamma',
            'description' => 'SEO and content marketing campaign that drove 200% growth.',
            'image_url' => 'https://via.placeholder.com/600x400',
            'link' => 'https://example.com/gamma',
            'category' => 'Marketing',
            'is_featured' => true,
        ]);
    }
}
