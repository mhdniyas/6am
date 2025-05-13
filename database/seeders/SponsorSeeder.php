<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsors = [
            [
                'name' => 'LocalEats Restaurant',
                'category' => 'food',
                'contact_person' => 'Maria Garcia',
                'email' => 'maria@localeats.com',
                'phone' => '555-111-2222',
                'committed_amount' => 2000.00,
                'paid_amount' => 1500.00,
            ],
            [
                'name' => 'CityView Hotel',
                'category' => 'venue',
                'contact_person' => 'Robert Chen',
                'email' => 'robert@cityviewhotel.com',
                'phone' => '555-333-4444',
                'committed_amount' => 3500.00,
                'paid_amount' => 3500.00,
            ],
            [
                'name' => 'SportyGear',
                'category' => 'equipment',
                'contact_person' => 'Alex Johnson',
                'email' => 'alex@sportygear.com',
                'phone' => '555-555-6666',
                'committed_amount' => 1800.00,
                'paid_amount' => 900.00,
            ],
            [
                'name' => 'FastTravel Agency',
                'category' => 'transportation',
                'contact_person' => 'Samantha Brown',
                'email' => 'sam@fasttravel.com',
                'phone' => '555-777-8888',
                'committed_amount' => 1200.00,
                'paid_amount' => 600.00,
            ],
            [
                'name' => 'Digital Promotions',
                'category' => 'marketing',
                'contact_person' => 'Tyrone Williams',
                'email' => 'tyrone@digitalpromo.com',
                'phone' => '555-999-0000',
                'committed_amount' => 1500.00,
                'paid_amount' => 1500.00,
            ],
            [
                'name' => 'Community Bank',
                'category' => 'other',
                'contact_person' => 'Jessica Miller',
                'email' => 'jessica@communitybank.com',
                'phone' => '555-222-3333',
                'committed_amount' => 2500.00,
                'paid_amount' => 2500.00,
            ],
        ];
        
        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}
