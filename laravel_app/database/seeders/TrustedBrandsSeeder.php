<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrustedBrand;

class TrustedBrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Seven Zone', 'image_url' => '/src/assets/600227056b7527b69705be543b80a752afdf113a.png'],
            ['name' => 'Geet', 'image_url' => '/src/assets/f9299fee2291d2653b9ad275166146e7d0dcf969.png'],
            ['name' => 'Brand 3', 'image_url' => '/src/assets/9a46bf1421ea19514e084d6f8de44420cb549f46.png'],
            ['name' => 'Brand 4', 'image_url' => '/src/assets/050284a61b1befb9fd4efcb5ee28b0e8213df906.png'],
            ['name' => 'Brand 5', 'image_url' => '/src/assets/be057d547073de06678a38796ebc5c25417e74df.png'],
            ['name' => 'Brand 6', 'image_url' => '/src/assets/5d0ae4cf5528886a7469ba06f79edad53593e92d.png'],
            ['name' => 'Nexus', 'image_url' => '/src/assets/96dee2bfb9864119bd21b5369f6dbcde771d81cc.png'],
            ['name' => 'Nexus Design Arabia', 'image_url' => '/src/assets/330faa420bd606ea43428f48ae75c04f6ac26483.png'],
        ];

        foreach ($brands as $b) {
            TrustedBrand::updateOrCreate(
                ['name' => $b['name']],
                ['image_url' => $b['image_url']]
            );
        }
    }
}
