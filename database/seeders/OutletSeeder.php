<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('outlets')->insert([
            [
                "name" => "Outlet 1",
                "phone" => "017865432",
                "latitude" => "23.777176",
                "longitude" => "90.399452"
            ],
            [
                "name" => "Outlet 2",
                "phone" => "017865232",
                "latitude" => "23.777176",
                "longitude" => "90.399452"
            ],
            [
                "name" => "Outlet 3",
                "phone" => "013865432",
                "latitude" => "23.777176",
                "longitude" => "90.399452"
            ],
            [
                "name" => "Outlet 4",
                "phone" => "117835432",
                "latitude" => "23.777176",
                "longitude" => "90.399452"
            ],
            [
                "name" => "Outlet 5",
                "phone" => "017865932",
                "latitude" => "23.777176",
                "longitude" => "90.399452"
            ],

        ]);
    }
}
