<?php

/**
 * Script di integrazione per pagine esistenti
 * Esegui con: php integration-script.php
 */

class PageIntegrator
{
    private $existingPagesPath;
    private $targetPath;
    
    public function __construct($existingPagesPath = './existing-pages', $targetPath = './resources/views')
    {
        $this->existingPagesPath = $existingPagesPath;
        $this->targetPath = $targetPath;
    }
    
    public function integrate()
    {
        echo "🚀 Avvio integrazione pagine esistenti...\n";
        
        // 1. Analizza pagine esistenti
        $this->analyzeExistingPages();
        
        // 2. Crea mapping
        $mapping = $this->createMapping();
        
        // 3. Migra pagine
        $this->migratePages($mapping);
        
        // 4. Aggiorna rotte
        $this->updateRoutes($mapping);
        
        echo "✅ Integrazione completata!\n";
    }
    
    private function analyzeExistingPages()
    {
        echo "📁 Analisi pagine esistenti...\n";
        
        if (!is_dir($this->existingPagesPath)) {
            echo "❌ Cartella pagine esistenti non trovata: {$this->existingPagesPath}\n";
            return;
        }
        
        $files = glob($this->existingPagesPath . '/**/*.{html,php,blade.php}', GLOB_BRACE);
        
        foreach ($files as $file) {
            echo "📄 Trovata: " . basename($file) . "\n";
        }
    }
    
    private function createMapping()
    {
        return [
            'dashboard.html' => 'admin/dashboard.blade.php',
            'stabili.html' => 'admin/stabili/index.blade.php',
            'tickets.html' => 'admin/tickets/index.blade.php',
            'documenti.html' => 'admin/documenti/index.blade.php',
            'contabilita.html' => 'admin/contabilita/index.blade.php',
            // Aggiungi altri mapping qui
        ];
    }
    
    private function migratePages($mapping)
    {
        echo "🔄 Migrazione pagine...\n";
        
        foreach ($mapping as $source => $target) {
            $sourcePath = $this->existingPagesPath . '/' . $source;
            $targetPath = $this->targetPath . '/' . $target;
            
            if (file_exists($sourcePath)) {
                // Crea directory se non esiste
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                // Leggi contenuto e adatta
                $content = file_get_contents($sourcePath);
                $adaptedContent = $this->adaptContent($content);
                
                // Salva file adattato
                file_put_contents($targetPath, $adaptedContent);
                echo "✅ Migrata: {$source} → {$target}\n";
            }
        }
    }
    
    private function adaptContent($content)
    {
        // Adatta il contenuto per Laravel Blade
        
        // 1. Aggiungi layout Blade
        $bladeContent = "@extends('layouts.app')\n\n";
        $bladeContent .= "@section('content')\n";
        
        // 2. Estrai body content (rimuovi html, head, body tags)
        $content = preg_replace('/<html[^>]*>/', '', $content);
        $content = preg_replace('/<\/html>/', '', $content);
        $content = preg_replace('/<head>.*?<\/head>/s', '', $content);
        $content = preg_replace('/<body[^>]*>/', '', $content);
        $content = preg_replace('/<\/body>/', '', $content);
        
        // 3. Converti link statici in route Laravel
        $content = preg_replace('/href="([^"]*\.html)"/', 'href="{{ route(\'admin.dashboard\') }}"', $content);
        
        // 4. Aggiungi CSRF token ai form
        $content = preg_replace('/<form([^>]*)>/', '<form$1>@csrf', $content);
        
        $bladeContent .= $content;
        $bladeContent .= "\n@endsection";
        
        return $bladeContent;
    }
    
    private function updateRoutes($mapping)
    {
        echo "🛣️ Aggiornamento rotte...\n";
        
        $routeFile = './routes/web.php';
        $routes = file_get_contents($routeFile);
        
        // Aggiungi nuove rotte per le pagine migrate
        $newRoutes = "\n// Rotte pagine migrate\n";
        foreach ($mapping as $source => $target) {
            $routeName = str_replace('.html', '', $source);
            $viewName = str_replace('.blade.php', '', $target);
            $newRoutes .= "Route::get('/{$routeName}', function() { return view('{$viewName}'); })->name('{$routeName}');\n";
        }
        
        // Aggiungi prima della chiusura del gruppo middleware
        $routes = str_replace('});', $newRoutes . '});', $routes);
        
        file_put_contents($routeFile, $routes);
        echo "✅ Rotte aggiornate\n";
    }
}

// Esegui integrazione
$integrator = new PageIntegrator();
$integrator->integrate();