<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jurnal Umum</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1f2937; }
        h1 { margin-bottom: 4px; text-align: center; font-size: 18px; }
        .subtitle { margin: 0 0 20px; text-align: center; color: #6b7280; }
        .journal { margin-bottom: 24px; page-break-inside: avoid; }
        .journal-header { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #d1d5db; text-align: left; }
        th { background: #f3f4f6; }
        .number { text-align: right; white-space: nowrap; }
    </style>
</head>
<body>
    <h1>Jurnal Umum</h1>
    <p class="subtitle">Dicetak {{ now()->format('d/m/Y H:i') }}</p>

    @forelse($journals as $journal)
        <section class="journal">
            <div class="journal-header">
                <strong>{{ $journal->entry_number }}</strong>
                · {{ $journal->date?->format('d/m/Y') }}
                · {{ $journal->branch?->name }}
                <br>
                {{ $journal->description }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Akun</th>
                        <th>Keterangan</th>
                        <th class="number">Debit (Rp)</th>
                        <th class="number">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journal->items as $item)
                        <tr>
                            <td>{{ $item->account?->code }} · {{ $item->account?->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="number">{{ number_format((float) $item->debit, 2, ',', '.') }}</td>
                            <td class="number">{{ number_format((float) $item->credit, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="number">{{ number_format((float) $journal->items->sum('debit'), 2, ',', '.') }}</th>
                        <th class="number">{{ number_format((float) $journal->items->sum('credit'), 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </section>
    @empty
        <p>Tidak ada jurnal untuk filter yang dipilih.</p>
    @endforelse
</body>
</html>
