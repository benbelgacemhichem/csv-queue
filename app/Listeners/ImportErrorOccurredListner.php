<?php

namespace App\Listeners;

use App\Events\ImportErrorOccurred;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class ImportErrorOccurredListner
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImportErrorOccurred $event): void
    {
        Log::info('ImportErrorOccurred #' . $event->errorMessage);
        Livewire::emit('import-error-occurred', $event->errorMessage);
        dispatch('import-error-occurred', $event->errorMessage);
        dd(1);
    }
}
