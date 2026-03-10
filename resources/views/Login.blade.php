<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pieteikšanās</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Navy-Maroon theme */
        :root{
            --navy: #071632;
            --maroon: #6b0f14;
            --accent: #b94b4b;
            --card-bg: rgba(255,255,255,0.06);
            --muted-white: rgba(255,255,255,0.9);
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: linear-gradient(135deg, var(--navy) 0%, var(--maroon) 100%);
            color: var(--muted-white);
        }

        /* Centering container */
        .center-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* Glassy card */
        .card.custom {
            width: 400px;
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--muted-white);
            box-shadow: 0 8px 30px rgba(0,0,0,0.45);
            border-radius: 12px;
            backdrop-filter: blur(6px) saturate(120%);
            overflow: hidden;
        }

        .card.custom h2 {
            color: var(--accent);
            font-weight: 600;
        }

        label.form-label {
            color: rgba(255,255,255,0.85);
            font-weight: 500;
        }

        input.form-control {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            color: var(--muted-white);
        }

        input.form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.15rem rgba(185,75,75,0.15);
            background: rgba(255,255,255,0.04);
            color: var(--muted-white);
        }

        /* Button bloom effect */
        .btn-bloom {
            position: relative;
            overflow: hidden;
            color: #fff;
            background: linear-gradient(90deg, var(--accent) 0%, #7a1c23 50%, var(--navy) 100%);
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
            background: radial-gradient(circle, rgba(255,255,255,0.32) 0%, rgba(255,255,255,0.06) 40%, transparent 60%);
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
            transition: transform 600ms cubic-bezier(.2,.9,.2,1), opacity 250ms ease;
            pointer-events: none;
            border-radius: 50%;
            z-index: -1;
        }

        .btn-bloom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(107,15,20,0.35);
        }

        .btn-bloom:active {
            transform: translateY(0);
        }

        .btn-bloom:focus {
            outline: none;
            box-shadow: 0 0 0 0.3rem rgba(185,75,75,0.18);
        }

        .btn-bloom:hover::after {
            transform: translate(-50%, -50%) scale(12);
            opacity: 1;
        }

        /* Link and small text */
        .card .text-center a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
        }

        .card .text-center a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        /* Error box adapted to theme */
        .alert-danger {
            background: linear-gradient(180deg, rgba(107,15,20,0.15), rgba(107,15,20,0.08));
            border-color: rgba(255,255,255,0.06);
            color: #ffdede;
        }

        /* Responsive tweaks */
        @media (max-width: 420px){
            .card.custom { width: 100%; padding: 1.25rem; border-radius: 10px; }
        }
    </style>
</head>

<body>

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

            <!-- Login forma -->
            <form method="POST" action="/Login/submit">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Lietotājvārds</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Parole</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-bloom w-100">Pieteikties</button>
            </form>

            <div class="text-center mt-3">
                <a href="/register">Izveidot kontu</a> · <a href="/">← Atpakaļ uz sākumlapu</a>
            </div>
        </div>
    </div>

</body>
</html>
