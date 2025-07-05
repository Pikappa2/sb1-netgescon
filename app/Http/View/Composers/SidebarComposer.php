<?php
namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Stabile;
use App\Models\Gestione;
use Spatie\Permission\Traits\HasRoles;

class SidebarComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        if ($user && !in_array(HasRoles::class, class_uses($user))) {
            $user->setRelation('roles', collect()); // fallback vuoto
        }
        $stabili = collect();
        if ($user) {
            if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                $stabili = Stabile::orderBy('denominazione')->get();
            } elseif ($user->amministratore) {
                $stabili = Stabile::where('amministratore_id', $user->amministratore->id_amministratore)->orderBy('denominazione')->get();
            }
        }
        $stabileAttivo = session('stabile_corrente') ?? ($stabili->first() ? $stabili->first()->denominazione : null);
        $stabileObj = $stabili->firstWhere('denominazione', $stabileAttivo);
        $gestioni = $stabileObj ? Gestione::where('stabile_id', $stabileObj->id)->orderByDesc('anno_gestione')->get() : collect();
        $annoAttivo = session('anno_corrente') ?? ($gestioni->first() ? $gestioni->first()->anno_gestione : date('Y'));
        $gestioneAttiva = session('gestione_corrente') ?? ($gestioni->first() ? $gestioni->first()->tipo_gestione : 'Ord.');
        $view->with([
            'stabili' => $stabili,
            'stabileAttivo' => $stabileAttivo,
            'anni' => $gestioni->pluck('anno_gestione')->unique(),
            'annoAttivo' => $annoAttivo,
            'gestione' => $gestioneAttiva,
        ]);
    }
}
