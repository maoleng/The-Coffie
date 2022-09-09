<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetCounterSystemMail extends Command
{
    public const COMMAND = 'system_mail_counter:reset';

    protected $signature = self::COMMAND;
    protected $description = 'Đặt lại bộ đếm số tin nhắn đã gửi cho người dùng mỗi ngày';

    public function handle(): void
    {
        User::query()->where('count_system_mail_daily', '!=', 0)
            ->update(['count_system_mail_daily' => 0]);
    }
}
