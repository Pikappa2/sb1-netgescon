<aside class="submenu">
    @php
        // Esempio di sottomenu dinamico in base al menu selezionato (da implementare JS/Livewire)
        $submenu = [
            'dashboard' => [
                ['label' => __('menu.dashboard_overview'), 'route' => 'dashboard'],
            ],
            'stabili.index' => [
                ['label' => __('menu.lista_stabili'), 'route' => 'stabili.index'],
                ['label' => __('menu.nuovo_stabile'), 'route' => 'stabili.create'],
            ],
            'soggetti.index' => [
                ['label' => __('menu.lista_soggetti'), 'route' => 'soggetti.index'],
                ['label' => __('menu.nuovo_soggetto'), 'route' => 'soggetti.create'],
            ],
            'contabilita.index' => [
                ['label' => __('menu.piano_conti'), 'route' => 'contabilita.piano_conti'],
                ['label' => __('menu.movimenti'), 'route' => 'contabilita.movimenti'],
            ],
            'impostazioni' => [
                ['label' => __('menu.utenti'), 'route' => 'impostazioni.utenti'],
                ['label' => __('menu.ruoli'), 'route' => 'impostazioni.ruoli'],
            ],
        ];
        // Da implementare: $activeMenu = ...
        $activeMenu = 'dashboard';
    @endphp
    <ul class="nav flex-column mt-4">
        @foreach($submenu[$activeMenu] ?? [] as $item)
            <li class="nav-item">
                <a href="{{ route($item['route']) }}" class="nav-link">{{ $item['label'] }}</a>
            </li>
        @endforeach
    </ul>
</aside>
