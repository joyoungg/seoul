<?php

namespace App\Console;

use App\Http\Controllers\HouseController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;
use Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        return;
        $schedule->call(function () {
            $house = new HouseController();
            $result = $house->create(new Request());
            Mail::send('emails.reminder', ['result' => $result], function ($m) use ($result) {
                $m->from('luritas@duse.co.kr', 'SEOUL');
                $m->to('luritas@duse.co.kr', '김재국')->subject("주간데이터 삽입");
            });
        })
//            ->everyMinute();
            ->dailyAt('06:00');
//            ->weeklyOn(0, '04:00')
//            ->timezone('Asia/Seoul');

        $schedule->call(function () {
            $house = new HouseController();
            $result = $house->delete();
            Mail::send('emails.reminder', ['result' => $result], function ($m) use ($result) {
                $m->from('luritas@duse.co.kr', 'SEOUL');
                $m->to('luritas@duse.co.kr', '김재국')->subject("월간데이터 삭제");
            });
        })->monthlyOn(1, '04:00')
            ->timezone('Asia/Seoul');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
