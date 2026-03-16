<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>RCB Inventāra uzskaite</title>
 
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" sizes="any">
   
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
   
    <meta name="theme-color" content="#0D203A">
    <!-- Inline data-URI favicon (fallback) -->
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Icons & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Navy-Maroon theme + bloom buttons + decorative shapes -->
    <style>
        :root{
            --navy: #0D203A;
            --navy-2: #243B53;
            --maroon: #6B7C8C;
            --maroon-2: #D1C3A5;
            --accent: #FAF8F2;
        }

        html,body{
            height:100%;
            margin:0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: radial-gradient(1200px 800px at 10% 20%, rgba(36, 59, 83, 0.24), transparent 8%),
                        radial-gradient(1000px 600px at 90% 80%, rgba(36, 59, 83, 0.28), transparent 10%),
                        linear-gradient(180deg, var(--navy) 0%, var(--maroon) 100%);
            color: var(--accent);
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        /* Decorative blurred shapes behind content */
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
            background: radial-gradient(circle at 30% 30%, rgba(36, 59, 83, 0.72), rgba(36, 59, 83, 0.22) 55%, transparent 85%);
        }
        .page-shapes .b2{
            width: 240px;
            height: 240px;
            right: 2%;
            bottom: 20%;
            background: radial-gradient(circle at 70% 70%, rgba(36, 59, 83, 0.7), rgba(36, 59, 83, 0.2) 55%, transparent 85%);
        }
        .page-shapes .b3{
            width: 180px;
            height: 180px;
            right: 8%;
            top: 15%;
            background: radial-gradient(circle at 30% 70%, rgba(36, 59, 83, 0.55), rgba(36, 59, 83, 0.16) 60%, transparent 85%);
            opacity: 0.35;
            filter: blur(16px);
        }

        /* Layout layers */
        header, main, footer{
            position: relative;
            z-index: 2; /* above shapes */
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
            0 0 10px rgba(36, 59, 83, 0.7),
            0 0 20px rgba(36, 59, 83, 0.75),
            0 0 30px rgba(36, 59, 83, 0.8),
            0 6px 20px rgba(13, 32, 58, 0.8);
        }

        /* Ensure headings are readable on dark background */
        h2, h3, h4, h5, h6 {
            color: var(--accent);
        }

        /* Card style for main content to create shape */
        .card-surface{
            border: 1px solid rgba(36, 59, 83, 0.42);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(13, 32, 58, 0.7), inset 0 1px 0 rgba(36, 59, 83, 0.32);
            backdrop-filter: blur(6px) saturate(120%);
        }

        /* Bloom button style */
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
                0 6px 18px rgba(13, 32, 58, 0.35),
                0 0 12px rgba(36, 59, 83, 0.2),
                inset 0 1px 0 rgba(209, 195, 165, 0.1);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
            cursor: pointer;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }
        .bloom-button:focus{
            outline: 3px solid rgba(36, 59, 83, 0.35);
            outline-offset: 4px;
        }
        .bloom-button:hover{
            transform: translateY(-4px) scale(1.02);
            box-shadow:
                0 16px 40px rgba(13, 32, 58, 0.45),
                0 0 40px rgba(36, 59, 83, 0.28),
                inset 0 1px 0 rgba(209, 195, 165, 0.14);
            filter: saturate(120%) brightness(1.06);
        }
        .bloom-button:active{
            transform: translateY(-1px) scale(0.995);
            box-shadow:
                0 8px 22px rgba(13, 32, 58, 0.32),
                0 0 18px rgba(36, 59, 83, 0.2);
        }

        /* Small utility */
        .spaced{
            gap: .75rem;
            display:inline-flex;
            align-items:center;
        }

        /* Table styling for list pages */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--accent);
        }
        .data-table th,
        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(209, 195, 165, 0.26);
        }
        .data-table th {
            font-weight: 600;
            border-bottom: 2px solid rgba(36, 59, 83, 0.45);
            text-align: left;
            letter-spacing: 0.02em;
        }
        .data-table tbody tr {
            background: rgba(36, 59, 83, 0.45);
            transition: background 0.2s ease;
        }
        .data-table tbody tr:hover {
            background: rgba(36, 59, 83, 0.5);
        }
        .data-table .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        /* Search & sort controls */
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
            border: 1px solid rgba(36, 59, 83, 0.65);
            background: rgba(36, 59, 83, 0.5);
            color: var(--accent);
            min-width: 240px;
        }
        .table-search-input:focus {
            outline: 2px solid rgba(36, 59, 83, 0.7);
        }

        /* Form controls match the dark theme used on list pages */
        .form-control,
        .form-select {
            background: rgba(36, 59, 83, 0.5);
            border: 1px solid rgba(36, 59, 83, 0.65);
            color: var(--accent);
            border-radius: 999px;
        }

        .form-control:focus,
        .form-select:focus {
            outline: 2px solid rgba(36, 59, 83, 0.55);
            box-shadow: none;
        }

        .form-label {
            color: rgba(209, 195, 165, 0.95);
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
                0 6px 18px rgba(13, 32, 58, 0.35),
                0 0 12px rgba(36, 59, 83, 0.2),
                inset 0 1px 0 rgba(209, 195, 165, 0.12);
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
        
        /* Pagination */
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
            border: 1px solid rgba(36, 59, 83, 0.62);
            background: rgba(36, 59, 83, 0.5);
            color: var(--accent);
            text-decoration: none;
            min-width: 36px;
            font-size: 0.9rem;
            line-height: 1;
            text-align: center;
        }
        .pagination li.active span {
            background: rgba(36, 59, 83, 0.22);
            border-color: rgba(36, 59, 83, 0.65);
            font-weight: 600;
        }
        .pagination li.disabled span {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .pagination li a:hover {
            background: rgba(209, 195, 165, 0.16);
            border-color: rgba(209, 195, 165, 0.5);
        }

        /* Card-based list styling (table-like cards) */
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
            border: 1px solid rgba(36, 59, 83, 0.62);
            background: rgba(36, 59, 83, 0.45);
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .card-table-header {
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: rgba(209, 195, 165, 0.92);
            border-bottom: 2px solid rgba(36, 59, 83, 0.8);
            background: rgba(36, 59, 83, 0.4);
        }
        .card-table-row:hover {
            background: rgba(36, 59, 83, 0.5);
            transform: translateY(-1px);
        }
        .card-table-row .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Card list rows styled like table rows */
        .table-card {
            background: rgba(36, 59, 83, 0.45);
            border: 1px solid rgba(36, 59, 83, 0.62);
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(13, 32, 58, 0.35);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            animation: fadeIn 0.25s ease;
        }
        .table-card:hover {
            background: rgba(36, 59, 83, 0.5);
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(13, 32, 58, 0.5);
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
            color: rgba(209, 195, 165, 0.95);
        }
        .table-card .card-text + .card-text {
            opacity: 0.75;
            font-size: 0.9rem;
            color: rgba(209, 195, 165, 0.82);
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

        /* Small bloom button variant */
        .bloom-button.sm {
            padding: 0.45rem 0.8rem;
            font-size: 0.9rem;
        }

        /* Responsive tweaks */
        @media (max-width: 768px){
            header h1{ font-size:1.25rem; }
            .card-surface{ padding:1rem; border-radius:12px; }
            .card-table-header,
            .card-table-row {
                grid-template-columns: 1.5fr 1fr;
            }
        }
        @media print {
            body, html { background: #FAF8F2 !important; color: #0D203A !important; }
            .page-shapes, header, footer, .auth-links a, .bloom-button, .btn { display: none !important; }
            .card-surface { border: none !important; box-shadow: none !important; background: transparent !important; }
            .data-table, .data-table th, .data-table td { color: #0D203A !important; border-color: #243B53 !important; }
            .data-table th.sortable::after { content: '' !important; }
            .no-results-message { display: none !important; }
            * { text-shadow: none !important; box-shadow: none !important; filter: none !important; }
            .container { padding: 0 !important; margin: 0 !important; }
            main { padding: 0 !important; }
            .card-surface { margin: 0 !important; border: 1px solid #243B53 !important; border-radius: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="page-shapes" aria-hidden="true">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
    </div>



<!-- Update the header element to include the new class -->
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

    <script>
        (function(){
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

                        // Re-apply filter after sort so hidden rows stay hidden.
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
                document.addEventListener('DOMContentLoaded', initAllTableControls);
            } else {
                initAllTableControls();
            }
        })();
    </script>
</body>
</html>

