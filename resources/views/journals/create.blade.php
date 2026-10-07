@extends('layouts.app')
@section('title', 'Entri Jurnal')
@section('page-title', 'Entri jurnal baru')
@section('page-subtitle', 'Catat transaksi dengan total debit dan kredit yang seimbang.')
@section('content')
<div class="mb-5"><a href="{{ route('journals.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-800">← Kembali ke jurnal</a></div>
@livewire('journal-form')
@endsection
