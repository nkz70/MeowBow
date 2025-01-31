<?php

use Illuminate\Database\Seeder;
use App\Models\Tweet;
use Carbon\Carbon;

class TweetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Tweet::create([
              'user_id'    => $i,
              'content'    => 'テスト投稿'.$i,
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now(),
            ]);
        }
    }
}
