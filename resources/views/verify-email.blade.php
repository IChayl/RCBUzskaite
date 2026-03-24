<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-pasta verifikācija</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#0F1931">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root{
            --navy: #0F1931;
            --navy-2: #2D4159;
            --maroon: #594435;
            --maroon-2: #C89768;
            --accent: #E2D4BB;
            --card-bg: rgba(226, 212, 187, 0.06);
            --muted-white: rgba(226, 212, 187, 0.9);
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

        .center-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card.custom {
            width: 450px;
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

        .card.custom h3 {
            color: rgba(226, 212, 187, 0.9);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .card.custom p {
            color: rgba(226, 212, 187, 0.75);
            font-size: 0.95rem;
        }

        label.form-label {
            color: rgba(226, 212, 187, 0.85);
            font-weight: 500;
        }

        input.form-control {
            background: rgba(226, 212, 187, 0.03);
            border: 1px solid rgba(226, 212, 187, 0.06);
            color: var(--muted-white);
            text-align: center;
            font-size: 1.3rem;
            letter-spacing: 0.2em;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        input.form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.15rem rgba(45, 65, 89, 0.15);
            background: rgba(226, 212, 187, 0.04);
            color: var(--muted-white);
        }

        input.form-control::placeholder {
            color: rgba(226, 212, 187, 0.4);
            opacity: 0.7;
        }

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

        .btn-secondary {
            background: rgba(226, 212, 187, 0.1);
            border: 1px solid rgba(226, 212, 187, 0.2);
            color: var(--accent);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 180ms ease;
        }

        .btn-secondary:hover {
            background: rgba(226, 212, 187, 0.15);
            border-color: rgba(226, 212, 187, 0.3);
            color: var(--accent);
        }

        .card .text-center a {
            color: rgba(226, 212, 187, 0.85);
            text-decoration: none;
        }

        .card .text-center a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .alert-danger {
            background: linear-gradient(180deg, rgba(45, 65, 89, 0.15), rgba(45, 65, 89, 0.08));
            border-color: rgba(226, 212, 187, 0.06);
            color: #E2D4BB;
        }

        .alert-info {
            background: linear-gradient(180deg, rgba(45, 65, 89, 0.15), rgba(45, 65, 89, 0.08));
            border-color: rgba(226, 212, 187, 0.06);
            color: #E2D4BB;
        }

        .info-box {
            background: rgba(226, 212, 187, 0.04);
            border-left: 3px solid var(--accent);
            padding: 1rem;
            border-radius: 6px;
            margin: 1.5rem 0;
            font-size: 0.95rem;
        }

        .icon-wrapper {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 3rem;
            color: var(--accent);
            opacity: 0.8;
        }

        .resend-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .resend-link form {
            display: inline;
        }

        @media (max-width: 500px){
            .card.custom { width: 100%; padding: 1.25rem; border-radius: 10px; }
        }
    </style>
</head>

<body>

    <div class="center-wrap">
        <div class="card custom p-4">
            {{-- Ikona vizuāli parāda, ka šis solis ir saistīts ar e-pasta apstiprināšanu. --}}
            <div class="icon-wrapper">
                <i class="fas fa-envelope"></i>
            </div>

            <h2 class="text-center mb-2">E-pasta verifikācija</h2>
            <p class="text-center" style="color: rgba(226, 212, 187, 0.75); margin-bottom: 1.5rem;">
                Ievadiet 6 ciparu kodu, kas tika nosūtīts uz jūsu e-pastu
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($message)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> {{ $message }}
                </div>
            @endif

            {{-- Šajā formā lietotājs ievada uz e-pastu nosūtīto sešciparu kodu. --}}
            <form method="POST" action="{{ route('verify-email.submit') }}" novalidate>
                @csrf
                {{-- Slēptais lauks pasaka serverim, kuram lietotājam jāpārbauda kods. --}}
                <input type="hidden" name="user_id" value="{{ $user->lietotajs_id }}">

                <div class="mb-3">
                    <label for="verification_code" class="form-label">Verifikācijas kods</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="verification_code" 
                        name="verification_code" 
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required 
                        placeholder="000000"
                        autocomplete="off"
                    >
                </div>

                <div class="info-box">
                    <i class="fas fa-clock"></i> 
                    Kods ir derīgs <strong>24 stundas</strong>. Pēc tam jums būs jāpieprasa jauns kods.
                </div>

                <button type="submit" class="btn btn-bloom w-100 mb-2">
                    <i class="fas fa-check"></i> Apstiprināt e-pastu
                </button>
            </form>

            {{-- Atsevišķa forma ļauj pieprasīt jaunu kodu, ja iepriekšējais nav saņemts vai ir beidzies. --}}
            <div class="resend-link">
                <p style="color: rgba(226, 212, 187, 0.7); font-size: 0.9rem; margin-bottom: 0.5rem;">
                    Neesi saņēmis kodu?
                </p>
                <form method="POST" action="{{ route('verify-email.resend') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->lietotajs_id }}">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i> Atkārtoti nosūtīt kodu
                    </button>
                </form>
            </div>

            <div class="text-center mt-3">
                <a href="/Login">
                    <i class="fas fa-arrow-left"></i> Atpakaļ uz pieteikšanos
                </a>
            </div>
        </div>
    </div>

</body>
</html>
