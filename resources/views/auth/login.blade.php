<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revisor — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-md w-full max-w-sm px-8 py-10">

        <!-- Logo + Title -->
        <div class="flex flex-col items-center mb-7">
            <div class="bg-blue-600 rounded-xl p-2.5 mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Revisor</h1>
            <p class="text-sm text-gray-500 mt-1">Take control of your academic progress</p>
        </div>

        <!-- Single Sign-In Button -->
        <button type="button" id="google-signin-btn"
            class="w-full flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#ffffff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#ffffff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#ffffff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#ffffff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Sign in with Google
        </button>

        <!-- Terms -->
        <p class="text-xs text-center text-gray-400 mb-5">
            By signing in, you agree to Revisor's
            <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and
            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
            regarding your academic data.
        </p>

    </div>

    <!-- Footer -->
    <p class="text-xs text-gray-400 mt-6">Helping students master their revision.</p>
    <div class="flex gap-4 mt-2 text-xs text-gray-400">
        <a href="#" class="hover:text-gray-600 uppercase tracking-wide">Support</a>
        <a href="#" class="hover:text-gray-600 uppercase tracking-wide">Status</a>
        <a href="#" class="hover:text-gray-600 uppercase tracking-wide">Academic Integrity</a>
    </div>

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
    <script>
        firebase.initializeApp({
            apiKey: "AIzaSyA90fJOZWta_aCmQBLTt72XUZmNztXI-OI",
            authDomain: "studybuddy-d9ba3.firebaseapp.com",
            projectId: "studybuddy-d9ba3",
            messagingSenderId: "1019183329365",
            appId: "1:1019183329365:web:ba63ce1112835ea717a4dc"
        });

        async function handleGoogleSignIn() {
            const provider = new firebase.auth.GoogleAuthProvider();
            try {
                const result = await firebase.auth().signInWithPopup(provider);
                const token = await result.user.getIdToken();
                const response = await fetch('/auth/google', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ token })
                });
                const data = await response.json();
                if (data.success) window.location.href = data.redirect;
                else alert('Sign-in failed: ' + data.error);
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        document.getElementById('google-signin-btn').addEventListener('click', handleGoogleSignIn);
    </script>
</body>
</html>