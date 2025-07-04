<x-filament::page>
    {{ $this->form }}

    <div class="mt-4 flex gap-2">
        <x-filament::button wire:click="saveInvoiceWrapper">
            Save Invoice
        </x-filament::button>

        <x-filament::button wire:click="generatePdf">
            Generate PDF & Save
        </x-filament::button>

        <x-filament::button wire:click="sendInvoice">
            Send Email & Save
        </x-filament::button>
    </div>
</x-filament::page>
