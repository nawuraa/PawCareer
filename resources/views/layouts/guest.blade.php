<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PawCareer — Klinik Hewan Sahabat Satwa')</title>

    {{-- FONTS: Plus Jakarta Sans (Sama dengan Welcome) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            DEFAULT: '#183828',
                            dark: '#11291D',
                            light: '#244F39',
                        },
                        cream: {
                            DEFAULT: '#FAF7F2',
                            warm: '#F5EDE0',
                        },
                        terracotta: {
                            DEFAULT: '#E68A3E',
                            hover: '#D4782C',
                            soft: '#FDF2E6',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #183828;
            background-color: #FFFFFF;
            margin: 0;
        }

        /* Styling Form Auth (Login & Register) */
        .auth-box .eyebrow {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #E68A3E;
            margin-bottom: 0.35rem;
        }
        .auth-box h2 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #183828;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin: 0 0 0.4rem 0;
        }
        .auth-box .intro {
            font-size: 0.875rem;
            color: rgba(24, 56, 40, 0.7);
            line-height: 1.55;
            margin: 0 0 1.35rem 0;
        }
        .auth-box .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 1rem;
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #B91C1C;
        }
        .auth-box .alert[role="status"] {
            background-color: #EEF5F0;
            border-color: #CFE0D5;
            color: #183828;
        }
        .auth-box .field {
            margin-bottom: 0.95rem;
        }
        .auth-box .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #183828;
            margin-bottom: 0.35rem;
        }
        .auth-box .field input,
        .auth-box .field select,
        .auth-box .field textarea {
            width: 100%;
            padding: 0.7rem 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            color: #183828;
            background-color: #F6F5F2;
            border: 1.5px solid #E5E3DD;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }
        .auth-box .field input:focus,
        .auth-box .field select:focus,
        .auth-box .field textarea:focus {
            background-color: #FFFFFF;
            border-color: #E68A3E;
            box-shadow: 0 0 0 3px rgba(230, 138, 62, 0.16);
        }
        .auth-box .field small,
        .auth-box .field .hint {
            display: block;
            font-size: 0.76rem;
            color: rgba(24, 56, 40, 0.65);
            margin-top: 0.3rem;
        }
        .auth-box .error {
            font-size: 0.76rem;
            font-weight: 600;
            color: #DC2626;
            margin-top: 0.3rem;
        }
        .auth-box .remember {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.84rem;
            font-weight: 500;
            color: rgba(24, 56, 40, 0.8);
            margin-top: 0.15rem;
            margin-bottom: 1.25rem;
            cursor: pointer;
        }
        .auth-box .remember input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #183828;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .auth-box .submit {
            width: 100%;
            margin-top: 0.35rem;
            padding: 0.85rem 1.25rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #FFFFFF;
            background-color: #183828;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }
        .auth-box .submit:hover {
            background-color: #11291D;
        }
        .auth-box .submit:active {
            transform: scale(0.995);
        }
        .auth-box .switch {
            text-align: center;
            font-size: 0.84rem;
            color: rgba(24, 56, 40, 0.7);
            margin-top: 1.2rem;
        }
        .auth-box .switch a {
            font-weight: 700;
            color: #E68A3E;
            text-decoration: none;
        }
        .auth-box .switch a:hover {
            text-decoration: underline;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col lg:flex-row antialiased selection:bg-terracotta/20 selection:text-forest">

    {{-- PANEL KIRI: BRANDING & FOTO PET-BACKGROUND --}}
    <section class="w-full lg:w-[50%] bg-gradient-to-br from-[#FAF7F2] via-[#FBF6EE] to-[#F3E5D0] px-7 sm:px-12 lg:px-14 py-8 lg:py-10 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-forest/10">
        <div class="w-full max-w-lg mx-auto">
            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-2xl overflow-hidden bg-forest shadow-sm ring-2 ring-forest/10 group-hover:scale-105 transition-transform shrink-0">
                    <img src="{{ asset('images/logo-paw.jpg') }}"
                         alt="Logo PawCareer"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-forest leading-none">PawCareer</span>
                    <span class="text-xs font-medium text-forest/65 mt-1">Klinik Hewan Sahabat Satwa</span>
                </div>
            </a>

            {{-- EYEBROW PILL --}}
            <div class="mt-6">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 border border-forest/10 text-xs font-bold text-forest/80 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-terracotta"></span>
                    Karier di Dunia Kesehatan Satwa
                </span>
            </div>

            {{-- HEADLINE (Sama persis dengan Welcome) --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold text-forest leading-[1.14] tracking-tight mt-4">
                Rawat mereka,<br>
                tumbuh bersama
                <span class="relative inline-block">
                    kami.
                    <svg class="w-full h-3 -mt-0.5 text-terracotta" viewBox="0 0 280 14" fill="none" preserveAspectRatio="none">
                        <path d="M3 10.5C75 4.5 190 3.5 277 8.5" stroke="currentColor" stroke-width="5.5" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>

            <p class="text-sm text-forest/70 leading-relaxed mt-3 font-normal">
                Temukan peluang karier terbaik bersama tim veteriner yang mengutamakan kesejahteraan satwa dan kenyamanan rekan kerja.
            </p>

            {{-- FOTO PET-BACKGROUND.JPG --}}
            <div class="mt-6 rounded-[1.5rem] p-2.5 bg-white/85 border border-forest/10 shadow-md">
                <div class="rounded-[1.15rem] overflow-hidden bg-[#EFE6D8] aspect-[16/9]">
                    <img src="{{ asset('images/pet-background-landscape.jpg') }}"
                         alt="Ilustrasi Hewan Peliharaan PawCareer"
                         class="w-full h-full object-cover object-center">
                </div>
            </div>
        </div>
    </section>

    {{-- PANEL KANAN: FORM LOGIN / REGISTER --}}
    <main class="w-full lg:w-[50%] bg-white px-7 sm:px-14 lg:px-16 py-8 lg:py-10 flex flex-col justify-center">
        <div class="w-full max-w-md mx-auto auth-box">
            @yield('content')

            <p class="mt-6 pt-5 border-t border-slate-100 text-center text-[11px] text-slate-400 leading-relaxed">
                Informasi akun Anda hanya digunakan untuk mendukung proses rekrutmen di Klinik Hewan Sahabat Satwa.
            </p>
        </div>
    </main>

</body>
</html>