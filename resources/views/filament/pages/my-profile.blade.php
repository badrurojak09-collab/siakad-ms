<x-filament-panels::page>
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                <x-filament::icon icon="heroicon-o-user" class="h-7 w-7" />
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-950 dark:text-white">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>