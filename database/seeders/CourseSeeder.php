<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i =1; $i <= 8000; $i++){//con 9 lan
            DB::table('courses')->insert([
                'name' => Str::random(10),
                'description' => Str::random(10),
                'image' => Str::random(5) . ".png",
            ]);
        }
    }
}
