<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetPasswordsSeeder extends Seeder
{
    public function run(): void
    {
        // Update all admin users with password: admin123
        DB::table('users')
            ->where('role', 'admin')
            ->update(['password' => Hash::make('admin123')]);

        // Update all petugas users with password: petugas123
        DB::table('users')
            ->where('role', 'petugas')
            ->update(['password' => Hash::make('petugas123')]);

        // Update all peminjam users with password: peminjam123
        DB::table('users')
            ->where('role', 'peminjam')
            ->update(['password' => Hash::make('peminjam123')]);

        $this->command->info('All user passwords have been reset successfully!');
        $this->command->info('Admin: admin123 | Petugas: petugas123 | Peminjam: peminjam123');
    }
}
