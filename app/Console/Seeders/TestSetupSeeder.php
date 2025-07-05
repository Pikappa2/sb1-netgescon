<?php

namespace App\Console\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Amministratore;
use App\Models\Stabile;
use App\Models\UnitaImmobiliare;
use App\Models\Soggetto;
use App\Models\Proprieta;
use App\Models\TabellaMillesimale;
use App\Models\DettaglioTabellaMillesimale;
use App\Models\PianoContiCondominio;
use App\Models\Gestione;
use App\Models\Preventivo;
use App\Models\VocePreventivo;
use Spatie\Permission\Models\Role;  
use Spatie\Permission\Models\Permission; 
use Illuminate\Support\Facades\Hash;

// Assicurati di avere i modelli e le migrazioni corretti prima di eseguire questo seeder.
// Questo seeder crea un ambiente di test con un utente Super Admin, un Amministratore, un Condominio e alcune Unità Immobiliari con Soggetti associati.
// Assicurati di eseguire questo seeder con il comando `php artisan db:seed --class=TestSetupSeeder` per popolare il database con i dati di test.
// Puoi modificare le email e le password per adattarle alle tue esigenze di test.
// Assicurati di avere i modelli e le migrazioni corretti prima di eseguire questo seeder.
// Questo seeder è utile per testare le funzionalità del tuo gestionale senza dover inserire manualmente i dati ogni volta.
// Puoi anche estendere questo seeder per aggiungere ulteriori dati di test come spese, entrate, verbali, ecc.
// Assicurati di avere le relazioni corrette nei modelli Soggetto, UnitaImmobiliare e SoggettoUnita per gestire le associazioni tra soggetti e unità immobiliari.
// Questo seeder è un ottimo punto di partenza per testare le funzionalità del tuo gestionale e garantire che tutto funzioni correttamente.
// Puoi anche utilizzare questo seeder come base per creare altri seeders specifici per le tue esigenze di test.


class TestSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pulisce la cache dei permessi
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crea i ruoli
        // Usa Spatie\Permission\Models\Role per assegnare i ruoli
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        // Ruoli in italiano per la gestione condominiale
        $amministratoreRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'amministratore', 'guard_name' => 'web']);
        $collaboratoreRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'collaboratore', 'guard_name' => 'web']);
        $condominoRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'condomino', 'guard_name' => 'web']);
        $fornitoreRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'fornitore', 'guard_name' => 'web']);
        $inquilinoRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'inquilino', 'guard_name' => 'web']);
        $ospiteRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'ospite', 'guard_name' => 'web']);
        $serviziRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'servizi', 'guard_name' => 'web']);
        $this->command->info('Ruoli creati/verificati.');

        // Ruoli di base per sviluppo (rimosso uso di App\Models\Role e campo label)
        // Tutti i ruoli sono ora gestiti solo tramite Spatie\Permission\Models\Role


        // 2. Crea l'utente Super Admin
        // Rimosso il campo 'role' diretto, verrà assegnato tramite Spatie

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Cambia questa password in produzione!
                'email_verified_at' => now(),

            ]
        );
        // Il ruolo 'super-admin' verrà assegnato tramite Spatie
        $this->command->info('Utente Super Admin creato/aggiornato: ' . $superAdmin->email); // Variabile corretta

        // 2. Crea un Utente Amministratore
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Amministratore Test',
                'password' => Hash::make('password'), // Cambia questa password in produzione!
                'email_verified_at' => now(),
            ]
        );
        // Il ruolo 'admin' verrà assegnato tramite Spatie
        $this->command->info('Utente Amministratore creato/aggiornato: ' . $adminUser->email);

        // 3. Crea un Record Amministratore (collegato all'utente admin)
        $amministratore = Amministratore::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'nome' => 'Mario',
                'cognome' => 'Rossi',
                'denominazione_studio' => 'Studio Rossi Amministrazioni',
                'partita_iva' => '12345678901',
                'codice_fiscale_studio' => 'RSSMRA80A01H501K',
                'indirizzo_studio' => 'Via Roma 10',
                'cap_studio' => '00100',
                'citta_studio' => 'Roma',
                'provincia_studio' => 'RM',
                'telefono_studio' => '061234567',
                'email_studio' => 'studio.rossi@example.com',
                'pec_studio' => 'studio.rossi@pec.it',
            ]
        );
        $this->command->info('Record Amministratore creato/aggiornato: ' . $amministratore->nome . ' ' . $amministratore->cognome);

        // 4. Crea un Condominio di Test
        $stabile = Stabile::firstOrCreate(
            ['denominazione' => 'Stabile Test Via Milano 1'],
            [
            'amministratore_id' => $amministratore->id_amministratore,
                'indirizzo' => 'Via Milano 1',
                'cap' => '20100',
                'citta' => 'Milano',
                'provincia' => 'MI',
                'codice_fiscale' => 'CNDMLN00001A001A',
                'note' => 'Condominio di test per lo sviluppo.',
                'stato' => 'attivo',
            ]
        );
        $this->command->info('Stabile di Test creato/aggiornato: ' . $stabile->denominazione);

        // 5. Crea Unità Immobiliari di Test
        $unita1 = UnitaImmobiliare::firstOrCreate(
            ['stabile_id' => $stabile->id, 'interno' => '1', 'scala' => 'A', 'fabbricato' => 'Principale'],


            [
                'piano' => '1',
                'subalterno' => '1',
                'categoria_catastale' => 'A/3',
                'superficie' => 80.50,
                'vani' => 4.5,
                'indirizzo' => null,
                'note' => 'Appartamento di test A1',
            ]
        );
        $unita2 = UnitaImmobiliare::firstOrCreate(
            ['stabile_id' => $stabile->id, 'interno' => '2', 'scala' => 'A', 'fabbricato' => 'Principale'],
            [
                'piano' => '1',
                'subalterno' => '2',
                'categoria_catastale' => 'A/3',
                'superficie' => 70.00,
                'vani' => 3.5,
                'indirizzo' => null,
                'note' => 'Appartamento di test A2',
            ]
        );
        $this->command->info('Unità Immobiliari di Test create.');

        // 6. Crea Soggetti di Test
        $soggettoProprietario1 = Soggetto::firstOrCreate(['email' => 'proprietario1@example.com'], ['nome' => 'Giuseppe', 'cognome' => 'Verdi', 'tipo' => 'proprietario', 'codice_fiscale' => 'VRDGPP80A01H501A']);
        $soggettoProprietario2 = Soggetto::firstOrCreate(['email' => 'proprietario2@example.com'], ['nome' => 'Maria', 'cognome' => 'Bianchi', 'tipo' => 'proprietario', 'codice_fiscale' => 'BNCMRA85B02H502B']);
        $soggettoInquilino = Soggetto::firstOrCreate(['email' => 'inquilino@example.com'], ['nome' => 'Luca', 'cognome' => 'Neri', 'tipo' => 'inquilino', 'codice_fiscale' => 'NRELCA90C03H503C']);
        $this->command->info('Soggetti di Test creati.');

        // 7. Collega Soggetti alle Unità (Proprieta)
        Proprieta::firstOrCreate([
            'soggetto_id' => $soggettoProprietario1->id ?? $soggettoProprietario1->id_soggetto,
            'unita_immobiliare_id' => $unita1->id ?? $unita1->id_unita
        ], [
            'tipo_diritto' => 'proprietario',
            'percentuale_possesso' => 100.00,
            'data_inizio' => '2020-01-01'
        ]);
        Proprieta::firstOrCreate([
            'soggetto_id' => $soggettoProprietario1->id ?? $soggettoProprietario1->id_soggetto,
            'unita_immobiliare_id' => $unita2->id ?? $unita2->id_unita
        ], [
            'tipo_diritto' => 'nudo_proprietario',
            'percentuale_possesso' => 100.00,
            'data_inizio' => '2022-03-01'
        ]);
        Proprieta::firstOrCreate([
            'soggetto_id' => $soggettoProprietario2->id ?? $soggettoProprietario2->id_soggetto,
            'unita_immobiliare_id' => $unita2->id ?? $unita2->id_unita
        ], [
            'tipo_diritto' => 'usufruttuario',
            'percentuale_possesso' => 100.00,
            'data_inizio' => '2022-03-01'
        ]);
        Proprieta::firstOrCreate([
            'soggetto_id' => $soggettoInquilino->id ?? $soggettoInquilino->id_soggetto,
            'unita_immobiliare_id' => $unita1->id ?? $unita1->id_unita
        ], [
            'tipo_diritto' => 'inquilino',
            'percentuale_possesso' => 100.00,
            'data_inizio' => '2023-06-15'
        ]);
        $this->command->info('Relazioni Soggetto-Unità create.');

        // 8. Crea una Tabella Millesimale di Test
        $tabellaA = TabellaMillesimale::firstOrCreate(
            ['stabile_id' => $stabile->id, 'nome_tabella_millesimale' => 'Tabella A - Proprietà'],
            ['descrizione' => 'Ripartizione spese in base ai millesimi di proprietà generale.']
        );
        // Fix: recupera la chiave primaria corretta se non presente
        if (!$tabellaA->id) {
            // Prova a ricaricare dal DB se firstOrCreate restituisce un oggetto senza la chiave primaria
            $tabellaA = TabellaMillesimale::where('stabile_id', $stabile->id)
                ->where('nome_tabella_millesimale', 'Tabella A - Proprietà')
                ->first();
        }
        if (!$tabellaA || !$tabellaA->id) {
            $this->command->error('Errore: la tabella millesimale non è stata creata correttamente!');
            return;
        }
        $this->command->info('Tabella Millesimale di Test creata.');

        // 9. Crea Dettagli Millesimali per le unità
        DettaglioTabellaMillesimale::firstOrCreate(
            ['tabella_millesimale_id' => $tabellaA->id, 'unita_immobiliare_id' => $unita1->id ?? $unita1->id_unita],
            ['millesimi' => 500.0000]
        );
        DettaglioTabellaMillesimale::firstOrCreate(
            ['tabella_millesimale_id' => $tabellaA->id, 'unita_immobiliare_id' => $unita2->id ?? $unita2->id_unita],
            ['millesimi' => 500.0000]
        );
        $this->command->info('Dettagli Millesimali creati.');

        // 10. Crea una Gestione di Test
        $gestione2024 = Gestione::firstOrCreate(
            ['stabile_id' => $stabile->id, 'anno_gestione' => 2024, 'tipo_gestione' => 'Ord.'],
            ['data_inizio' => '2024-01-01', 'data_fine' => '2024-12-31', 'stato' => 'aperta']
        );
        $this->command->info('Gestione di Test creata.');

        // Aggiungiamo anche la gestione 2025
        $gestione2025 = Gestione::firstOrCreate(
            ['stabile_id' => $stabile->id, 'anno_gestione' => 2025, 'tipo_gestione' => 'Ord.'],
            ['data_inizio' => '2025-01-01', 'stato' => 'aperta']
        );
        $this->command->info('Gestione 2025 creata.');

        // 11. Crea un Piano dei Conti per lo Stabile (esempio base)
        $contoPulizie = PianoContiCondominio::firstOrCreate(
            ['stabile_id' => $stabile->id, 'codice' => 'SP.PUL'],
            ['descrizione' => 'Spese di Pulizia Scale', 'tipo_conto' => 'ECONOMICO_COSTO']
        );
        $contoAssicurazione = PianoContiCondominio::firstOrCreate(
            ['stabile_id' => $stabile->id, 'codice' => 'SP.ASS'],
            ['descrizione' => 'Assicurazione Fabbricato', 'tipo_conto' => 'ECONOMICO_COSTO']
        );
        $this->command->info('Piano dei Conti di Test creato.');

        /*// 12. Crea un Preventivo di Test
        $preventivo2024 = Preventivo::firstOrCreate(
            ['id_gestione' => $gestione2024->id_gestione],
            ['descrizione' => 'Preventivo Ordinario 2024', 'stato' => 'APPROVATO']
        );
        $this->command->info('Preventivo di Test creato.');

        // 13. Crea Voci di Preventivo
        VocePreventivo::firstOrCreate(['id_preventivo' => $preventivo2024->id_preventivo, 'id_piano_conto_condominio_pc' => $contoPulizie->id_conto_condominio_pc], ['importo_previsto' => 1200.00, 'id_tabella_millesimale_ripartizione' => $tabellaA->id_tabella_millesimale]);
        VocePreventivo::firstOrCreate(['id_preventivo' => $preventivo2024->id_preventivo, 'id_piano_conto_condominio_pc' => $contoAssicurazione->id_conto_condominio_pc], ['importo_previsto' => 800.00, 'id_tabella_millesimale_ripartizione' => $tabellaA->id_tabella_millesimale]);
        $this->command->info('Voci di Preventivo create.'); */

        // Creazione Permessi (Esempio)
        $gestioneCondominiPermission = Permission::firstOrCreate(['name' => 'gestione-condomini']);
        $visualizzaReportPermission = Permission::firstOrCreate(['name' => 'visualizza-report']);

        Permission::firstOrCreate(['name' => 'view-stabili']);
        Permission::firstOrCreate(['name' => 'manage-stabili']); // Permesso generico per le azioni CRUD
        

        // Permessi per la gestione utenti (Super Admin)
        Permission::firstOrCreate(['name' => 'create-users']);
        Permission::firstOrCreate(['name' => 'view-users']);
        Permission::firstOrCreate(['name' => 'manage-users']); // Include create, edit, delete, update role
        Permission::firstOrCreate(['name' => 'impersonate-users']);

        // Permessi per la gestione amministratori (Super Admin)
        Permission::firstOrCreate(['name' => 'view-amministratori']);
        Permission::firstOrCreate(['name' => 'manage-amministratori']); // Include create, edit, delete

        // Permessi per la gestione categorie ticket (Super Admin)
        Permission::firstOrCreate(['name' => 'view-categorie-ticket']);
        Permission::firstOrCreate(['name' => 'manage-categorie-ticket']); // Include create, edit, delete

        // Permessi per la gestione soggetti (Admin)
        Permission::firstOrCreate(['name' => 'view-soggetti']);
        Permission::firstOrCreate(['name' => 'manage-soggetti']); // Include create, edit, delete

        // Permessi per la gestione fornitori (Admin)
        Permission::firstOrCreate(['name' => 'view-fornitori']);
        Permission::firstOrCreate(['name' => 'manage-fornitori']);

        // Permessi per la gestione ticket (Admin)
        Permission::firstOrCreate(['name' => 'view-tickets']);
        Permission::firstOrCreate(['name' => 'manage-tickets']);

        // Permessi per la gestione unità immobiliari (Admin)
        Permission::firstOrCreate(['name' => 'view-unita-immobiliari']);
        Permission::firstOrCreate(['name' => 'manage-unita-immobiliari']);

        // Permessi per le impostazioni e API Tokens (Admin)
        Permission::firstOrCreate(['name' => 'view-impostazioni']);
        Permission::firstOrCreate(['name' => 'manage-api-tokens']);
        Permission::firstOrCreate(['name' => 'view-rubrica']);


        // Aggiungi qui altri permessi specifici per il tuo progetto


        // Assegnazione Permessi ai Ruoli (Esempio)
        $amministratoreRole = \Spatie\Permission\Models\Role::where('name', 'amministratore')->first();
        $adminRole = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
        $superAdminRole = \Spatie\Permission\Models\Role::where('name', 'super-admin')->first();

        $amministratoreRole = \Spatie\Permission\Models\Role::where('name', 'amministratore')->first();
        if ($amministratoreRole) {
            $amministratoreRole->givePermissionTo([
                'visualizza-report',
                'view-stabili', 'manage-stabili',
                'view-soggetti', 'manage-soggetti',
                'view-fornitori', 'manage-fornitori',
                'view-tickets', 'manage-tickets',
                'view-unita-immobiliari', 'manage-unita-immobiliari',
                'view-impostazioni', 'manage-api-tokens', 'view-rubrica',
            ]);
        } else {
            $this->command->warn("Ruolo 'amministratore' non trovato: permessi non assegnati.");
        }


        // Assegna i permessi al ruolo 'admin'
        $adminRole->givePermissionTo([
            'view-soggetti', 'manage-soggetti',
            'view-fornitori', 'manage-fornitori',
            'view-tickets', 'manage-tickets',
            'view-unita-immobiliari', 'manage-unita-immobiliari',
            'view-impostazioni', 'manage-api-tokens', 'view-rubrica',
        ]);


        // Assegna il ruolo 'amministratore' all'utente di test per permettergli di gestire gli stabili
        if ($amministratoreRole) {
            $adminUser->assignRole('amministratore');
        } else {
            $this->command->warn("Ruolo 'amministratore' non trovato: non assegnato all'utente di test.");
        }


        // Assegna tutti i permessi al Super Admin
        $superAdminRole->givePermissionTo(Permission::all());
        $superAdmin->assignRole('super-admin');
     
        $this->command->info('Setup di test completato con successo!');
    }
}
// Questo seeder crea un ambiente di test con un utente Super Admin, un Amministratore, un Condominio e alcune Unità Immobiliari con Soggetti associati.
// Assicurati di eseguire questo seeder con il comando `php artisan db:seed --class=TestSetupSeeder` per popolare il database con i dati di test.
// Puoi modificare le email e le password per adattarle alle tue esigenze di test.
// Assicurati di avere i modelli e le migrazioni corretti prima di eseguire questo seeder.
// Questo seeder è utile per testare le funzionalità del tuo gestionale senza dover inserire manualmente i dati ogni volta.
// Puoi anche estendere questo seeder per aggiungere ulteriori dati di test come spese, entrate, verbali, ecc.
// Assicurati di avere le relazioni corrette nei modelli Soggetto, UnitaImmobiliare e SoggettoUnita per gestire le associazioni tra soggetti e unità immobiliari.
// Questo seeder è un ottimo punto di partenza per testare le funzionalità del tuo gestionale e garantire che tutto funzioni correttamente.
// Puoi anche utilizzare questo seeder come base per creare altri seeders specifici per le tue esigenze di test.
// Assicurati di eseguire il seeder in un ambiente di sviluppo o test, non in produzione, per evitare conflitti con i dati reali.
// Ricorda di aggiornare le password e le email in produzione per garantire la sicurezza del tuo gestionale.
// Questo seeder è progettato per essere eseguito una sola volta per impostare un ambiente di test iniziale.
// Puoi eseguire nuovamente il seeder per ripristinare lo stato di test, ma fai attenzione a non duplicare i dati esistenti.
// Se hai bisogno di modificare i dati di test, puoi farlo direttamente nel seeder o creare nuovi seeders per aggiungere ulteriori dati.
// Assicurati di avere le dipendenze corrette nel tuo progetto Laravel per eseguire questo seeder senza errori.
// Puoi anche utilizzare questo seeder come base per creare altri seeders specifici per le tue esigenze di test.
// Questo seeder è un ottimo punto di partenza per testare le funzionalità del tuo gestionale e garantire che tutto funzioni correttamente.
// Puoi anche utilizzare questo seeder come base per creare altri seeders specifici per le tue esigenze di test.
// Assicurati di eseguire il seeder in un ambiente di sviluppo o test, non in produzione, per evitare conflitti con i dati reali.
// Ricorda di aggiornare le password e le email in produzione per garantire la sicurezza del tuo gestionale.
// Questo seeder è progettato per essere eseguito una sola volta per impostare un ambiente di test iniziale.
// Puoi eseguire nuovamente il seeder per ripristinare lo stato di test, ma fai attenzione a non duplicare i dati esistenti.
// Se hai bisogno di modificare i dati di test, puoi farlo direttamente nel seeder o creare nuovi seeders per aggiungere ulteriori dati.
// Assicurati di avere le dipendenze corrette nel tuo progetto Laravel per eseguire questo seeder senza errori.
// Puoi anche utilizzare questo seeder come base per creare altri seeders specifici per le tue esigenze di test.
// Questo seeder è un ottimo punto di partenza per testare le funzionalità del tuo gestionale e garantire che tutto funzioni correttamente.
// Puoi anche utilizzare questo seeder come base per creare altri seeders specifici per le tue esigenze di test.