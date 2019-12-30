<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Admin::class, 15)->create()->each( function ($admin) {
            $admin->stores()->save(factory(App\Models\Store::class)->make());
        });
    }
}
