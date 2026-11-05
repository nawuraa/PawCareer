<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f2">
    <title>@yield('title', 'PawCareer')</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #17231d; background: #f5f7f2; font-synthesis: none; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(360px, .95fr); }
        .story { display: flex; flex-direction: column; justify-content: space-between; padding: clamp(2rem, 6vw, 5.5rem); color: #f8faf6; background: #183c2b; }
        .brand { display: inline-flex; align-items: center; gap: .75rem; width: fit-content; color: inherit; text-decoration: none; font-size: 1.2rem; font-weight: 750; letter-spacing: -.03em; }
        .brand-mark { display: grid; width: 2.5rem; height: 2.5rem; place-items: center; border-radius: .85rem; color: #183c2b; background: #c9e6a8; font-size: 1.35rem; }
        .story-copy { max-width: 38rem; margin: 4rem 0; }
        .eyebrow { color: #b7d99d; font-size: .75rem; font-weight: 750; letter-spacing: .16em; text-transform: uppercase; }
        .story h1 { max-width: 11ch; margin: 1rem 0; font-size: clamp(2.8rem, 6vw, 5.7rem); line-height: .99; letter-spacing: -.065em; }
        .story-copy p { max-width: 34rem; color: #d3ded5; font-size: 1.08rem; line-height: 1.8; }
        .story-foot { color: #b9c9bd; font-size: .82rem; }
        .panel { display: grid; place-items: center; padding: 2rem; }
        .form-card { width: min(100%, 430px); }
        .mobile-brand { display: none; }
        .form-card h2 { margin: 0; font-size: 2rem; letter-spacing: -.045em; }
        .intro { margin: .55rem 0 1.8rem; color: #64736a; line-height: 1.6; }
        .field { margin: 1rem 0; }
        .remember { display: flex; align-items: center; gap: .55rem; margin: .25rem 0 .75rem; font-weight: 500; }
        .remember input { width: 1rem; min-height: 1rem; accent-color: #24633b; }
        label { display: block; margin-bottom: .45rem; color: #34443a; font-size: .9rem; font-weight: 650; }
        input { width: 100%; min-height: 3rem; padding: .75rem .9rem; border: 1px solid #d5ded5; border-radius: .7rem; outline: none; background: #fff; color: #17231d; font: inherit; }
        input:focus { border-color: #34794c; box-shadow: 0 0 0 3px #34794c20; }
        input::placeholder { color: #99a59c; }
        .hint { margin: .4rem 0 0; color: #738078; font-size: .78rem; }
        .error { margin: .4rem 0 0; color: #a52c2c; font-size: .82rem; }
        .alert { margin: 1rem 0; padding: .8rem 1rem; border: 1px solid #f0caca; border-radius: .65rem; color: #8c2525; background: #fff1f1; font-size: .9rem; }
        .submit { width: 100%; min-height: 3.1rem; margin-top: .75rem; border: 0; border-radius: .7rem; color: #fff; background: #24633b; font: inherit; font-weight: 700; cursor: pointer; transition: background .15s, transform .15s; }
        .submit:hover { background: #194d2d; transform: translateY(-1px); }
        .switch { margin-top: 1.4rem; color: #64736a; text-align: center; font-size: .9rem; }
        a { color: #24633b; font-weight: 650; }
        .privacy { margin-top: 1.8rem; color: #87938a; text-align: center; font-size: .76rem; line-height: 1.6; }
        @media (max-width: 760px) {
            body { display: block; }
            .story { display: none; }
            .panel { min-height: 100vh; padding: 1.5rem; }
            .mobile-brand { display: inline-flex; margin-bottom: 2.5rem; color: #183c2b; }
            .form-card { max-width: 440px; }
        }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; } }
    </style>
</head>
<body>
    <aside class="story" aria-label="About PawCareer">
        <a class="brand" href="{{ route('login') }}">
            <span class="brand-mark" aria-hidden="true">+</span>
            <span>PawCareer</span>
        </a>
        <div class="story-copy">
            <span class="eyebrow">Careers in animal care</span>
            <h1>Good people. Better care.</h1>
            <p>Find your next opportunity with a veterinary team that puts animals and the people who care for them first.</p>
        </div>
        <p class="story-foot">A calmer way to find meaningful work in veterinary care.</p>
    </aside>
    <main class="panel">
        <section class="form-card">
            <a class="brand mobile-brand" href="{{ route('login') }}">
                <span class="brand-mark" aria-hidden="true">+</span>
                <span>PawCareer</span>
            </a>
            @yield('content')
            <p class="privacy">Your account information is used only to support your recruitment journey.</p>
        </section>
    </main>
</body>
</html>