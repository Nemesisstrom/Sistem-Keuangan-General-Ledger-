<div class="mx-auto max-w-6xl space-y-5">
    @if (session()->has('error'))
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ session('error') }}</div>
    @endif
    @if (session()->has('warning'))
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">{{ session('warning') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><p class="font-semibold">Periksa kembali data jurnal.</p><ul class="mt-1 list-inside list-disc text-xs">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form wire:submit="save" class="space-y-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div><p class="text-[10px] font-bold uppercase tracking-[.17em] text-emerald-700">Informasi utama</p><h2 class="mt-1 text-base font-bold text-slate-900">Header jurnal</h2></div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold text-slate-500">Semua kolom bertanda * wajib</span>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Cabang <span class="text-rose-600">*</span></label>
                    <select wire:model="branch_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700" @if(auth()->check() && auth()->user()->role !== 'superadmin') disabled @endif>
                        <option value="">Pilih cabang</option>
                        @foreach($branches as $branch)<option value="{{ $branch->id }}">{{ $branch->code }} · {{ $branch->name }}</option>@endforeach
                    </select>
                    @error('branch_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Tanggal transaksi <span class="text-rose-600">*</span></label>
                    <input type="date" wire:model="date" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                    @error('date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Keterangan <span class="text-rose-600">*</span></label>
                    <input type="text" wire:model="description" maxlength="255" placeholder="Tujuan atau ringkasan transaksi" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col justify-between gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:px-6">
                <div><p class="text-[10px] font-bold uppercase tracking-[.17em] text-emerald-700">Rincian pembukuan</p><h2 class="mt-1 text-base font-bold text-slate-900">Baris jurnal</h2><p class="mt-1 text-xs text-slate-400">Pastikan setiap baris hanya memiliki nilai debit atau kredit.</p></div>
                <button type="button" wire:click="addItem" class="rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs font-semibold text-emerald-800 transition hover:bg-emerald-100">＋ Tambah baris</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-400"><tr><th class="w-12 px-4 py-3 text-center">#</th><th class="px-3 py-3">Akun</th><th class="px-3 py-3">Keterangan baris</th><th class="w-44 px-3 py-3 text-right">Debit (Rp)</th><th class="w-44 px-3 py-3 text-right">Kredit (Rp)</th><th class="w-14 px-3 py-3"></th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($items as $index => $item)
                            <tr wire:key="journal-item-{{ $index }}" class="align-top">
                                <td class="px-4 py-4 text-center text-xs font-semibold text-slate-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-3 py-3">
                                    <select wire:model="items.{{ $index }}.account_id" class="w-full rounded-lg border-slate-200 text-xs focus:border-emerald-700 focus:ring-emerald-700"><option value="">Pilih akun</option>@foreach($accounts as $account)<option value="{{ $account->id }}">{{ $account->code }} · {{ $account->name }}</option>@endforeach</select>
                                    @error("items.$index.account_id")<p class="mt-1 text-[10px] text-rose-600">{{ $message }}</p>@enderror
                                </td>
                                <td class="px-3 py-3"><input wire:model="items.{{ $index }}.description" maxlength="255" placeholder="Opsional" class="w-full rounded-lg border-slate-200 text-xs focus:border-emerald-700 focus:ring-emerald-700">@error("items.$index.description")<p class="mt-1 text-[10px] text-rose-600">{{ $message }}</p>@enderror</td>
                                <td class="px-3 py-3"><input type="number" min="0" step="0.01" wire:model.live.debounce.250ms="items.{{ $index }}.debit" class="w-full rounded-lg border-slate-200 text-right text-xs tabular-nums focus:border-emerald-700 focus:ring-emerald-700" placeholder="0.00"></td>
                                <td class="px-3 py-3"><input type="number" min="0" step="0.01" wire:model.live.debounce.250ms="items.{{ $index }}.credit" class="w-full rounded-lg border-slate-200 text-right text-xs tabular-nums focus:border-emerald-700 focus:ring-emerald-700" placeholder="0.00"></td>
                                <td class="px-3 py-3 text-center"><button type="button" wire:click="removeItem({{ $index }})" aria-label="Hapus baris" class="rounded-lg p-2 text-slate-300 transition hover:bg-rose-50 hover:text-rose-600">×</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-slate-200 bg-slate-50">
                        <tr><td colspan="3" class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Total transaksi</td><td class="px-3 py-4 text-right text-sm font-bold tabular-nums text-slate-900">Rp {{ number_format($totalDebit, 2, ',', '.') }}</td><td class="px-3 py-4 text-right text-sm font-bold tabular-nums text-slate-900">Rp {{ number_format($totalCredit, 2, ',', '.') }}</td><td></td></tr>
                    </tfoot>
                </table>
            </div>
            <div class="flex flex-col justify-between gap-4 border-t border-slate-100 p-4 sm:flex-row sm:items-center sm:px-6">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full {{ $isBalanced ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                    @if($isBalanced)<span class="text-xs font-semibold text-emerald-700">Seimbang · siap diposting</span>@else<span class="text-xs font-semibold text-amber-700">Belum seimbang · selisih Rp {{ number_format(abs($totalDebit - $totalCredit), 2, ',', '.') }}</span>@endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('journals.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                    <button type="submit" wire:loading.attr="disabled" @disabled(!$isBalanced) class="rounded-xl bg-[#174735] px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#103626] disabled:cursor-not-allowed disabled:opacity-40">
                        <span wire:loading.remove wire:target="save">Simpan & posting jurnal</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </section>
    </form>
</div>
