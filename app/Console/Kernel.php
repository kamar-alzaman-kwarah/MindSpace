<?php

namespace App\Console;

use App\Models\res;
use App\Models\book;
use Illuminate\Console\Scheduling\Schedule;
//use App\Console\Commands\send;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        'App\Console\Commands\send',
    ];

    protected function schedule(Schedule $schedule)
    {
         $schedule->command('user:send')->everyMinute();

         $schedule->call(function(){
            $res = res::get();
            foreach($res as $r)
            {
                if($r->created_at->addDays(3)->toDateString() == date('Y-m-d'))
                {
                    book::where('id', $r->book_id)->update([
                        'copies_number' => book::raw("copies_number+$r->number")
                    ]);
                    res::where('id', $r->id)->delete();
                }
            }
         })->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
