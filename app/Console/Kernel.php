<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('Bắt đầu quá trình mở khóa tài khoản');
            
            try {
                // Lấy danh sách các tài khoản cần mở khóa
                $expiredSchedules = DB::connection('sqlsrv')
                    ->table('weapon_change_schedules')
                    ->where('scheduled_at', '<=', now())
                    ->where('processed', 0)
                    ->get();
                
                Log::info('Số lượng tài khoản cần mở khóa: ' . count($expiredSchedules));
                
                foreach ($expiredSchedules as $schedule) {
                    DB::connection('sqlsrv')->beginTransaction();
                    
                    try {
                        // Mở khóa tài khoản (đặt member_class = 0)
                        $updated = DB::connection('sqlsrv')
                            ->table('Tbl_ND_Member_Login')
                            ->where('member_guid', $schedule->member_guid)
                            ->where('member_class', 4) // Chỉ reset nếu vẫn đang ở trạng thái khóa
                            ->update(['member_class' => 0]);
                        
                        // Đánh dấu là đã xử lý
                        DB::connection('sqlsrv')
                            ->table('weapon_change_schedules')
                            ->where('id', $schedule->id)
                            ->update(['processed' => 1]);
                        
                        DB::connection('sqlsrv')->commit();
                        
                        Log::info('Đã mở khóa tài khoản', [
                            'user_id' => $schedule->user_id,
                            'member_guid' => $schedule->member_guid,
                            'updated' => $updated
                        ]);
                    } catch (\Exception $e) {
                        DB::connection('sqlsrv')->rollBack();
                        
                        Log::error('Lỗi khi mở khóa tài khoản: ' . $e->getMessage(), [
                            'user_id' => $schedule->user_id,
                            'member_guid' => $schedule->member_guid,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Lỗi trong quá trình mở khóa tài khoản: ' . $e->getMessage(), [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        })->everyMinute();
    
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
