# 🚀 Guida Completa Deploy e Integrazione

## 📋 CHECKLIST PRE-DEPLOY

### 1. Verifica File Essenziali
```bash
# Controlla che tutti i file siano presenti
ls -la
ls app/Models/
ls app/Http/Controllers/
ls database/migrations/
ls resources/views/
```

### 2. Configurazione Environment
```bash
# Copia e configura .env
cp .env.example .env

# Genera chiave applicazione
php artisan key:generate
```

### 3. Database Setup
```bash
# Esegui le migrazioni
php artisan migrate

# Seed dei dati iniziali
php artisan db:seed

# Crea i ruoli e permessi
php artisan permission:create-role super-admin
php artisan permission:create-role admin
php artisan permission:create-role amministratore
php artisan permission:create-role condomino
```

## 🔧 INTEGRAZIONE PAGINE ESISTENTI

### Opzione 1: Integrazione Manuale
Se hai già delle pagine sviluppate, puoi integrarle così:

1. **Copia le tue views esistenti** in `resources/views/`
2. **Adatta i layout** per usare il nostro sistema
3. **Aggiorna le rotte** in `routes/web.php`

### Opzione 2: Migrazione Automatica
```bash
# Script per migrare le tue pagine esistenti
php artisan make:command MigrateExistingPages
```

## 🌐 DEPLOY OPZIONI

### Opzione A: Deploy Locale (Sviluppo)
```bash
# Installa dipendenze
composer install
npm install

# Compila assets
npm run build

# Avvia server locale
php artisan serve
```

### Opzione B: Deploy su Hosting Condiviso
1. **Upload files** via FTP/SFTP
2. **Configura database** nel pannello hosting
3. **Imposta .env** con credenziali hosting
4. **Esegui migrazioni** via SSH o pannello

### Opzione C: Deploy su VPS/Cloud
```bash
# Clona repository
git clone [tuo-repo]

# Setup ambiente
sudo apt update
sudo apt install php8.2 mysql-server nginx

# Configura Nginx
sudo nano /etc/nginx/sites-available/condominio
```

### Opzione D: Deploy su Netlify (Frontend)
```bash
# Build per produzione
npm run build

# Deploy automatico
# Collega repository GitHub a Netlify
```

## 📁 STRUTTURA FILE FINALE

```
condominio-management/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   ├── SuperAdmin/
│   │   └── Condomino/
│   ├── Models/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── superadmin/
│   │   ├── condomino/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── routes/
├── public/
└── storage/
```

## 🔐 CONFIGURAZIONE SICUREZZA

### 1. Permessi File
```bash
# Imposta permessi corretti
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
```

### 2. Configurazione .env Produzione
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tuodominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=condominio_db
DB_USERNAME=username
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tua-email@gmail.com
MAIL_PASSWORD=tua-password
```

## 🎯 PRIMO ACCESSO E TEST

### 1. Crea Super Admin
```bash
php artisan tinker

# Nel tinker:
$user = App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'admin@tuodominio.com',
    'password' => bcrypt('password123'),
    'email_verified_at' => now()
]);

$user->assignRole('super-admin');
```

### 2. Test Funzionalità
1. **Login** come super-admin
2. **Crea amministratore** dal pannello
3. **Crea stabile** di test
4. **Aggiungi unità immobiliari**
5. **Crea soggetti/condomini**
6. **Test ticket** e documenti

## 📊 MONITORAGGIO E MANUTENZIONE

### 1. Log Monitoring
```bash
# Monitora log errori
tail -f storage/logs/laravel.log

# Log personalizzati
tail -f storage/logs/assemblee.log
tail -f storage/logs/bilanci.log
```

### 2. Backup Automatico
```bash
# Script backup database
php artisan backup:run

# Backup files
rsync -av /path/to/project/ /backup/location/
```

### 3. Aggiornamenti
```bash
# Update dipendenze
composer update
npm update

# Nuove migrazioni
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🚀 LANCIO PRODUZIONE

### Checklist Finale
- [ ] Database configurato e migrato
- [ ] .env produzione configurato
- [ ] SSL certificato installato
- [ ] Backup automatico attivo
- [ ] Monitoring errori attivo
- [ ] Email SMTP configurato
- [ ] Permessi file corretti
- [ ] Cache ottimizzata
- [ ] Super admin creato
- [ ] Test completo funzionalità

### Performance Optimization
```bash
# Ottimizza per produzione
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## 📞 SUPPORTO POST-DEPLOY

### Problemi Comuni
1. **Errore 500**: Controlla log Laravel
2. **Database connection**: Verifica credenziali .env
3. **Permessi**: Controlla ownership files
4. **Assets mancanti**: Esegui `npm run build`

### Contatti Supporto
- Email: support@tuodominio.com
- Documentazione: docs.tuodominio.com
- GitHub Issues: github.com/tuo-repo/issues