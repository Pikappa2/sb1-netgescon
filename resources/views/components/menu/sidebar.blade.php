<nav class="h-full flex flex-col bg-yellow-300 border-r-4 border-indigo-500 py-6 px-2 w-full shadow-xl z-50">
    @php
        $userRoles = auth()->check() ? auth()->user()->getRoleNames()->toArray() : [];
        $panelPrefix = '';
        if (in_array('super-admin', $userRoles)) {
            $panelPrefix = 'superadmin.';
        } elseif (in_array('admin', $userRoles) || in_array('amministratore', $userRoles)) {
            $panelPrefix = 'admin.';
        }
        $mainMenu = [
            [
                'icon' => 'fa-solid fa-home',
                'label' => __('menu.dashboard'),
                'route' => $panelPrefix . 'dashboard',
                'roles' => ['admin', 'super-admin', 'amministratore', 'collaboratore', 'condomino'],
            ],
            [
                'icon' => 'fa-solid fa-building',
                'label' => __('menu.stabili'),
                'route' => $panelPrefix . 'stabili.index',
                'roles' => ['admin', 'super-admin', 'amministratore', 'collaboratore'],
            ],
            [
                'icon' => 'fa-solid fa-users',
                'label' => __('menu.soggetti'),
                'route' => $panelPrefix . 'soggetti.index',
                'roles' => ['admin', 'super-admin', 'amministratore', 'collaboratore'],
            ],
            [
                'icon' => 'fa-solid fa-file-invoice-dollar',
                'label' => __('menu.contabilita'),
                'route' => $panelPrefix . 'contabilita.index',
                'roles' => ['admin', 'super-admin', 'amministratore'],
            ],
            [
                'icon' => 'fa-solid fa-cogs',
                'label' => __('menu.impostazioni'),
                'route' => $panelPrefix . 'impostazioni',
                'roles' => ['admin', 'super-admin', 'amministratore'],
            ],
        ];
    @endphp
    <div class="px-2 pt-3 pb-2" style="background: var(--sidebar-bg); color: var(--sidebar-text); border-bottom: 2px solid var(--sidebar-accent); position:sticky; top:0; z-index:20;">
        <div class="flex flex-col items-center">
            <span class="text-xs text-gray-700 mb-1">{{ $annoAttivo }}/{{ $gestione }}</span>
            <div class="flex items-center gap-2 bg-yellow-200 dark:bg-gray-800 rounded px-2 py-1 shadow" style="min-width: 160px;">
                <div class="flex flex-col justify-center items-center mr-1">
                    <button id="prev-stabile" class="w-6 h-6 flex items-center justify-center rounded bg-indigo-500 text-white hover:bg-indigo-700 text-xs mb-1" title="Stabile precedente">
                        <!-- Tabler icon chevron-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-up" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 15 12 9 18 15" /></svg>
                    </button>
                    <button id="next-stabile" class="w-6 h-6 flex items-center justify-center rounded bg-indigo-500 text-white hover:bg-indigo-700 text-xs" title="Stabile successivo">
                        <!-- Tabler icon chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-down" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 9 12 15 18 9" /></svg>
                    </button>
                </div>
                <button id="current-stabile" class="flex-1 text-base font-semibold text-gray-900 dark:text-gray-100 bg-transparent hover:bg-yellow-300 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-center" title="Cambia stabile">
                    {{ $stabileAttivo }}
                </button>
            </div>
            <button id="toggle-darkmode" class="mt-2 px-2 py-1 rounded bg-gray-800 text-yellow-300 hover:bg-gray-900 text-xs flex items-center gap-1" title="Attiva/disattiva modalità scura">
                <!-- Tabler icon moon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-moon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 .003 .393 .008a9 9 0 1 0 9.599 9.599a7 7 0 0 1 -9.992 -9.607z" /></svg>
                <span>Dark</span>
            </button>
        </div>
    </div>
    <div class="flex-1 flex flex-col gap-2">
        @foreach($mainMenu as $item)
            @if(array_intersect($item['roles'], $userRoles))
                @if(Route::has($item['route']))
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition group">
                        <i class="{{ $item['icon'] }} text-lg group-hover:text-indigo-700"></i>
                        <span class="font-medium">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endif
        @endforeach
    </div>
    <div class="mt-auto pt-4">
        <button id="submenu-toggle" class="w-full flex items-center justify-center text-gray-400 hover:text-indigo-600 transition" title="Mostra/Nascondi menu">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>
    @php
    // Colori personalizzabili e dark mode
    $sidebarBg = impostazione('sidebar_bg', '#fde047');
    $sidebarText = impostazione('sidebar_text', '#1e293b');
    $sidebarAccent = impostazione('sidebar_accent', '#6366f1');
    $sidebarBgDark = impostazione('sidebar_bg_dark', '#23272e');
    $sidebarTextDark = impostazione('sidebar_text_dark', '#f1f5f9');
    $sidebarAccentDark = impostazione('sidebar_accent_dark', '#fbbf24');
    // Recupera stabile attivo (ultimo usato o primo della lista demo)
    $stabileAttivo = session('stabile_corrente') ?? $stabili->first()->denominazione;
    $annoAttivo = session('anno_corrente') ?? date('Y');
    $gestione = session('gestione_corrente') ?? 'Ord.';
    @endphp
    <style>
        :root {
            --sidebar-bg: {{ $sidebarBg }};
            --sidebar-text: {{ $sidebarText }};
            --sidebar-accent: {{ $sidebarAccent }};
        }
        .dark {
            --sidebar-bg: {{ $sidebarBgDark }};
            --sidebar-text: {{ $sidebarTextDark }};
            --sidebar-accent: {{ $sidebarAccentDark }};
        }
    </style>
    <!-- Modale ricerca stabile -->
    <div id="modal-stabile" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-2">Cerca stabile</h3>
            <input type="text" id="input-stabile" class="w-full border rounded px-3 py-2 mb-2" placeholder="Digita il nome dello stabile...">
            <ul id="result-stabile" class="max-h-40 overflow-y-auto"></ul>
            <div class="flex justify-end mt-2">
                <button id="close-modal-stabile" class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">Chiudi</button>
            </div>
        </div>
    </div>
    <script>
