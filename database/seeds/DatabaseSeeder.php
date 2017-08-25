<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Model::unguard();

        // 指定我们要运行假数据填充的文件
        $this->call(UsersTableSeeder::class);

        Model::reguard();
    }
}
