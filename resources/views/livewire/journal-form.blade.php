<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Form Transaksi Jurnal Umum</h2>

    <!-- Alert / Flash Message -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="mb-4 p-4 text-yellow-700 bg-yellow-100 rounded-lg">
            {{ session('warning') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <!-- Header Jurnal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Pilih Cabang (SR1/SR2) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cabang *</label>
                <select wire:model.live="branch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if(auth()->check() && !auth()->user()->is_super_admin) disabled @endif>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->code }} - {{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Tanggal Transaction -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" wire:model="date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Keterangan Transaksi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Memori *</label>
                <input type="text" wire:model="description" placeholder="Deskripsi transaksi..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Detail Baris Jurnal Dinamis -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-3 border">Akun Rekening (COA)</th>
                        <th class="p-3 border">Keterangan Baris</th>
                        <th class="p-3 border w-40 text-right">Debit (Rp)</th>
                        <th class="p-3 border w-40 text-right">Kredit (Rp)</th>
                        <th class="p-3 border w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <!-- Akun COA -->
                            <td class="p-2 border">
                                <select wire:model.live="items.{{ $index }}.account_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                    @endforeach
                                </select>
                                @error("items.$index.account_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </td>

                            <!-- Deskripsi Item -->
                            <td class="p-2 border">
                                <input type="text" wire:model="items.{{ $index }}.description" placeholder="Opsional" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </td>

                            <!-- Debit -->
                            <td class="p-2 border">
                                <input type="number" step="0.01" min="0" wire:model.live.debounce.300ms="items.{{ $index }}.debit" class="w-full border-gray-300 rounded-md shadow-sm text-sm text-right">
                            </td>

                            <!-- Kredit -->
                            <td class="p-2 border">
                                <input type="number" step="0.01" min="0" wire:model.live.debounce.300ms="items.{{ $index }}.credit" class="w-full border-gray-300 rounded-md shadow-sm text-sm text-right">
                            </td>

                            <!-- Tombol Hapus Baris -->
                            <td class="p-2 border text-center">
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-800 font-bold text-lg">
                                    &times;
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <!-- Total Debit & Kredit -->
                    <tr class="bg-gray-50 font-semibold text-sm">
                        <td colspan="2" class="p-3 border text-right">Total:</td>
                        <td class="p-3 border text-right text-blue-600">
                            Rp {{ number_format($totalDebit, 2, ',', '.') }}
                        </td>
                        <td class="p-3 border text-right text-blue-600">
                            Rp {{ number_format($totalCredit, 2, ',', '.') }}
                        </td>
                        <td class="p-3 border"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Control: Tambah Baris, Status Balance & Submit -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <button type="button" wire:click="addItem" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                + Tambah Baris
            </button>

            <div class="flex items-center gap-4">
                <!-- Indicator Balance -->
                <div>
                    @if($isBalanced)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            ✓ Balance (Seimbang)
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            ✕ Unbalanced (Selisih: Rp {{ number_format(abs($totalDebit - $totalCredit), 2, ',', '.') }})
                        </span>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        @if(!$isBalanced) disabled @endif
                        class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Simpan Jurnal
                </button>
            </div>
        </div>
    </form>
</div>
