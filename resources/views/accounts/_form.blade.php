<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="code" class="mb-1.5 block text-xs font-semibold text-slate-600">Kode akun <span class="text-rose-600">*</span></label>
        <input id="code" name="code" value="{{ old('code', $account->code) }}" required maxlength="20" class="w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-700 focus:ring-emerald-700" placeholder="Contoh: 1101">
        @error('code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="name" class="mb-1.5 block text-xs font-semibold text-slate-600">Nama akun <span class="text-rose-600">*</span></label>
        <input id="name" name="name" value="{{ old('name', $account->name) }}" required maxlength="255" class="w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-700 focus:ring-emerald-700" placeholder="Nama akun">
        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="type" class="mb-1.5 block text-xs font-semibold text-slate-600">Kategori akun <span class="text-rose-600">*</span></label>
        <select id="type" name="type" required class="w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-700 focus:ring-emerald-700">
            <option value="">Pilih kategori</option>
            @foreach(['asset' => 'Aset', 'liability' => 'Liabilitas', 'equity' => 'Ekuitas', 'revenue' => 'Pendapatan', 'expense' => 'Beban'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $account->type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="normal_balance" class="mb-1.5 block text-xs font-semibold text-slate-600">Saldo normal <span class="text-rose-600">*</span></label>
        <select id="normal_balance" name="normal_balance" required class="w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-700 focus:ring-emerald-700">
            <option value="">Pilih saldo normal</option>
            <option value="debit" @selected(old('normal_balance', $account->normal_balance) === 'debit')>Debit</option>
            <option value="credit" @selected(old('normal_balance', $account->normal_balance) === 'credit')>Kredit</option>
        </select>
        @error('normal_balance')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="parent_id" class="mb-1.5 block text-xs font-semibold text-slate-600">Akun induk <span class="font-normal text-slate-400">(opsional)</span></label>
        <select id="parent_id" name="parent_id" class="w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-700 focus:ring-emerald-700">
            <option value="">Tanpa akun induk</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" @selected((string) old('parent_id', $account->parent_id) === (string) $parent->id)>{{ $parent->code }} · {{ $parent->name }}</option>
            @endforeach
        </select>
        @error('parent_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="flex items-center pt-6">
        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex cursor-pointer items-center gap-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account->exists ? $account->is_active : true)) class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-700">
            <span><span class="block text-sm font-semibold text-slate-700">Akun aktif</span><span class="block text-xs text-slate-400">Akun nonaktif tidak muncul di input jurnal.</span></span>
        </label>
    </div>
</div>
