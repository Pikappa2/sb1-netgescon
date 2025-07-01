@php
        $roles = is_array(Auth::user()->roles ?? null)
            ? Auth::user()->roles
            : explode(',', Auth::user()->role ?? '');
@endphp