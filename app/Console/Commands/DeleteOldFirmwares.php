<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldFirmwares extends Command
{
    protected $signature = 'firmwares:delete-old';
    protected $description = 'Удаляем через 10 минут файлы обновления';

    public function handle()
    {
        $files = Storage::allFiles('firmwares');

        foreach ($files as $file) {
            $lastModified = Storage::lastModified($file);
            if (time() - $lastModified > 600) { // 600 секунд = 10 минут
                Storage::delete($file);
                $this->info("Deleted: $file");
            }
        }
    }
}
