<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>RCB Inventāra uzskaite</title>
 
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
   
    <meta name="theme-color" content="#0F1931">
    <!-- Iekļauta data-URI favicon (rezerves variants) -->
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Ikonas un Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Tumšā tēma + bloom pogas + dekoratīvās formas -->
    <style>
        :root{
            --navy: #0F1931;
            --navy-2: #2D4159;
            --maroon: #2D4159;
            --maroon-2: #2D4159;
            --accent: #E2D4BB;
        }

        html,body{
            height:100%;
            margin:0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: radial-gradient(1200px 800px at 10% 20%, rgba(45, 65, 89, 0.24), transparent 8%),
                        radial-gradient(1000px 600px at 90% 80%, rgba(45, 65, 89, 0.28), transparent 10%),
                        linear-gradient(180deg, var(--navy) 0%, var(--maroon) 100%);
            color: var(--accent);
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        /* Dekoratīvas izpludinātas formas aiz satura */
        .page-shapes{
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: visible;
        }
        .page-shapes .blob{
            position: absolute;
            filter: blur(24px) saturate(120%);
            opacity: 0.4;
            transform: translate3d(0,0,0);
            mix-blend-mode: screen;
            border-radius: 50%;
        }
        .page-shapes .b1{
            width: 260px;
            height: 260px;
            left: 5%;
            top: 2%;
            background: radial-gradient(circle at 30% 30%, rgba(45, 65, 89, 0.72), rgba(45, 65, 89, 0.22) 55%, transparent 85%);
        }
        .page-shapes .b2{
            width: 240px;
            height: 240px;
            right: 2%;
            bottom: 20%;
            background: radial-gradient(circle at 70% 70%, rgba(45, 65, 89, 0.7), rgba(45, 65, 89, 0.2) 55%, transparent 85%);
        }
        .page-shapes .b3{
            width: 180px;
            height: 180px;
            right: 8%;
            top: 15%;
            background: radial-gradient(circle at 30% 70%, rgba(45, 65, 89, 0.55), rgba(45, 65, 89, 0.16) 60%, transparent 85%);
            opacity: 0.35;
            filter: blur(16px);
        }

        /* Izkārtojuma slāņi */
        header, main, footer{
            position: relative;
            z-index: 2; /* virs formām */
        }

        .container{
            /* padding-top: 3.5rem;
            padding-bottom: 3.5rem; */
        }



        header h1{
            margin:0;
            font-size:1.75rem;
            letter-spacing:0.6px;
            color: var(--accent);
            text-shadow: 
            0 0 10px rgba(45, 65, 89, 0.7),
            0 0 20px rgba(45, 65, 89, 0.75),
            0 0 30px rgba(45, 65, 89, 0.8),
            0 6px 20px rgba(15, 25, 49, 0.8);
        }

        /* Nodrošina, ka virsraksti ir salasāmi uz tumša fona */
        h2, h3, h4, h5, h6 {
            color: var(--accent);
        }

        /* Kartītes stils galvenajam saturam */
        .card-surface{
            border: 1px solid rgba(45, 65, 89, 0.42);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(15, 25, 49, 0.7), inset 0 1px 0 rgba(45, 65, 89, 0.32);
            backdrop-filter: blur(6px) saturate(120%);
        }

        /* Bloom pogas stils */
        .bloom-button{
            display:inline-block;
            background: linear-gradient(90deg, var(--navy-2) 0%, var(--maroon) 50%, var(--maroon-2) 100%);
            color: var(--accent);
            border: none;
            padding: .6rem 1rem;
            border-radius: 999px;
            font-weight:600;
            letter-spacing: .4px;
            box-shadow:
                0 6px 18px rgba(15, 25, 49, 0.35),
                0 0 12px rgba(45, 65, 89, 0.2),
                inset 0 1px 0 rgba(226, 212, 187, 0.1);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
            cursor: pointer;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }
        .bloom-button:focus{
            outline: 3px solid rgba(45, 65, 89, 0.35);
            outline-offset: 4px;
        }
        .bloom-button:hover{
            transform: translateY(-4px) scale(1.02);
            box-shadow:
                0 16px 40px rgba(15, 25, 49, 0.45),
                0 0 40px rgba(45, 65, 89, 0.28),
                inset 0 1px 0 rgba(226, 212, 187, 0.14);
            filter: saturate(120%) brightness(1.06);
        }
        .bloom-button:active{
            transform: translateY(-1px) scale(0.995);
            box-shadow:
                0 8px 22px rgba(15, 25, 49, 0.32),
                0 0 18px rgba(45, 65, 89, 0.2);
        }

        /* Neliels palīgstils */
        .spaced{
            gap: .75rem;
            display:inline-flex;
            align-items:center;
        }

        /* Tabulas stils sarakstu lapām */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--accent);
        }
        .data-table th,
        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(226, 212, 187, 0.26);
        }
        .data-table th {
            font-weight: 600;
            border-bottom: 2px solid rgba(45, 65, 89, 0.45);
            text-align: left;
            letter-spacing: 0.02em;
        }
        .data-table tbody tr {
            background: rgba(45, 65, 89, 0.45);
            transition: background 0.2s ease;
        }
        .data-table tbody tr:hover {
            background: rgba(45, 65, 89, 0.5);
        }
        .data-table .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        /* Meklēšanas un kārtošanas vadīklas */
        .table-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .table-search-input {
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid rgba(45, 65, 89, 0.65);
            background: rgba(45, 65, 89, 0.5);
            color: var(--accent);
            min-width: 240px;
        }
        .table-search-input:focus {
            outline: 2px solid rgba(45, 65, 89, 0.7);
        }

        /* Formu lauki pielāgoti tumšajai sarakstu lapu tēmai */
        .form-control,
        .form-select {
            background: rgba(45, 65, 89, 0.5);
            border: 1px solid rgba(45, 65, 89, 0.65);
            color: var(--accent);
            border-radius: 999px;
        }

        .form-control:focus,
        .form-select:focus {
            outline: 2px solid rgba(45, 65, 89, 0.55);
            box-shadow: none;
        }

        .form-label {
            color: rgba(226, 212, 187, 0.95);
        }

        .btn,
        .btn-primary,
        .btn-secondary {
            background: linear-gradient(90deg, var(--navy-2) 0%, var(--maroon) 50%, var(--maroon-2) 100%);
            color: var(--accent);
            border: none;
            padding: .55rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            cursor: pointer;
            box-shadow:
                0 6px 18px rgba(15, 25, 49, 0.35),
                0 0 12px rgba(45, 65, 89, 0.2),
                inset 0 1px 0 rgba(226, 212, 187, 0.12);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }

        .btn:hover {
            transform: translateY(-2px) scale(1.01);
            filter: saturate(120%) brightness(1.06);
        }

        .btn:active {
            transform: translateY(-1px) scale(0.99);
        }

        .no-results-message {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .data-table th.sortable {
            cursor: pointer;
            position: relative;
            user-select: none;
            color: var(--accent);
        }
        .data-table th.sortable a {
            color: var(--accent);
            text-decoration: none;
        }
        .data-table th.sortable:hover a {
            color: var(--accent);
            text-decoration: underline;
        }
        .data-table th.sortable::after {
            content: "▾";
            font-size: 0.62rem;
            margin-left: 6px;
            opacity: 0.65;
            line-height: 1;
            vertical-align: middle;
        }
        .data-table th.sortable.sorted-asc::after {
            content: "▴";
        }
        .data-table th.sortable.sorted-desc::after {
            content: "▾";
        }
        
        /* Lapošana */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .pagination li {
            margin: 0;
        }
        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 0.7rem;
            border-radius: 999px;
            border: 1px solid rgba(45, 65, 89, 0.62);
            background: rgba(45, 65, 89, 0.5);
            color: var(--accent);
            text-decoration: none;
            min-width: 36px;
            font-size: 0.9rem;
            line-height: 1;
            text-align: center;
        }
        .pagination li.active span {
            background: rgba(45, 65, 89, 0.22);
            border-color: rgba(45, 65, 89, 0.65);
            font-weight: 600;
        }
        .pagination li.disabled span {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .pagination li a:hover {
            background: rgba(226, 212, 187, 0.16);
            border-color: rgba(226, 212, 187, 0.5);
        }

        /* Kartītēs balstīts saraksta stils (tabulai līdzīgas kartītes) */
        .card-table {
            display: grid;
            gap: 10px;
        }
        .card-table-header,
        .card-table-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr;
            align-items: center;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(45, 65, 89, 0.62);
            background: rgba(45, 65, 89, 0.45);
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .card-table-header {
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: rgba(226, 212, 187, 0.92);
            border-bottom: 2px solid rgba(45, 65, 89, 0.8);
            background: rgba(45, 65, 89, 0.4);
        }
        .card-table-row:hover {
            background: rgba(45, 65, 89, 0.5);
            transform: translateY(-1px);
        }
        .card-table-row .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Kartīšu saraksta rindas, stilizētas kā tabulas rindas */
        .table-card {
            background: rgba(45, 65, 89, 0.45);
            border: 1px solid rgba(45, 65, 89, 0.62);
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(15, 25, 49, 0.35);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            animation: fadeIn 0.25s ease;
        }
        .table-card:hover {
            background: rgba(45, 65, 89, 0.5);
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(15, 25, 49, 0.5);
        }
        .table-card .card-body {
            padding: 14px 16px;
            display: grid;
            grid-template-columns: 1fr;
            row-gap: 12px;
        }
        .table-card .card-body > div {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .table-card .card-text:first-child {
            font-weight: 600;
            color: rgba(226, 212, 187, 0.95);
        }
        .table-card .card-text + .card-text {
            opacity: 0.75;
            font-size: 0.9rem;
            color: rgba(226, 212, 187, 0.82);
        }
        .table-card .auth-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .table-card .auth-links a {
            flex-shrink: 0;
            min-width: 40px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mazās bloom pogas variants */
        .bloom-button.sm {
            padding: 0.45rem 0.8rem;
            font-size: 0.9rem;
        }

        /* Responsīvie pielāgojumi */
        @media (max-width: 768px){
            header h1{ font-size:1.25rem; }
            .card-surface{ padding:1rem; border-radius:12px; }
            .card-table-header,
            .card-table-row {
                grid-template-columns: 1.5fr 1fr;
            }
        }
        @media print {
            /* Document-like styling for print */
            *, *::before, *::after {
                background: transparent !important;
                color: #000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
                filter: none !important;
            }

            html, body {
                background: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
                height: auto;
            }

            /* Hide non-printable elements */
            .page-shapes,
            header,
            footer,
            .auth-links,
            .bloom-button,
            .btn,
            button,
            .table-controls,
            .pagination,
            .no-results-message,
            .actions a,
            a[onclick*="print"] {
                display: none !important;
            }

            /* Document header styling */
            main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100%;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 20px !important;
                margin: 0 !important;
            }

            .card-surface {
                border: none !important;
                background: white !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            /* Document title styling */
            p:first-of-type {
                font-size: 16pt !important;
                font-weight: bold !important;
                margin-bottom: 20px !important;
                margin-top: 0 !important;
            }

            h2 {
                font-size: 14pt !important;
                font-weight: bold !important;
                margin-top: 0 !important;
                margin-bottom: 12px !important;
                border-bottom: 2px solid #000 !important;
                padding-bottom: 8px !important;
            }

            /* Professional table styling */
            .data-table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 20px 0 !important;
                font-size: 11pt !important;
            }

            .data-table th {
                background: #333 !important;
                color: white !important;
                padding: 10px 8px !important;
                text-align: left !important;
                font-weight: bold !important;
                border: 1px solid #000 !important;
                page-break-inside: avoid;
            }

            .data-table th.sortable::after {
                content: '' !important;
            }

            .data-table td {
                padding: 8px !important;
                border: 1px solid #ccc !important;
                color: #000 !important;
            }

            .data-table tbody tr:nth-child(even) {
                background: #f9f9f9 !important;
            }

            .data-table tbody tr {
                page-break-inside: avoid;
            }

            /* Hide action columns in print */
            .data-table td:last-child,
            .data-table th:last-child {
                display: none !important;
            }

            /* Card styling for detail pages */
            .card {
                border: 1px solid #000 !important;
                background: white !important;
                color: black !important;
                margin: 20px 0 !important;
                page-break-inside: avoid;
            }

            .card-body {
                padding: 16px !important;
            }

            .card-title {
                font-size: 12pt !important;
                font-weight: bold !important;
                margin-bottom: 12px !important;
                border-bottom: 1px solid #000 !important;
                padding-bottom: 8px !important;
            }

            .card-text {
                margin: 6px 0 !important;
                font-size: 11pt !important;
            }

            .card-text strong {
                font-weight: bold !important;
            }

            /* Page breaks and margins */
            @page {
                margin: 15mm;
                size: A4;
            }

            /* Ensure good print quality */
            img {
                max-width: 100% !important;
                page-break-inside: avoid;
            }

            hr {
                border: none !important;
                border-top: 1px solid #000 !important;
                margin: 12px 0 !important;
            }
        }
    </style>
</head>
<body>
    <!-- <div class="page-shapes" aria-hidden="true">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
    </div> -->



<!-- Atjaunināts header elements ar jauno klasi -->
<header class="small">
    <h1>RCB inventāra uzskaite</h1>
        <div>
            
            @include('inc.header')
        </div>


    </header>

    <main style="padding-bottom: 100px" class="container">
        <section class="card-surface">
            @yield('content')
        </section>
    </main>

  

    <footer class="container" style="margin-top:1.25rem;">
        @include('inc.footer')
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>

    <script>
        (function(){
            const initDatePickers = () => {
                if (!window.flatpickr) return;

                const lvLocale = (window.flatpickr.l10ns && window.flatpickr.l10ns.lv)
                    ? window.flatpickr.l10ns.lv
                    : 'lv';

                document.querySelectorAll('input[type="date"], input[data-datepicker="lv"]').forEach((input) => {
                    if (input.dataset.fpInitialized === '1') return;

                    const currentValue = input.value;
                    input.setAttribute('data-datepicker', 'lv');
                    input.type = 'text';
                    input.autocomplete = 'off';

                    window.flatpickr(input, {
                        locale: lvLocale,
                        dateFormat: 'Y-m-d',
                        allowInput: true,
                        disableMobile: true,
                        defaultDate: currentValue || null,
                    });

                    input.dataset.fpInitialized = '1';
                });
            };

            const normalize = (str) => (str || '').toString().trim().toLowerCase();

            const applyFilter = (table, query, noResultsEl) => {
                const rows = Array.from(table.tBodies[0].rows);
                const matched = rows.filter(row => {
                    const text = Array.from(row.cells)
                        .map(cell => cell.textContent)
                        .join(' ');
                    return normalize(text).includes(normalize(query));
                });

                rows.forEach(row => row.style.display = 'none');
                matched.forEach(row => row.style.display = 'table-row');

                if (noResultsEl) {
                    noResultsEl.style.display = matched.length === 0 ? 'inline' : 'none';
                }

                return matched;
            };

            const sortTable = (table, columnIndex, asc) => {
                const tbody = table.tBodies[0];
                const rows = Array.from(tbody.rows);
                const collator = new Intl.Collator(undefined, { numeric: true, sensitivity: 'base' });

                rows.sort((a, b) => {
                    const aText = normalize(a.cells[columnIndex]?.textContent ?? '');
                    const bText = normalize(b.cells[columnIndex]?.textContent ?? '');
                    const result = collator.compare(aText, bText);
                    return asc ? result : -result;
                });

                rows.forEach(row => tbody.appendChild(row));
            };

            const initTableControls = (container) => {
                const table = container.querySelector('table.data-table');
                if (!table) return;

                const searchInput = container.querySelector('.table-search-input');
                const noResults = container.querySelector('.no-results-message');

                if (searchInput) {
                    searchInput.addEventListener('input', () => {
                        applyFilter(table, searchInput.value, noResults);
                    });
                }

                const headers = Array.from(table.querySelectorAll('th.sortable'));
                headers.forEach((th, index) => {
                    th.addEventListener('click', () => {
                        const current = th.classList.contains('sorted-asc') ? 'asc' : th.classList.contains('sorted-desc') ? 'desc' : null;
                        const nextAsc = current !== 'asc';

                        headers.forEach(h => h.classList.remove('sorted-asc', 'sorted-desc'));
                        th.classList.add(nextAsc ? 'sorted-asc' : 'sorted-desc');

                        sortTable(table, index, nextAsc);

                        // Pēc kārtošanas atkārtoti piemēro filtru, lai paslēptās rindas paliek paslēptas.
                        if (searchInput && searchInput.value.trim()) {
                            applyFilter(table, searchInput.value, noResults);
                        }
                    });
                });
            };

            const initAllTableControls = () => {
                document.querySelectorAll('.table-controls').forEach(initTableControls);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    initDatePickers();
                    initAllTableControls();
                });
            } else {
                initDatePickers();
                initAllTableControls();
            }
        })();
    </script>
</body>
</html>

