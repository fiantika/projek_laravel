<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-cover bg-center" 
      style="background-image: url('/images/buat_bg.jpg');">

    <div class="w-full max-w-sm bg-white p-6 rounded-lg shadow-lg border border-purple-200">
        <h1 class="text-2xl font-bold mb-4 text-center text-purple-700">Masuk</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-2 rounded mb-4">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login/do" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-purple-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="mt-1 block w-full rounded-md border-purple-300 shadow-sm 
                              focus:border-purple-400 focus:ring focus:ring-purple-300 focus:ring-opacity-50" 
                       required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-purple-700">Password</label>
                <input type="password" id="password" name="password" 
                       class="mt-1 block w-full rounded-md border-purple-300 shadow-sm 
                              focus:border-purple-400 focus:ring focus:ring-purple-300 focus:ring-opacity-50" 
                       required>
            </div>
            <div>
                <button type="submit" 
                        class="w-full py-2 px-4 bg-purple-600 text-white rounded-md 
                               hover:bg-purple-700 focus:outline-none focus:ring-2 
                               focus:ring-offset-2 focus:ring-purple-500">
                    Login
                </button>
            </div>
        </form>
    </div>
</body>
</html>
