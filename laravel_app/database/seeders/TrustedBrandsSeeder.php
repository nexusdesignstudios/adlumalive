<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrustedBrand;

class TrustedBrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Seven Zone', 'image_url' => '/app/assets/600227056b7527b69705be543b80a752afdf113a-z_76D2o7.png'],
            ['name' => 'Geet', 'image_url' => '/app/assets/f9299fee2291d2653b9ad275166146e7d0dcf969-B2uxWyFS.png'],
            ['name' => 'Brand 3', 'image_url' => '/app/assets/9a46bf1421ea19514e084d6f8de44420cb549f46-CUfbamCl.png'],
            ['name' => 'Brand 4', 'image_url' => '/app/assets/050284a61b1befb9fd4efcb5ee28b0e8213df906-oIvIkows.png'],
            ['name' => 'Brand 5', 'image_url' => '/app/assets/be057d547073de06678a38796ebc5c25417e74df-DWimATsa.png'],
            ['name' => 'Brand 6', 'image_url' => '/app/assets/5d0ae4cf5528886a7469ba06f79edad53593e92d-Bpp71hl6.png'],
            ['name' => 'Nexus', 'image_url' => '/app/assets/96dee2bfb9864119bd21b5369f6dbcde771d81cc-BZ0AG7eC.png'],
            ['name' => 'Nexus Design Arabia', 'image_url' => '/app/assets/330faa420bd606ea43428f48ae75c04f6ac26483-C2tcaeHb.png'],
        ];

        foreach ($brands as $b) {
            TrustedBrand::updateOrCreate(
                ['name' => $b['name']],
                ['image_url' => $b['image_url']]
            );
        }
    }
}