const stabili = {!! json_encode($stabili->pluck('denominazione')->values()->toArray()) !!};
let currentIndex = stabili.indexOf({!! json_encode($stabileAttivo) !!});
if(currentIndex === -1) currentIndex = 0;
// Dark mode toggle
const darkBtn = document.getElementById('toggle-darkmode');
darkBtn.addEventListener('click', function() {
    document.documentElement.classList.toggle('dark');
});
function updateStabile() {
    document.getElementById('current-stabile').textContent = stabili[currentIndex] || 'Nessuno selezionato';
    // TODO: chiamata AJAX o redirect per aggiornare sessione stabile_corrente
}
document.getElementById('prev-stabile').onclick = function() {
    currentIndex = (currentIndex - 1 + stabili.length) % stabili.length;
    updateStabile();
};
document.getElementById('next-stabile').onclick = function() {
    currentIndex = (currentIndex + 1) % stabili.length;
    updateStabile();
};
document.getElementById('current-stabile').onclick = function() {
    document.getElementById('modal-stabile').classList.remove('hidden');
    document.getElementById('input-stabile').value = '';
    document.getElementById('result-stabile').innerHTML = '';
};
document.getElementById('close-modal-stabile').onclick = function() {
    document.getElementById('modal-stabile').classList.add('hidden');
};
document.getElementById('input-stabile').oninput = function(e) {
    const val = e.target.value.toLowerCase();
    const results = stabili.filter(s => s.toLowerCase().includes(val));
    document.getElementById('result-stabile').innerHTML = results.map(s => `<li class='py-1 px-2 hover:bg-yellow-200 cursor-pointer' onclick='selectStabile("${s}")'>${s}</li>`).join('');
};
window.selectStabile = function(nome) {
    document.getElementById('current-stabile').textContent = nome;
    document.getElementById('modal-stabile').classList.add('hidden');
    // Chiamata AJAX per aggiornare la sessione stabile_corrente
    fetch('/session/stabile', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ stabile: nome })
    }).then(res => {
        if(res.ok) window.location.reload();
    });
};
updateStabile();
    </script>
    <footer class="w-full bg-gray-100 border-t border-gray-300 text-xs text-gray-600 text-center py-2 mt-4">
        <span>NetGesCon &copy; {{ date('Y') }} - <a href="https://github.com/netgescon" class="text-indigo-600 hover:underline">netgescon.github.io</a> - v0.7.0-dev</span>
    </footer>
</nav>
