<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Masuk | Tokoku</title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      position: relative;
      overflow: hidden;
      background: linear-gradient(to right, #6d28d9, #7e22ce, #9333ea);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* === BACKGROUND ICONS === */
    .bg-icons {
      position: absolute;
      inset: 0;
      z-index: 1;
      pointer-events: none;
      overflow: hidden;
    }

    .bg-icons i {
      position: absolute;
      font-size: 3.5rem;
      color: white;
      opacity: 0.07;
      filter: blur(1px);
      animation: floatXY ease-in-out infinite;
    }

    /* animasi biar lebih hidup ke segala arah */
    @keyframes floatXY {
      0%   { transform: translate(0, 0) rotate(0deg); }
      25%  { transform: translate(40px, -30px) rotate(10deg); }
      50%  { transform: translate(-40px, 20px) rotate(-8deg); }
      75%  { transform: translate(30px, 40px) rotate(6deg); }
      100% { transform: translate(0, 0) rotate(0deg); }
    }

    /* === LOGIN CARD === */
    .login-card {
      position: relative;
      z-index: 2;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      padding: 2rem;
    }

    .title-gradient {
      background: linear-gradient(to right, #7c3aed, #a855f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .login-btn:hover {
      transform: translateY(-2px) scale(1.03);
      transition: all 0.3s ease;
    }
  </style>
</head>

<body>
  <!-- Floating Background Icons -->
  <div class="bg-icons" id="bgIcons"></div>

  <!-- Login Form -->
  <div class="login-card w-full max-w-md">
    <h1 class="text-4xl font-bold mb-2 text-center title-gradient">Tokoku</h1>
    <p class="text-center text-gray-600 mb-6">Track Every Sale, Grow Everyday</p>

    @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
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
        <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2"
               placeholder="Email" required>
      </div>
      <div>
        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
        <input type="password" id="password" name="password"
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-2"
               placeholder="Password" required>
      </div>
      <div>
        <button type="submit"
                class="login-btn w-full py-2 px-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
          Masuk
        </button>
      </div>
    </form>
  </div>

  <script>
    // === Generate random icons ===
    const icons = [
      "fa-store", "fa-shopping-cart", "fa-bag-shopping",
      "fa-tags", "fa-box", "fa-box-open", "fa-cash-register"
    ];

    const container = document.getElementById('bgIcons');

    for (let i = 0; i < 35; i++) { // lebih banyak dikit biar penuh tapi tetap rapi
      const icon = document.createElement('i');
      icon.classList.add('fas', icons[Math.floor(Math.random() * icons.length)]);

      // Random position + random speed
      icon.style.top = `${Math.random() * 100}%`;
      icon.style.left = `${Math.random() * 100}%`;
      icon.style.fontSize = `${2 + Math.random() * 4}rem`;
      icon.style.animationDuration = `${4 + Math.random() * 6}s`; // lebih cepat
      icon.style.animationDelay = `${Math.random() * 3}s`;

      container.appendChild(icon);
    }
  </script>
</body>
</html>
