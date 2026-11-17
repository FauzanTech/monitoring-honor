<?php

use App\Http\Controllers\TemplateController;
use App\Livewire\Kegiatan\Index as KegiatanIndex;
use App\Livewire\Kegiatan\Show as KegiatanShow;
use App\Livewire\Mitra\Index as MitraIndex;
use App\Livewire\Mitra\Show as MitraShow;
use App\Livewire\Monitoring;
use App\Livewire\PengaturanLimit;
use Illuminate\Support\Facades\Route;

Route::get('/', Monitoring::class)->name('monitoring');

Route::get('/kegiatan', KegiatanIndex::class)->name('kegiatan.index');
Route::get('/kegiatan/{kegiatan}', KegiatanShow::class)->name('kegiatan.show');
Route::get('/kegiatan/{kegiatan}/template', [TemplateController::class, 'penugasan'])->name('kegiatan.template');

Route::get('/mitra', MitraIndex::class)->name('mitra.index');
Route::get('/mitra/template', [TemplateController::class, 'mitra'])->name('mitra.template');
Route::get('/mitra/{mitra}', MitraShow::class)->name('mitra.show');

Route::get('/pengaturan', PengaturanLimit::class)->name('pengaturan');
