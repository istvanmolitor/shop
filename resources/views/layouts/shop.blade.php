<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Webshop')</title>
    <style>
        :root {
            --bg: #f8fafc;
            --panel: #ffffff;
            --muted: #64748b;
            --text: #0f172a;
            --border: #e2e8f0;
            --primary: #2563eb;
            --primary-600: #1d4ed8;
            --accent: #f59e0b;
            --radius: 12px;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--text);
        }
        a { color: var(--primary); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 1100px; margin: 0 auto; padding: 1rem; }
        .nav {
            position: sticky; top: 0; z-index: 10;
            backdrop-filter: saturate(180%) blur(8px);
            background: rgba(255,255,255,0.7);
            border-bottom: 1px solid var(--border);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .8rem 0; }
        .brand { display: flex; align-items: center; gap: .6rem; font-weight: 700; letter-spacing: .2px; }
        .brand .logo { width: 28px; height: 28px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 6px 16px rgba(37,99,235,.35); }
        .brand a { color: var(--text); text-decoration: none; }
        .nav a { color: var(--text); opacity: .9; }
        .nav a:hover { opacity: 1; }
        .hero { padding: 2rem 0 1rem; }
        .hero h1 { margin: 0; font-size: 1.8rem; }
        .hero p { color: var(--muted); margin-top: .3rem; }
        .panel { background: var(--panel); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 10px 30px rgba(2, 6, 23, 0.04); }
        .content { padding: 1rem; }
        @media (min-width: 640px) { .content { padding: 1.25rem; } }
        @media (min-width: 1024px) { .content { padding: 1.5rem; } }
        .footer { text-align: center; color: var(--muted); font-size: .9rem; padding: 2rem 0; }
        /* Components */
        .grid { display: grid; gap: 1rem; }
        @media (min-width: 640px) { .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (min-width: 1024px) { .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        .card { background: var(--panel); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; }
        .card .thumb { position: relative; padding-top: 66%; background: #f1f5f9; }
        .card .thumb img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .card .body { padding: .9rem; display: grid; gap: .35rem; }
        .price { font-weight: 700; color: var(--primary-600); }
        .muted { color: var(--muted); }
        .btn { display: inline-flex; align-items: center; gap: .5rem; border: 1px solid var(--primary); color: #fff; background: var(--primary); padding: .55rem .8rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .btn:hover { background: var(--primary-600); text-decoration: none; }
        .back { display: inline-flex; align-items: center; gap: .4rem; font-weight: 500; }
        .details { display: grid; gap: 1rem; }
        @media (min-width: 768px) { .details { grid-template-columns: 1fr 1fr; } }
        .gallery { display: flex; gap: .5rem; flex-wrap: wrap; }
        .gallery img { width: 110px; height: 110px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid var(--border); padding: 8px 10px; text-align: left; }
        .table th { background: #f8fafc; }
    </style>
    @stack('head')
</head>
<body>
<nav class="nav">
    <div class="container nav-inner">
        <div class="brand">
            <span class="logo"></span>
            <a href="{{ route('shop.products.index') }}">Molitor Shop</a>
        </div>
        <div class="links">
            <a href="{{ route('shop.products.index') }}">Termékek</a>
        </div>
    </div>
</nav>
<div class="container">
    <div class="hero">
        <h1>@yield('page_title', 'Webshop')</h1>
        @hasSection('page_subtitle')
            <p class="muted">@yield('page_subtitle')</p>
        @endif
    </div>
    <div class="panel">
        <div class="content">
            @yield('content')
        </div>
    </div>
    <div class="footer">© {{ date('Y') }} Molitor Shop</div>
</div>
@stack('scripts')
</body>
</html>
