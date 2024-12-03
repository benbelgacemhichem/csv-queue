<?php

// app/Jobs/ImportCsvJob.php
namespace App\Jobs;

use App\Events\ImportCompleted;
use App\Events\ImportErrorOccurred;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CsvImport;
use App\Models\CsvImportError;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Livewire\Livewire;

class ImportCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $file;
    protected $userId;

    public function __construct($file, $userId)
    {
        $this->file = $file;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            // Handle the import and capture any errors
            Excel::import(new CsvImport, $this->file, null, \Maatwebsite\Excel\Excel::CSV)
                ->onError(function (\Throwable $e) {
                    // Log the error, or store it in a database
                    CsvImportError::create([
                        'user_id' => $this->userId,
                        'error_message' => $e->getMessage(),
                    ]);
                    // Livewire::emit('import-error-occurred', $e->getMessage());
                    // dispatch('import-error-occurred', $e->getMessage());
                });
               
                dd(1);
                event(new ImportCompleted('Import completed successfully!'));

        } catch (\Exception $e) {
            // Handle other errors here
            Log::error('Import CSV failed', ['error' => $e->getMessage()]);
            CsvImportError::create([
                'user_id' => $this->userId,
                'error_message' => $e->getMessage(),
            ]);
            Livewire::emit('import-error-occurred', $e->getMessage());
            // dispatch('import-error-occurred', $e->getMessage());
            dd(12);
            event(new ImportErrorOccurred($e->getMessage()));
        }
    }

    public function failed(\Exception $exception)
    {
        dd(1);
        // Dispatch error event when job fails
        event(new ImportErrorOccurred($exception->getMessage()));
    }
}
