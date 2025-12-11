<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - Situation Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-16 max-w-md">
        <h1 class="text-4xl font-bold mb-8 text-center">Start Your Workspace</h1>

        <div class="bg-gray-800 p-8 rounded-lg">
            <div class="bg-blue-900 p-4 rounded mb-6">
                <p class="text-xl font-bold">€49 / Monat</p>
                <p class="text-sm">Jederzeit kündbar</p>
            </div>

            @if($errors->any())
                <div class="bg-red-900 border-l-4 border-red-500 p-4 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-900 border-l-4 border-blue-500 p-4 mb-6">
                    {{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('signup.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-2">Organisation / Name:</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full p-2 bg-gray-700 rounded text-white">
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Ihre Subdomain:</label>
                    <div class="flex items-center">
                        <input type="text" name="subdomain" value="{{ old('subdomain') }}" required
                               class="flex-1 p-2 bg-gray-700 rounded-l text-white"
                               pattern="[a-z0-9-]+"
                               placeholder="meinefirma">
                        <span class="bg-gray-600 p-2 rounded-r">.situationroom.eu</span>
                    </div>
                    <p class="text-sm text-gray-400 mt-1">Nur Kleinbuchstaben, Zahlen und Bindestriche</p>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full p-2 bg-gray-700 rounded text-white">
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Passwort:</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full p-2 bg-gray-700 rounded text-white">
                </div>

                <div class="mb-6">
                    <label class="block mb-2">Passwort bestätigen:</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full p-2 bg-gray-700 rounded text-white">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 p-3 rounded font-bold text-lg">
                    Weiter zur Zahlung →
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/" class="text-blue-400 hover:text-blue-300">← Zurück zur Startseite</a>
            </div>
        </div>
    </div>
</body>
</html>
