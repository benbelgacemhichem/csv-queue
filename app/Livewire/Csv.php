<?php

namespace App\Livewire;

use App\Events\ImportCompleted;
use App\Events\ImportErrorOccurred;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Jobs\ImportCsvJob;
use Livewire\Attributes\On;

class Csv extends Component
{
    use WithFileUploads;

    public $file;
    public $path;
    public $statusMessage;

    public $importStatus = null;
    public $errorMessages = [];
    public $successMessage = '';

    protected $rules = [
        'file' => 'required|file|mimes:csv,txt|max:10240',
    ];

    public function import()
    {
        $this->validate();

        try {
            // Store file temporarily
            $this->path = $this->file->store('imports');

            // Dispatch the job to queue
            dispatch(new ImportCsvJob($this->path, 1));
            
            $this->statusMessage = 'File is being processed. You will be notified once completed.';
        } catch (\Exception $e) {
            $this->statusMessage = 'An error occurred during the import.';
        }
    }

    // Handle Import Success
    public function handleImportCompleted($message)
    {
        $this->statusMessage = 'Import completed successfully!';
        $this->successMessage = $message;
    }

    // Handle Import Error
    // #[On('import-error-occurred')] 
    public function handleImportError($errorMessage)
    {
        $this->statusMessage = 'Import failed!';
        $this->errorMessages[] = $errorMessage;
    }

    public function render()
    {
        return view('livewire.csv');
    }
}
