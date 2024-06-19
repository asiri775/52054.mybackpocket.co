<?php

use App\Helpers\ParserHelper;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('imap', function () {
    ParserHelper::run();
})->describe('Running IMAP Parsing');

Artisan::command('fixtures', function () {
    // DB::table('transactions')
    //     ->where('total', 'LIKE', '-%')
    //     ->update([
    //         'total' => DB::raw("REPLACE(total, '-', '')")
    //     ]);
})->describe('Running IMAP Parsing');
