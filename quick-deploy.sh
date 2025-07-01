#!/bin/bash

# 🚀 Script Quick Deploy per Condominio Management
# Esegui con: chmod +x quick-deploy.sh && ./quick-deploy.sh

echo "🚀 AVVIO DEPLOY CONDOMINIO MANAGEMENT"
echo "===================================="

# 1. Verifica prerequisiti
echo "📋 Verifica prerequisiti..."
command -v php >/dev/null 2>&1 || { echo "❌ PHP non installato"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "❌ Composer non installato"; exit 1; }
command -v npm >/dev/null 2>&1 || { echo "❌ NPM non installato"; exit 1; }

# 2. Setup ambiente
echo "🔧 Setup ambiente..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ File .env creato"
fi

# 3. Installa dipendenze
echo "📦 Installazione dipendenze..."
composer install --optimize-autoloader
npm install

# 4. Genera chiave
echo "🔑 Generazione chiave applicazione..."
php artisan key:generate

# 5. Database setup
echo "🗄️ Setup database..."
read -p "Vuoi eseguire le migrazioni? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate
    echo "✅ Migrazioni eseguite"
fi

# 6. Seed dati
read -p "Vuoi inserire dati di esempio? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed
    echo "✅ Dati di esempio inseriti"
fi

# 7. Compila assets
echo "🎨 Compilazione assets..."
npm run build

# 8. Ottimizzazioni
echo "⚡ Ottimizzazioni..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Permessi
echo "🔐 Impostazione permessi..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# 10. Crea super admin
echo "👤 Creazione Super Admin..."
read -p "Email super admin: " email
read -s -p "Password: " password
echo

php artisan tinker --execute="
\$user = App\Models\User::create([
    'name' => 'Super Admin',
    'email' => '$email',
    'password' => bcrypt('$password'),
    'email_verified_at' => now()
]);
\$user->assignRole('super-admin');
echo 'Super Admin creato con successo!';
"

# 11. Avvia server
echo "🌐 Avvio server di sviluppo..."
echo "✅ DEPLOY COMPLETATO!"
echo "🔗 Accedi a: http://localhost:8000"
echo "👤 Email: $email"
echo "🔑 Password: [quella inserita]"
echo ""
echo "🚀 Avvio server..."
php artisan serve