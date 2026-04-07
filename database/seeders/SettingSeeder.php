<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::set('site_name', 'Ravaa Invitation');
        Setting::set('admin_email', 'ravaacreative@gmail.com');
        Setting::set('admin_whatsapp', '6282233377661');
        Setting::set('base_url', 'https://invitation.ravaa.my.id');
        Setting::set('wish_limit', 200);
        Setting::set('maintenance_mode', 0);
    }
}
