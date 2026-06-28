<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisor — Take Control of Your Academic Progress</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">

    <!-- ============ NAV ============ -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 rounded-lg p-1.5">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="font-bold text-lg">Revisor</span>
            </div>
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Go to my Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                        Sign in
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ============ HERO ============ -->
    <section class="relative overflow-hidden bg-gradient-to-b from-blue-50 via-white to-white">
        <div class="max-w-6xl mx-auto px-6 pt-16 pb-20 grid md:grid-cols-2 gap-12 items-center">

            <div>
                <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full mb-5">
                    Built for university students
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight text-gray-900">
                    A smarter way to <span class="text-blue-600">revise</span>, every semester
                </h1>
                <p class="mt-5 text-lg text-gray-600 max-w-md">
                    Revisor tracks your revision time, monitors your marks, and tells you exactly what to study next — so no subject gets left behind.
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-sm">
                        Get Started Free
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <span class="text-sm text-gray-400">No credit card. No app to install.</span>
                </div>
            </div>

            <!-- Stylized dashboard mockup -->
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-5 rotate-1">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Study Next</span>
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <p class="font-bold text-gray-900">Algorithms & Complexity</p>
                        <p class="text-xs text-gray-500 mt-1">Last studied 9 days ago</p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Computer Systems</span>
                            <span class="font-semibold text-green-600">82%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Statistical Inference</span>
                            <span class="font-semibold text-amber-600">74%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Software Engineering</span>
                            <span class="font-semibold text-green-600">91%</span>
                        </div>
                    </div>
                </div>
                <!-- floating accent card -->
                <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-lg border border-gray-100 p-4 w-40 -rotate-3">
                    <p class="text-xs text-gray-400">Total Hours</p>
                    <p class="text-2xl font-extrabold text-blue-600">42.5</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ============ STATS STRIP ============ -->
    <section class="border-y border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-3xl font-extrabold text-blue-600">4</p>
                <p class="text-sm text-gray-500 mt-1">Smart features built in</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-blue-600">77</p>
                <p class="text-sm text-gray-500 mt-1">Kenyan universities supported</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-blue-600">100%</p>
                <p class="text-sm text-gray-500 mt-1">Free to use</p>
            </div>
        </div>
    </section>

    <!-- ============ FEATURE 1 — Subject Suggester ============ -->
    <section class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
        <div class="order-2 md:order-1">
            <div class="bg-blue-50 rounded-2xl p-8 aspect-square flex items-center justify-center">
                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.99-2.388l-.548-.547z" />
                </svg>
            </div>
        </div>
        <div class="order-1 md:order-2">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Subject Suggester</span>
            <h2 class="text-3xl font-bold mt-2 mb-4">Never wonder what to study next</h2>
            <p class="text-gray-600 leading-relaxed">
                Revisor looks at your revision history and tells you exactly which subject has been neglected the longest. No more guessing, no more cramming the night before — just one clear answer every time you log in.
            </p>
        </div>
    </section>

    <!-- ============ FEATURE 2 — Resource Recommender ============ -->
    <section class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Resource Recommender</span>
                <h2 class="text-3xl font-bold mt-2 mb-4">Free, curated resources — right when you need them</h2>
                <p class="text-gray-600 leading-relaxed">
                    The moment a subject is flagged as a priority, Revisor surfaces free learning material tailored to it — from Khan Academy, MIT OpenCourseWare, and YouTube — so you can start studying immediately instead of searching.
                </p>
            </div>
            <div class="bg-white rounded-2xl p-8 aspect-square flex items-center justify-center shadow-sm">
                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s4.332.477 5.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>
    </section>

    <!-- ============ FEATURE 3 — Let's Meet ============ -->
    <section class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
        <div class="order-2 md:order-1">
            <div class="bg-blue-50 rounded-2xl p-8 aspect-square flex items-center justify-center">
                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div class="order-1 md:order-2">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Let's Meet</span>
            <h2 class="text-3xl font-bold mt-2 mb-4">Find peers who can actually help</h2>
            <p class="text-gray-600 leading-relaxed">
                When you're struggling with a subject, Revisor connects you with high-performing students — starting with your own university for easy in-person study sessions. No marks or contact details are shared without your consent, and joining is always your choice.
            </p>
        </div>
    </section>

    <!-- ============ FEATURE 4 — Study Wrapped ============ -->
    <section class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Study Wrapped</span>
                <h2 class="text-3xl font-bold mt-2 mb-4">Your semester, summarized</h2>
                <p class="text-gray-600 leading-relaxed">
                    At semester's end, Revisor gives you a personal recap — your most studied subject, the one you neglected, your top performer, and your total revision hours. A simple way to reflect on the effort you put in.
                </p>
            </div>
            <div class="bg-white rounded-2xl p-8 aspect-square flex items-center justify-center shadow-sm">
                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </section>

    <!-- ============ HOW IT WORKS ============ -->
    <section class="max-w-6xl mx-auto px-6 py-20">
        <h2 class="text-3xl font-bold text-center mb-12">How it works</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4">1</div>
                <h3 class="font-semibold mb-2">Sign in with Google</h3>
                <p class="text-sm text-gray-500">No passwords, no setup hassle. Pick your university and you're in.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4">2</div>
                <h3 class="font-semibold mb-2">Add your subjects</h3>
                <p class="text-sm text-gray-500">Tell Revisor what you're studying this semester — it takes seconds.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4">3</div>
                <h3 class="font-semibold mb-2">Log sessions & marks</h3>
                <p class="text-sm text-gray-500">Revisor takes it from there — recommendations, resources, and peers, automatically.</p>
            </div>
        </div>
    </section>

    <!-- ============ FINAL CTA ============ -->
    <section class="bg-blue-600">
        <div class="max-w-3xl mx-auto px-6 py-20 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                Take control of your academic progress today
            </h2>
            <p class="text-blue-100 mb-8">Free for every Kenyan university student. No catch.</p>
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 bg-white text-blue-600 font-semibold px-7 py-3 rounded-lg hover:bg-blue-50 transition shadow-sm">
                Get Started Free
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-6xl mx-auto px-6 py-10 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 rounded-lg p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="font-bold text-white">Revisor</span>
            </div>
            <p class="text-sm text-center">Helping university students master their revision.</p>
            <div class="flex gap-4 text-xs uppercase tracking-wide">
                <a href="#" class="hover:text-white">Support</a>
                <a href="#" class="hover:text-white">Academic Integrity</a>
            </div>
        </div>
    </footer>

</body>
</html>
