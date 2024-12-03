<div>
    <form wire:submit.prevent="import">
        <input type="file" wire:model="file">
        @if ($errors)
            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
        @endif
        <button type="submit">Import CSV</button>
    </form>

    @if ($statusMessage)
        <p>{{ $statusMessage }}</p>
    @endif

    @if ($errors)
        <ul>
            @foreach ($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    
    @if ($errorMessages)
        <ul>
            @foreach ($errorMessages as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>