<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\ReminderBayarKas;

class SendReminderBayarKas extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:bayarkas';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengingat bayar kas bulanan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil semua pengguna yang telah diverifikasi emailnya
        $users = User::whereNotNull('email_verified_at')->get();

        // Kirim notifikasi kepada setiap pengguna
        foreach ($users as $user) {
            $user->notify(new ReminderBayarKas());
        }

        // Tampilkan pesan sukses
        $this->info('Email pengingat kas telah dikirim kepada pengguna yang terverifikasi.');
    }
}
