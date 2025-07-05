<?php
namespace App\Console\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImpostazioniSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('impostazioni')->insertOrIgnore([
            [
                'chiave' => 'sidebar_bg',
                'valore' => '#fde047',
                'descrizione' => 'Colore di sfondo sidebar (giallo)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chiave' => 'sidebar_text',
                'valore' => '#1e293b',
                'descrizione' => 'Colore testo sidebar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chiave' => 'sidebar_accent',
                'valore' => '#6366f1',
                'descrizione' => 'Colore accento sidebar (indigo)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chiave' => 'sidebar_bg_dark',
                'valore' => '#23272e',
                'descrizione' => 'Colore sidebar dark mode',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chiave' => 'sidebar_text_dark',
                'valore' => '#f1f5f9',
                'descrizione' => 'Colore testo sidebar dark mode',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chiave' => 'sidebar_accent_dark',
                'valore' => '#fbbf24',
                'descrizione' => 'Colore accento sidebar dark mode',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
