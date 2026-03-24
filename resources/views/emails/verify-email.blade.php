<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-pasta verifikācija</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #0F1931;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0F1931;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .greeting {
            font-size: 18px;
            color: #2D4159;
            margin-bottom: 15px;
        }
        .code-box {
            background-color: #0F1931;
            color: #E2D4BB;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        .info-text {
            background-color: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #2D4159;
            margin: 15px 0;
            color: #555;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #888;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            background-color: #0F1931;
            color: #E2D4BB;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2D4159;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Galvene identificē sistēmu un vēstules mērķi. --}}
        <div class="header">
            <h1>{{ $appName }}</h1>
            <p style="color: #666; margin: 10px 0 0 0;">E-pasta verifikācija</p>
        </div>

        <div class="content">
            {{-- Sveiciens izmanto vārdu, ja tas aizpildīts, pretējā gadījumā lietotājvārdu. --}}
            <div class="greeting">
                Sveiki, {{ $lietotajs->vards ?? $lietotajs->lietotajvards }}!
            </div>

            <p>
                Jūs saņēmāt šo e-pastu, jo reģistrējāties sistēmā {{ $appName }}.
            </p>

            <p>
                Lai pabeigtu reģistrāciju un aktivizētu savu kontu, lūdzu, izmantojiet zemāk norādīto verifikācijas kodu:
            </p>

            {{-- Galvenā darbība e-pastā ir koda parādīšana skaidri redzamā blokā. --}}
            <div class="code-box">
                {{ $verificationCode }}
            </div>

            <div class="info-text">
                <strong>⏱️ Svarīgi:</strong> Šis kods ir derīgs nākamās 24 stundas. Pēc tam jums būs jāpieprasa jauns kods.
            </div>

            <p>
                <strong>Kā patērēt verifikācijas kodu:</strong>
            </p>
            <ol>
                <li>Atgriezieties uz reģistrācijas lapu</li>
                <li>Ievadiet savu e-pasta adresi</li>
                <li>Ievadiet verifikācijas kodu: <strong style="font-family: 'Courier New', monospace;">{{ $verificationCode }}</strong></li>
                <li>Noklikšķiniet uz "Apstiprināt e-pastu"</li>
            </ol>

            <p style="color: #888; font-size: 14px;">
                Ja jūs nereģistrējāties {{ $appName }}, vienkārši ignorējiet šo e-pastu.
            </p>
        </div>

        <div class="footer">
            <p>
                © {{ date('Y') }} {{ $appName }}. Visas tiesības paturētas.<br>
                Ja jums ir jautājumi, sazinieties ar mūsu atbalsta komandu.
            </p>
        </div>
    </div>
</body>
</html>
