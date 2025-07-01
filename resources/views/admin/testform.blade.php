<!-- filepath: resources/views/admin/testform.blade.php -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Test Form Admin</title>
</head>
<body>
    <h1>Form di Test Admin con CSRF</h1>
    <form method="POST" action="{{ route('admin.testform.submit') }}">
        @csrf
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome">
        <button type="submit">Invia</button>
    </form>
    @if(session('ok'))
        <div style="color:green;">{{ session('ok') }}</div>
    @endif
</body>
</html>