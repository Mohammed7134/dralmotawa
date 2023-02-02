<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use Illuminate\Console\Command;

class dailyWisdom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Wisdom:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'showing wisdom every time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = Subscriber::all()->first();
        $user->name = date("Y-m-d H:i:s");
        $user->save();
    }
}
