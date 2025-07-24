<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Définir votre mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="max-w-md w-full bg-white p-6 rounded shadow">
    <h2 class="text-lg font-bold mb-4 text-center">Définir votre mot de passe</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('technicien.password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label class="block mb-1">Nouveau mot de passe</label>
        <input
            id="password"
            type="password"
            name="password"
            class="w-full border border-gray-300 p-2 rounded mb-3"
            required
        />

        <label class="block mb-1">Confirmer le mot de passe</label>
        <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            class="w-full border border-gray-300 p-2 rounded mb-4"
            required
        />

        <label class="inline-flex items-center mb-4 cursor-pointer select-none">
            <input type="checkbox" id="show-password-checkbox" class="form-checkbox" onchange="toggleShowPassword()" />
            <span class="ml-2 text-gray-700 select-none">Afficher le mot de passe</span>
        </label>

        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full">
            Enregistrer
        </button>
    </form>
</div>

<script>
    function toggleShowPassword() {
        const checkbox = document.getElementById('show-password-checkbox');
        const pwd = document.getElementById('password');
        const pwdConfirm = document.getElementById('password_confirmation');

        if (checkbox.checked) {
            pwd.type = 'text';
            pwdConfirm.type = 'text';
        } else {
            pwd.type = 'password';
            pwdConfirm.type = 'password';
        }
    }
</script>

</body>
</html>
