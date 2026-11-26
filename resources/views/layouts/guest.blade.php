<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0F2D23">
    <title>@yield('title', 'PawCareer — Klinik Hewan Sahabat Satwa')</title>

    {{-- FONTS: Bricolage Grotesque (display) + Figtree (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700;800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', 'Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            DEFAULT: '#0F2D23',
                            dark: '#072D25',
                            light: '#1B3D32',
                        },
                        brand: {
                            DEFAULT: '#06614B',
                            soft: '#D2F0E4',
                            hover: '#054D3B',
                        },
                        cream: {
                            DEFAULT: '#FAF6EE',
                            warm: '#F2ECE1',
                        },
                        apricot: {
                            DEFAULT: '#F09A53',
                            hover: '#E08843',
                            soft: '#FFEBD2',
                            fg: '#401F0A',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html { -webkit-font-smoothing: antialiased; }
        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            color: #0E271F;
            background-color: #FFFDFA;
            margin: 0;
        }
        h1, h2, h3, h4 {
            font-family: 'Bricolage Grotesque', 'Figtree', ui-sans-serif, system-ui, sans-serif;
            letter-spacing: -0.02em;
        }

        /* Styling Form Auth (Login & Register) */
        .auth-box .eyebrow {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #06614B;
            margin-bottom: 0.35rem;
        }
        .auth-box h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0E271F;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin: 0 0 0.4rem 0;
        }
        .auth-box .intro {
            font-size: 0.875rem;
            color: #536961;
            line-height: 1.55;
            margin: 0 0 1.35rem 0;
        }
        .auth-box .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.875rem;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 1rem;
            background-color: #FFE2DD;
            border: 1px solid #F5C0BE;
            color: #B32322;
        }
        .auth-box .alert[role="status"] {
            background-color: #CCF4D3;
            border-color: #A3E6B0;
            color: #00682A;
        }
        .auth-box .field {
            margin-bottom: 0.95rem;
        }
        .auth-box .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #0E271F;
            margin-bottom: 0.35rem;
        }
        .auth-box .field input,
        .auth-box .field select,
        .auth-box .field textarea {
            width: 100%;
            padding: 0.7rem 0.95rem;
            font-family: 'Figtree', sans-serif;
            font-size: 0.875rem;
            color: #0E271F;
            background-color: #FAF6EE;
            border: 1.5px solid #DDD7C9;
            border-radius: 0.875rem;
            outline: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }
        .auth-box .field input:focus,
        .auth-box .field select:focus,
        .auth-box .field textarea:focus {
            background-color: #FFFDFA;
            border-color: #06614B;
            box-shadow: 0 0 0 3px rgba(6, 97, 75, 0.14);
        }
        .auth-box .field small,
        .auth-box .field .hint {
            display: block;
            font-size: 0.76rem;
            color: #536961;
            margin-top: 0.3rem;
        }
        .auth-box .error {
            font-size: 0.76rem;
            font-weight: 600;
            color: #B32322;
            margin-top: 0.3rem;
        }
        .auth-box .remember {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.84rem;
            font-weight: 500;
            color: #536961;
            margin-top: 0.15rem;
            margin-bottom: 1.25rem;
            cursor: pointer;
        }
        .auth-box .remember input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #06614B;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .auth-box .submit {
            width: 100%;
            margin-top: 0.35rem;
            padding: 0.85rem 1.25rem;
            font-family: 'Figtree', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #FCF8F0;
            background-color: #06614B;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }
        .auth-box .submit:hover {
            background-color: #054D3B;
        }
        .auth-box .submit:active {
            transform: scale(0.995);
        }
        .auth-box .switch {
            text-align: center;
            font-size: 0.84rem;
            color: #536961;
            margin-top: 1.2rem;
        }
        .auth-box .switch a {
            font-weight: 700;
            color: #06614B;
            text-decoration: none;
        }
        .auth-box .switch a:hover {
            text-decoration: underline;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col lg:flex-row antialiased selection:bg-[#F09A53]/25 selection:text-[#0E271F]">

    {{-- PANEL KIRI: BRANDING & FOTO PET-BACKGROUND --}}
    <section class="w-full lg:w-[50%] px-7 sm:px-12 lg:px-14 py-8 lg:py-10 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-[#E3DDD1]"
             style="background-color:#FAF6EE; background-image: radial-gradient(120% 80% at 100% 0%, rgba(240, 154, 83, 0.22) 0%, transparent 55%), radial-gradient(90% 70% at 0% 100%, rgba(6, 97, 75, 0.14) 0%, transparent 60%);">
        <div class="w-full max-w-lg mx-auto">
            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform"
                     style="background:#F09A53;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="12" cy="17" rx="5" ry="4" fill="#0F2D23"/>
                        <ellipse cx="6.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                        <ellipse cx="17.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                        <ellipse cx="9" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                        <ellipse cx="15" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-[#0E271F] leading-none font-display">PawCareer</span>
                    <span class="text-xs font-medium text-[#536961] mt-1">Klinik Hewan Sahabat Satwa</span>
                </div>
            </a>

            {{-- EYEBROW PILL --}}
            <div class="mt-6">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFFDFA] border border-[#E3DDD1] text-xs font-bold text-[#0E271F] shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#F09A53]"></span>
                    Karier di Dunia Kesehatan Satwa
                </span>
            </div>

            {{-- HEADLINE --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E271F] leading-[1.14] tracking-tight mt-4 font-display">
                Rawat mereka,<br>
                tumbuh bersama
                <span class="relative inline-block whitespace-nowrap">
                    kami.
                    <svg class="w-full h-3 -mt-0.5 text-[#F09A53]" viewBox="0 0 280 14" fill="none" preserveAspectRatio="none">
                        <path d="M3 10.5C75 4.5 190 3.5 277 8.5" stroke="currentColor" stroke-width="5.5" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>

            <p class="text-sm text-[#536961] leading-relaxed mt-3 font-normal">
                Temukan peluang karier terbaik bersama tim veteriner yang mengutamakan kesejahteraan satwa dan kenyamanan rekan kerja.
            </p>

            {{-- FOTO PET-BACKGROUND.JPG --}}
            <div class="mt-6 rounded-3xl p-2.5 bg-[#FFFDFA]/90 border border-[#E3DDD1] shadow-md">
                <div class="rounded-2xl overflow-hidden bg-[#F2ECE1] aspect-[16/9]">
                    <img src="{{ asset('images/pet-background-landscape.jpg') }}"
                         alt="Ilustrasi Hewan Peliharaan PawCareer"
                         class="w-full h-full object-cover object-center">
                </div>
            </div>
        </div>
    </section>

    {{-- PANEL KANAN: FORM LOGIN / REGISTER --}}
    <main class="w-full lg:w-[50%] bg-[#FFFDFA] px-7 sm:px-14 lg:px-16 py-8 lg:py-10 flex flex-col justify-center">
        <div class="w-full max-w-md mx-auto auth-box">
            @yield('content')

            <p class="mt-6 pt-5 border-t border-[#E3DDD1] text-center text-[11px] text-[#536961] leading-relaxed">
                Informasi akun Anda hanya digunakan untuk mendukung proses rekrutmen di Klinik Hewan Sahabat Satwa.
            </p>
        </div>
    </main>

</body>
</html>
