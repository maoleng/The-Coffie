<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        services()->configService()->insert([
            [
                'id' => Str::uuid(),
                'key' => 'register_title',
                'value' => 'XÁC NHẬN ĐĂNG KÝ',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'verify_new_location_title',
                'value' => 'XÁC NHẬN ĐỊA ĐIỂM ĐĂNG NHẬP MỚI',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'reset_password_title',
                'value' => 'YÊU CẦU ĐỔI ĐẶT LẠI MẬT KHẨU',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'facebook_url',
                'value' => 'https://www.facebook.com/maolengg',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'github_url',
                'value' => 'https://github.com/apps/maoleng',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'gitlab_url',
                'value' => 'https://gitlab.com/maoleng',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'google_url',
                'value' => 'feature451@gmail.com',
            ],
            [
                'id' => Str::uuid(),
                'key' => 'linkedin_url',
                'value' => 'https://www.linkedin.com/company/maoleng',
            ],
        ]);
    }
}
