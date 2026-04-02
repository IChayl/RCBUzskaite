<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pieteikšanās</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#0F1931">
    <script>
        (function () {
            const key = 'rcb-theme';
            const stored = localStorage.getItem(key);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored === 'light' || stored === 'dark' ? stored : (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Pielāgojums lietotnes izkārtojuma tēmai */
        :root{
            --navy: #0F1931;
            --navy-2: #2D4159;
            --maroon: #594435;
            --maroon-2: #C89768;
            --accent: #E2D4BB;
            --card-bg: rgba(226, 212, 187, 0.06);
            --muted-white: rgba(226, 212, 187, 0.9);
        }

        html[data-theme="light"] {
            --navy: #f7faff;
            --navy-2: #dae5f3;
            --maroon: #e9eff7;
            --maroon-2: #c8d8eb;
            --accent: #1f2f43;
            --card-bg: rgba(255, 255, 255, 0.8);
            --muted-white: #1f2f43;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: radial-gradient(1200px 800px at 10% 20%, rgba(45, 65, 89, 0.12), transparent 8%),
                        radial-gradient(1000px 600px at 90% 80%, rgba(15, 25, 49, 0.2), transparent 10%),
                        linear-gradient(180deg, var(--navy) 0%, var(--maroon) 100%);
            color: var(--muted-white);
        }

        /* Centrējošs konteiners */
        .center-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .theme-toggle-floating {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 1000;
            border: 1px solid rgba(226, 212, 187, 0.32);
            border-radius: 999px;
            padding: 8px 12px;
            font-weight: 600;
            color: var(--accent);
            background: rgba(226, 212, 187, 0.1);
            backdrop-filter: blur(4px);
        }

        /* Stikla efekta kartīte */
        .card.custom {
            width: 400px;
            background: var(--card-bg);
            border: 1px solid rgba(226, 212, 187, 0.08);
            color: var(--muted-white);
            box-shadow: 0 8px 30px rgba(15, 25, 49, 0.45);
            border-radius: 12px;
            backdrop-filter: blur(6px) saturate(120%);
            overflow: hidden;
        }

        .card.custom h2 {
            color: var(--accent);
            font-weight: 600;
        }

        label.form-label {
            color: rgba(226, 212, 187, 0.85);
            font-weight: 500;
        }

        input.form-control {
            background: rgba(226, 212, 187, 0.03);
            border: 1px solid rgba(226, 212, 187, 0.06);
            color: var(--muted-white);
        }

        input.form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.15rem rgba(45, 65, 89, 0.15);
            background: rgba(226, 212, 187, 0.04);
            color: var(--muted-white);
        }

        /* Pogas bloom efekts */
        .btn-bloom {
            position: relative;
            overflow: hidden;
            color: #E2D4BB;
            background: linear-gradient(90deg, var(--maroon) 0%, var(--maroon-2) 50%, var(--navy-2) 100%);
            border: none;
            transition: transform 180ms ease, box-shadow 180ms ease;
            z-index: 0;
        }

        .btn-bloom::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            width: 20px;
            height: 20px;
            background: radial-gradient(circle, rgba(226, 212, 187, 0.32) 0%, rgba(226, 212, 187, 0.06) 40%, transparent 60%);
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
            transition: transform 600ms cubic-bezier(.2,.9,.2,1), opacity 250ms ease;
            pointer-events: none;
            border-radius: 50%;
            z-index: -1;
        }

        .btn-bloom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(45, 65, 89, 0.35);
        }

        .btn-bloom:active {
            transform: translateY(0);
        }

        .btn-bloom:focus {
            outline: none;
            box-shadow: 0 0 0 0.3rem rgba(45, 65, 89, 0.18);
        }

        .btn-bloom:hover::after {
            transform: translate(-50%, -50%) scale(12);
            opacity: 1;
        }

        /* Saites un mazais teksts */
        .card .text-center a {
            color: rgba(226, 212, 187, 0.85);
            text-decoration: none;
        }

        .card .text-center a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        /* Kļūdu bloks pielāgots tēmai */
        .alert-danger {
            background: linear-gradient(180deg, rgba(45, 65, 89, 0.15), rgba(45, 65, 89, 0.08));
            border-color: rgba(226, 212, 187, 0.06);
            color: #E2D4BB;
        }

        /* Responsīvie pielāgojumi */
        @media (max-width: 420px){
            .card.custom { width: 100%; padding: 1.25rem; border-radius: 10px; }
        }
    </style>
</head>

<body>
    <button type="button" class="theme-toggle-floating" data-theme-toggle aria-label="Mainīt tēmu">Tumša tēma</button>

    <div class="center-wrap">
        <div class="card custom p-4">
            <h2 class="text-center mb-4">Pieteikšanās</h2>

            <!-- Ziņojumi par kļūdām -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Pieteikšanās forma -->
            <form method="POST" action="/Login/submit" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">E-pasts</label>
                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Parole</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-bloom w-100">Pieteikties</button>

                Admins: janis@rcb.lv | Parole: 12345
                <br>
                Lietotājs: test@rcb.lv | Parole: 12345

            </form>

            <div class="text-center mt-3">

            </div>
        </div>
    </div>

</body>
<script>
    (function () {
        const key = 'rcb-theme';

        const getTheme = () => document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';

        const setTheme = (theme) => {
            const resolved = theme === 'light' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', resolved);
            localStorage.setItem(key, resolved);

            const meta = document.querySelector('meta[name="theme-color"]');
            if (meta) {
                meta.setAttribute('content', resolved === 'light' ? '#e8eef7' : '#0F1931');
            }

            const btn = document.querySelector('[data-theme-toggle]');
            if (btn) {
                btn.textContent = resolved === 'light' ? 'Gaiša tēma' : 'Tumša tēma';
                btn.title = resolved === 'light' ? 'Pārslēgt uz tumšo tēmu' : 'Pārslēgt uz gaišo tēmu';
            }
        };

        const button = document.querySelector('[data-theme-toggle]');
        if (button) {
            button.addEventListener('click', () => {
                setTheme(getTheme() === 'dark' ? 'light' : 'dark');
            });
        }

        setTheme(getTheme());
    })();
</script>
</html>
