<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>RCB Inventāra uzskaite</title>
 
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
   
    <meta name="theme-color" content="#0F1931">
    <!-- Iekļauta data-URI favicon (rezerves variants) -->

    <script>
        (function () {
            const key = 'rcb-theme';
            const stored = localStorage.getItem(key);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored === 'light' || stored === 'dark' ? stored : (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    
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
            --text-main: #E2D4BB;
            --bg-gradient-start: #0F1931;
            --bg-gradient-end: #2D4159;
            --shape-1: rgba(45, 65, 89, 0.24);
            --shape-2: rgba(45, 65, 89, 0.28);
            --surface-bg: rgba(45, 65, 89, 0.45);
            --surface-bg-soft: rgba(45, 65, 89, 0.5);
            --surface-border: rgba(45, 65, 89, 0.62);
            --table-border: rgba(226, 212, 187, 0.26);
            --header-bg: linear-gradient(90deg, #0F1931, #2D4159);
            --header-shadow: 0 4px 12px rgba(15, 25, 49, 0.45);
            --footer-bg: linear-gradient(90deg, #0F1931, #2D4159);
            --footer-shadow: 0 -4px 10px rgba(15, 25, 49, 0.4);
            --button-text: #E2D4BB;
            --button-outline: rgba(45, 65, 89, 0.35);
            --title-shadow:
                0 0 10px rgba(45, 65, 89, 0.7),
                0 0 20px rgba(45, 65, 89, 0.75),
                0 0 30px rgba(45, 65, 89, 0.8),
                0 6px 20px rgba(15, 25, 49, 0.8);
        }

        html[data-theme="light"] {
            --navy: #edf4ff;
            --navy-2: #1a52b0;
            --maroon: #1645a0;
            --maroon-2: #2965cc;
            --accent: #091828;
            --text-main: #091828;
            --bg-gradient-start: #edf4ff;
            --bg-gradient-end: #cfe0f9;
            --shape-1: rgba(40, 100, 210, 0.14);
            --shape-2: rgba(70, 130, 220, 0.16);
            --surface-bg: rgba(255, 255, 255, 0.97);
            --surface-bg-soft: rgba(255, 255, 255, 1);
            --surface-border: rgba(50, 100, 190, 0.28);
            --table-border: rgba(40, 85, 160, 0.18);
            --header-bg: linear-gradient(90deg, #153a82, #1e5bc6);
            --header-shadow: 0 6px 22px rgba(12, 35, 95, 0.42);
            --footer-bg: linear-gradient(90deg, #153a82, #1e5bc6);
            --footer-shadow: 0 -4px 14px rgba(12, 35, 95, 0.34);
            --button-text: #ffffff;
            --button-outline: rgba(22, 69, 160, 0.4);
            --title-shadow: 0 2px 10px rgba(25, 60, 150, 0.22);
        }

        html,body{
            height:100%;
            margin:0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: radial-gradient(1200px 800px at 10% 20%, var(--shape-1), transparent 8%),
                        radial-gradient(1000px 600px at 90% 80%, var(--shape-2), transparent 10%),
                        linear-gradient(180deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            color: var(--text-main);
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
            text-shadow: var(--title-shadow);
        }

        /* Nodrošina, ka virsraksti ir salasāmi uz tumša fona */
        h2, h3, h4, h5, h6 {
            color: var(--accent);
        }

        /* Kartītes stils galvenajam saturam */
        .card-surface{
            border: 1px solid var(--surface-border);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(15, 25, 49, 0.35), inset 0 1px 0 rgba(45, 65, 89, 0.2);
            backdrop-filter: blur(6px) saturate(120%);
            background: var(--surface-bg);
        }

        /* Bloom pogas stils */
        .bloom-button{
            display:inline-block;
            background: linear-gradient(90deg, var(--navy-2) 0%, var(--maroon) 50%, var(--maroon-2) 100%);
            color: var(--button-text);
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
            outline: 3px solid var(--button-outline);
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

        html[data-theme="light"] .bloom-button {
            box-shadow:
                0 3px 10px rgba(12, 35, 95, 0.22),
                0 0 6px rgba(30, 91, 198, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.22);
        }
        html[data-theme="light"] .bloom-button:hover {
            box-shadow:
                0 7px 18px rgba(12, 35, 95, 0.28),
                0 0 12px rgba(30, 91, 198, 0.16),
                inset 0 1px 0 rgba(255, 255, 255, 0.26);
        }
        html[data-theme="light"] .bloom-button:active {
            box-shadow:
                0 4px 12px rgba(12, 35, 95, 0.22),
                0 0 8px rgba(30, 91, 198, 0.13);
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
            min-width: 1400px;
            border-collapse: collapse;
            color: var(--accent);
            table-layout: auto;
        }
        .table-wrap {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }
        .table-wrap--fit {
            overflow-x: hidden;
        }
        .movement-table {
            table-layout: fixed;
        }
        .data-table th,
        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--table-border);
            vertical-align: top;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
            overflow-wrap: break-word;
            word-break: break-word;
        }
        .data-table th:last-child,
        .data-table td:last-child {
            width: 230px;
            overflow: visible;
            text-overflow: clip;
        }
        .data-table th {
            font-weight: 600;
            border-bottom: 2px solid rgba(45, 65, 89, 0.45);
            text-align: left;
            letter-spacing: 0.02em;
        }
        .movement-table th,
        .movement-table td {
            white-space: nowrap;
        }
        .print-group-row {
            display: none;
        }
        .data-table tbody tr {
            background: var(--surface-bg);
            transition: background 0.2s ease;
        }
        .data-table tbody tr:hover {
            background: var(--surface-bg-soft);
        }
        .data-table .actions {
            display: flex;
            flex-wrap: nowrap;
            gap: 6px;
            align-items: center;
            white-space: nowrap;
            overflow: visible;
        }
        .data-table .actions > * {
            flex: 0 0 auto;
            min-width: 0;
        }
        .data-table .actions .bloom-button,
        .data-table .actions select {
            padding: 0.34rem 0.52rem;
            font-size: 0.78rem;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .icon-button {
            width: 34px;
            min-width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .icon-button i {
            font-size: 0.95rem;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .data-table .actions--inventory {
            gap: 4px;
        }
        .data-table .actions--inventory .bloom-button,
        .data-table .actions--inventory select {
            padding: 0.32rem 0.46rem;
            font-size: 0.74rem;
            max-width: 84px;
        }
        .data-table .actions--inventory .quick-action-select {
            min-width: 84px !important;
            width: 84px;
        }

        /* Meklēšanas un kārtošanas vadīklas */
        .table-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            overflow: visible;
            white-space: normal;
            min-width: 0;
        }
        .table-controls > * {
            flex: 0 1 auto;
            min-width: 0;
        }
        .table-controls label {
            flex: 0 0 auto;
            margin-bottom: 0;
        }
        .table-controls select {
            max-width: 165px;
            min-width: 110px;
            width: 100%;
            padding: 8px 10px !important;
            font-size: 0.9rem;
        }
        .table-controls .table-search-input {
            flex: 1 1 180px;
            min-width: 90px;
            width: auto;
            padding: 8px 10px;
            font-size: 0.9rem;
        }
        .table-controls input[type="date"].table-search-input {
            flex: 0 1 128px;
            width: 128px !important;
            min-width: 128px !important;
            max-width: 128px !important;
        }
        .table-controls .bloom-button,
        .table-controls button {
            flex: 0 0 auto;
            padding: 0.45rem 0.75rem;
            font-size: 0.88rem;
        }
        .table-controls span {
            flex: 0 0 auto;
            font-size: 0.88rem;
        }
        .print-options {
            display: none;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 12px;
            margin-left: 10px;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid var(--surface-border);
            background: var(--surface-bg-soft);
            color: var(--text-main);
            font-size: 0.82rem;
            box-shadow: 0 8px 18px rgba(15, 25, 49, 0.24);
        }
        .print-options.is-visible {
            display: inline-flex;
        }
        .print-options .print-options-title {
            font-weight: 700;
            letter-spacing: 0.01em;
            margin-right: 2px;
            color: var(--text-main);
        }
        .print-options label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            white-space: nowrap;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(45, 65, 89, 0.08);
        }
        .print-options input[type="number"] {
            width: 102px;
            border-radius: 999px;
            border: 1px solid var(--surface-border);
            background: var(--surface-bg);
            color: var(--text-main);
            padding: 4px 10px;
        }
        .print-options input[type="checkbox"] {
            width: 14px;
            height: 14px;
        }
        .print-options .print-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .print-options .print-pdf {
            display: none;
        }
        .confirm-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 1200;
            background: rgba(15, 25, 49, 0.55);
            backdrop-filter: blur(4px);
        }
        .confirm-overlay.is-visible {
            display: flex;
        }
        .confirm-dialog {
            width: min(460px, 100%);
            border-radius: 18px;
            border: 1px solid var(--surface-border);
            background: var(--surface-bg-soft);
            box-shadow: 0 18px 42px rgba(15, 25, 49, 0.45);
            padding: 18px;
            color: var(--text-main);
        }
        .confirm-title {
            margin: 0 0 8px;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--accent);
        }
        .confirm-message {
            margin: 0;
            color: var(--text-main);
            line-height: 1.5;
        }
        .confirm-actions {
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        html[data-theme="light"] .confirm-overlay {
            background: rgba(12, 35, 95, 0.36);
        }
        html[data-theme="light"] .confirm-dialog {
            background: #ffffff;
            border-color: rgba(26, 82, 176, 0.24);
            box-shadow: 0 16px 38px rgba(26, 82, 176, 0.22);
        }
        html[data-theme="light"] .confirm-title,
        html[data-theme="light"] .confirm-message {
            color: #091828;
        }
        html[data-theme="light"] .print-options {
            background: rgba(255, 255, 255, 0.98);
            border-color: rgba(26, 82, 176, 0.24);
            box-shadow: 0 8px 20px rgba(26, 82, 176, 0.14);
        }
        html[data-theme="light"] .print-options label {
            background: rgba(26, 82, 176, 0.08);
        }
        @media (max-width: 1400px) {
            .table-controls {
                gap: 6px;
            }
            .table-controls select {
                max-width: 145px;
                min-width: 96px;
                font-size: 0.85rem;
            }
            .table-controls .table-search-input {
                min-width: 78px;
                font-size: 0.85rem;
            }
            .table-controls input[type="date"].table-search-input {
                width: 118px !important;
                min-width: 118px !important;
                max-width: 118px !important;
            }
            .table-controls .bloom-button,
            .table-controls button,
            .table-controls span,
            .table-controls label {
                font-size: 0.82rem;
            }
        }
        @media (max-width: 992px) {
            .movement-table th,
            .movement-table td {
                padding: 10px 8px;
                font-size: 0.88rem;
            }
        }
        .inventory-toolbar {
            gap: 5px;
        }
        .inventory-toolbar select {
            max-width: 124px;
            min-width: 82px;
            padding: 7px 9px !important;
            font-size: 0.83rem;
        }
        .inventory-toolbar .inventory-search-scope {
            max-width: 106px;
            min-width: 78px;
        }
        .inventory-toolbar .inventory-search-input {
            flex: 1 1 110px;
            min-width: 88px;
            padding: 7px 9px;
            font-size: 0.83rem;
        }
        .inventory-toolbar input[type="date"].table-search-input {
            width: 104px !important;
            min-width: 104px !important;
            max-width: 104px !important;
            padding: 7px 8px;
            font-size: 0.81rem;
        }
        .inventory-toolbar .inventory-date-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }
        .inventory-toolbar .bloom-button,
        .inventory-toolbar button {
            padding: 0.42rem 0.62rem;
            font-size: 0.8rem;
        }
        .table-search-input {
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid var(--surface-border);
            background: var(--surface-bg-soft);
            color: var(--text-main);
            min-width: 0;
        }
        .table-search-input:focus {
            outline: 2px solid rgba(45, 65, 89, 0.7);
        }

        /* Formu lauki pielāgoti tumšajai sarakstu lapu tēmai */
        .form-control,
        .form-select {
            background: var(--surface-bg-soft);
            border: 1px solid var(--surface-border);
            color: var(--text-main);
            border-radius: 999px;
        }

        .form-control:focus,
        .form-select:focus {
            outline: 2px solid rgba(45, 65, 89, 0.55);
            box-shadow: none;
        }

        .form-control:disabled,
        .form-select:disabled,
        .form-control[readonly],
        .form-select[readonly] {
            color: #0F1931 !important;
            -webkit-text-fill-color: #0F1931;
            opacity: 1;
        }

        .form-label {
            color: rgba(226, 212, 187, 0.95);
        }

        html[data-theme="light"] .form-label {
            color: #1a2d46;
        }

        /* Datuma izvēlnes stils saskaņots ar pārējo interfeisu */
        .flatpickr-calendar {
            background: linear-gradient(180deg, rgba(15, 25, 49, 0.98) 0%, rgba(45, 65, 89, 0.96) 100%);
            border: 1px solid rgba(226, 212, 187, 0.16);
            border-radius: 18px;
            box-shadow:
                0 18px 42px rgba(15, 25, 49, 0.48),
                0 0 0 1px rgba(45, 65, 89, 0.28),
                inset 0 1px 0 rgba(226, 212, 187, 0.08);
            color: var(--accent);
            overflow: hidden;
        }

        .flatpickr-calendar.open,
        .flatpickr-calendar.inline {
            animation: pickerFadeIn 0.18s ease;
        }

        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: rgba(15, 25, 49, 0.98);
        }

        .flatpickr-calendar.arrowBottom:before,
        .flatpickr-calendar.arrowBottom:after {
            border-top-color: rgba(45, 65, 89, 0.96);
        }

        .flatpickr-months {
            padding: 10px 10px 6px;
        }

        .flatpickr-months .flatpickr-month,
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year,
        .flatpickr-weekday,
        span.flatpickr-weekday,
        .flatpickr-day,
        .flatpickr-time input,
        .flatpickr-time .flatpickr-am-pm {
            color: var(--accent);
        }

        .flatpickr-current-month {
            padding-top: 2px;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            background: rgba(226, 212, 187, 0.08);
            border: 1px solid rgba(226, 212, 187, 0.16);
            border-radius: 10px;
            box-shadow: none;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months:hover,
        .flatpickr-current-month input.cur-year:hover,
        .flatpickr-current-month .flatpickr-monthDropdown-months:focus,
        .flatpickr-current-month input.cur-year:focus {
            background: rgba(226, 212, 187, 0.14);
            outline: none;
        }

        .flatpickr-monthDropdown-months option {
            background: var(--navy);
            color: var(--accent);
        }

        .flatpickr-weekdays {
            background: rgba(15, 25, 49, 0.35);
            border-top: 1px solid rgba(226, 212, 187, 0.08);
            border-bottom: 1px solid rgba(226, 212, 187, 0.08);
        }

        .flatpickr-weekday {
            font-weight: 600;
            opacity: 0.88;
        }

        .flatpickr-day {
            border-radius: 12px;
            border: 1px solid transparent;
        }

        .flatpickr-day:hover,
        .flatpickr-day:focus {
            background: rgba(226, 212, 187, 0.14);
            border-color: rgba(226, 212, 187, 0.14);
        }

        .flatpickr-day.today {
            border-color: rgba(226, 212, 187, 0.52);
            background: rgba(226, 212, 187, 0.08);
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover {
            background: linear-gradient(135deg, rgba(45, 65, 89, 0.96) 0%, rgba(226, 212, 187, 0.3) 100%);
            border-color: rgba(226, 212, 187, 0.28);
            color: #fff8ed;
            box-shadow: 0 8px 18px rgba(15, 25, 49, 0.32);
        }

        .flatpickr-day.inRange,
        .flatpickr-day.prevMonthDay.inRange,
        .flatpickr-day.nextMonthDay.inRange,
        .flatpickr-day.today.inRange,
        .flatpickr-day.prevMonthDay.today.inRange,
        .flatpickr-day.nextMonthDay.today.inRange,
        .flatpickr-day:hover.inRange,
        .flatpickr-day.prevMonthDay:hover.inRange,
        .flatpickr-day.nextMonthDay:hover.inRange,
        .flatpickr-day:focus.inRange,
        .flatpickr-day.prevMonthDay:focus.inRange,
        .flatpickr-day.nextMonthDay:focus.inRange {
            background: rgba(226, 212, 187, 0.12);
            border-color: rgba(226, 212, 187, 0.12);
            box-shadow: none;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay,
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: rgba(226, 212, 187, 0.34);
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            fill: var(--accent);
            color: var(--accent);
            padding: 8px;
            border-radius: 999px;
            transition: background 0.18s ease, transform 0.18s ease;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(226, 212, 187, 0.12);
            transform: translateY(-1px);
        }

        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 15px;
            height: 15px;
        }

        .flatpickr-time {
            background: rgba(15, 25, 49, 0.22);
            border-top: 1px solid rgba(226, 212, 187, 0.08);
        }

        .flatpickr-time input:hover,
        .flatpickr-time .flatpickr-am-pm:hover,
        .flatpickr-time input:focus,
        .flatpickr-time .flatpickr-am-pm:focus {
            background: rgba(226, 212, 187, 0.08);
        }

        @keyframes pickerFadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Light theme: Flatpickr datepicker ── */
        html[data-theme="light"] .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid rgba(26, 82, 176, 0.2) !important;
            box-shadow: 0 8px 30px rgba(26, 82, 176, 0.15), 0 0 0 1px rgba(26, 82, 176, 0.08) !important;
            color: #091828 !important;
        }
        html[data-theme="light"] .flatpickr-calendar.arrowTop:before,
        html[data-theme="light"] .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #ffffff !important;
        }
        html[data-theme="light"] .flatpickr-calendar.arrowBottom:before,
        html[data-theme="light"] .flatpickr-calendar.arrowBottom:after {
            border-top-color: #ffffff !important;
        }
        html[data-theme="light"] .flatpickr-months .flatpickr-month,
        html[data-theme="light"] .flatpickr-current-month .flatpickr-monthDropdown-months,
        html[data-theme="light"] .flatpickr-current-month input.cur-year,
        html[data-theme="light"] .flatpickr-weekday,
        html[data-theme="light"] span.flatpickr-weekday,
        html[data-theme="light"] .flatpickr-day,
        html[data-theme="light"] .flatpickr-time input,
        html[data-theme="light"] .flatpickr-time .flatpickr-am-pm {
            color: #091828 !important;
        }
        html[data-theme="light"] .flatpickr-current-month .flatpickr-monthDropdown-months,
        html[data-theme="light"] .flatpickr-current-month input.cur-year {
            background: rgba(26, 82, 176, 0.07) !important;
            border: 1px solid rgba(26, 82, 176, 0.2) !important;
            color: #091828 !important;
        }
        html[data-theme="light"] .flatpickr-current-month .flatpickr-monthDropdown-months:hover,
        html[data-theme="light"] .flatpickr-current-month input.cur-year:hover,
        html[data-theme="light"] .flatpickr-current-month .flatpickr-monthDropdown-months:focus,
        html[data-theme="light"] .flatpickr-current-month input.cur-year:focus {
            background: rgba(26, 82, 176, 0.12) !important;
        }
        html[data-theme="light"] .flatpickr-monthDropdown-months option {
            background: #ffffff !important;
            color: #091828 !important;
        }
        html[data-theme="light"] .flatpickr-weekdays {
            background: rgba(26, 82, 176, 0.06) !important;
            border-top-color: rgba(26, 82, 176, 0.1) !important;
            border-bottom-color: rgba(26, 82, 176, 0.1) !important;
        }
        html[data-theme="light"] .flatpickr-day:hover,
        html[data-theme="light"] .flatpickr-day:focus {
            background: rgba(26, 82, 176, 0.1) !important;
            border-color: rgba(26, 82, 176, 0.15) !important;
        }
        html[data-theme="light"] .flatpickr-day.today {
            border-color: rgba(26, 82, 176, 0.5) !important;
            background: rgba(26, 82, 176, 0.07) !important;
        }
        html[data-theme="light"] .flatpickr-day.selected,
        html[data-theme="light"] .flatpickr-day.startRange,
        html[data-theme="light"] .flatpickr-day.endRange,
        html[data-theme="light"] .flatpickr-day.selected:hover,
        html[data-theme="light"] .flatpickr-day.startRange:hover,
        html[data-theme="light"] .flatpickr-day.endRange:hover {
            background: linear-gradient(135deg, #1a52b0, #2965cc) !important;
            border-color: #1a52b0 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(26, 82, 176, 0.3) !important;
        }
        html[data-theme="light"] .flatpickr-day.inRange,
        html[data-theme="light"] .flatpickr-day.prevMonthDay.inRange,
        html[data-theme="light"] .flatpickr-day.nextMonthDay.inRange {
            background: rgba(26, 82, 176, 0.1) !important;
            border-color: rgba(26, 82, 176, 0.1) !important;
        }
        html[data-theme="light"] .flatpickr-day.prevMonthDay,
        html[data-theme="light"] .flatpickr-day.nextMonthDay,
        html[data-theme="light"] .flatpickr-day.flatpickr-disabled,
        html[data-theme="light"] .flatpickr-day.flatpickr-disabled:hover {
            color: rgba(9, 24, 40, 0.3) !important;
        }
        html[data-theme="light"] .flatpickr-months .flatpickr-prev-month,
        html[data-theme="light"] .flatpickr-months .flatpickr-next-month {
            fill: #1a52b0 !important;
            color: #1a52b0 !important;
        }
        html[data-theme="light"] .flatpickr-months .flatpickr-prev-month:hover,
        html[data-theme="light"] .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(26, 82, 176, 0.1) !important;
        }
        html[data-theme="light"] .flatpickr-time {
            background: rgba(26, 82, 176, 0.04) !important;
            border-top-color: rgba(26, 82, 176, 0.1) !important;
        }
        html[data-theme="light"] .flatpickr-time input:hover,
        html[data-theme="light"] .flatpickr-time .flatpickr-am-pm:hover,
        html[data-theme="light"] .flatpickr-time input:focus,
        html[data-theme="light"] .flatpickr-time .flatpickr-am-pm:focus {
            background: rgba(26, 82, 176, 0.08) !important;
        }

        /* Flatpickr input field styling */
        html[data-theme="light"] input.flatpickr-input {
            background: #ffffff !important;
            color: #091828 !important;
            border-color: rgba(26, 82, 176, 0.25) !important;
        }
        html[data-theme="light"] input.flatpickr-input:focus {
            background: #ffffff !important;
            border-color: #1a52b0 !important;
            box-shadow: 0 0 10px rgba(26, 82, 176, 0.2) !important;
        }

        .btn,
        .btn-primary,
        .btn-secondary {
            background: linear-gradient(90deg, var(--navy-2) 0%, var(--maroon) 50%, var(--maroon-2) 100%);
            color: var(--button-text);
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
            border: 1px solid var(--surface-border);
            background: var(--surface-bg-soft);
            color: var(--text-main);
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
            border: 1px solid var(--surface-border);
            background: var(--surface-bg);
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
            background: var(--surface-bg);
            border: 1px solid var(--surface-border);
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(15, 25, 49, 0.35);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            animation: fadeIn 0.25s ease;
        }

        html[data-theme="light"] .table-card,
        html[data-theme="light"] .card-surface,
        html[data-theme="light"] .card-table-header,
        html[data-theme="light"] .card-table-row {
            box-shadow: 0 6px 20px rgba(30, 75, 170, 0.1);
        }

        html[data-theme="light"] .table-card:hover {
            background: rgba(235, 244, 255, 0.98) !important;
            box-shadow: 0 10px 28px rgba(30, 75, 170, 0.16) !important;
        }

        html[data-theme="light"] .card-table-row:hover {
            background: rgba(235, 244, 255, 0.98) !important;
        }

        html[data-theme="light"] .data-table {
            color: #091828;
        }

        html[data-theme="light"] .data-table th {
            border-bottom-color: rgba(40, 85, 160, 0.4);
            color: #091828;
        }

        html[data-theme="light"] .data-table th.sortable,
        html[data-theme="light"] .data-table th.sortable a {
            color: #091828;
        }

        html[data-theme="light"] .data-table th.sortable:hover a {
            color: #153a82;
        }

        html[data-theme="light"] .table-card .card-text,
        html[data-theme="light"] .table-card .card-text:first-child {
            color: #091828;
        }

        html[data-theme="light"] .table-card .card-text + .card-text {
            color: #2d4a68;
        }

        html[data-theme="light"] .table-search-input,
        html[data-theme="light"] input[data-datepicker="lv"] {
            background: #ffffff !important;
            color: #091828 !important;
            border: 1px solid rgba(26, 82, 176, 0.25) !important;
        }

        html[data-theme="light"] .table-search-input:focus {
            outline-color: rgba(22, 69, 160, 0.6);
            background: #ffffff !important;
            border-color: #1a52b0 !important;
        }

        html[data-theme="light"] input[data-datepicker="lv"]:focus {
            outline-color: rgba(22, 69, 160, 0.6);
            background: #ffffff !important;
            border-color: #1a52b0 !important;
        }

        html[data-theme="light"] .form-control:focus,
        html[data-theme="light"] .form-select:focus {
            outline-color: rgba(22, 69, 160, 0.5);
            background-color: #ffffff !important;
            color: #091828 !important;
            border-color: #1a52b0 !important;
            box-shadow: 0 0 0 0.2rem rgba(26, 82, 176, 0.15) !important;
        }

        html[data-theme="light"] .form-control,
        html[data-theme="light"] .form-select {
            background-color: #ffffff !important;
            color: #091828 !important;
            border-color: rgba(26, 82, 176, 0.25) !important;
        }

        html[data-theme="light"] .pagination li a,
        html[data-theme="light"] .pagination li span {
            color: #0d2247;
        }

        html[data-theme="light"] .pagination li a:hover {
            background: rgba(22, 69, 160, 0.1);
            border-color: rgba(22, 69, 160, 0.5);
        }

        html[data-theme="light"] .no-results-message {
            color: #2d4a68;
        }

        html[data-theme="light"] h2,
        html[data-theme="light"] h3,
        html[data-theme="light"] h4,
        html[data-theme="light"] h5,
        html[data-theme="light"] h6 {
            color: #091828;
        }

        html[data-theme="light"] hr {
            border-color: rgba(40, 85, 160, 0.2);
        }

        html[data-theme="light"] .card-table-header {
            background: rgba(215, 232, 255, 0.85) !important;
            color: #091828;
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
        /* Print-only elements hidden on screen */
        .print-only { display: none !important; }

        @media print {
            /* ── Page setup ── */
            @page {
                size: A4 portrait;
                margin: 18mm 20mm 22mm 20mm;
            }

            /* ── Reset colours & shadows ── */
            *, *::before, *::after {
                background: transparent !important;
                color: #000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
                filter: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            html, body {
                background: #fff !important;
                font-family: Arial, Helvetica, sans-serif !important;
                font-size: 10pt !important;
                line-height: 1.45 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
            }

            /* ── Hide all screen-only chrome ── */
            .page-shapes,
            header,
            footer,
            .auth-links,
            .bloom-button,
            .btn,
            button,
            form.table-controls,
            .inventory-toolbar,
            .pagination,
            .no-results-message,
            .actions,
            #flash-message,
            #flash-error,
            hr {
                display: none !important;
            }

            /* ── Show print-only elements ── */
            .print-only {
                display: block !important;
            }

            .confirm-overlay {
                display: none !important;
            }

            /* ── Layout wrappers ── */
            main {
                padding: 0 !important;
                margin: 0 !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .card-surface {
                border: none !important;
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            /* ── Print document header ── */
            .print-doc-header {
                width: 100%;
                margin-bottom: 8mm;
            }

            .print-doc-header .print-org-row {
                display: flex !important;
                justify-content: space-between;
                align-items: baseline;
                font-size: 9pt;
                color: #444 !important;
                margin-bottom: 3mm;
            }

            .print-doc-header .print-org-name {
                font-weight: bold;
                font-size: 11pt;
                letter-spacing: 0.03em;
                text-transform: uppercase;
            }

            .print-doc-header .print-date {
                font-size: 9pt;
                color: #444 !important;
            }

            .print-doc-header .print-divider {
                border: none !important;
                border-top: 2px solid #000 !important;
                display: block !important;
                margin: 2mm 0 4mm !important;
            }

            .print-doc-header .print-doc-title {
                font-size: 15pt !important;
                font-weight: bold !important;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                margin: 0 0 1mm !important;
            }

            .print-doc-header .print-doc-subtitle {
                font-size: 9pt;
                color: #555 !important;
            }

            .print-doc-header .print-title-divider {
                border: none !important;
                border-top: 1px solid #000 !important;
                display: block !important;
                margin: 3mm 0 0 !important;
            }

            /* ── Hide page h2 (title shown in print header instead) ── */
            h2 {
                display: none !important;
            }

            /* ── Data table ── */
            .data-table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 5mm 0 !important;
                font-size: 8pt !important;
                table-layout: fixed !important;
            }

            .table-wrap,
            .table-wrap--fit {
                overflow: visible !important;
            }

            .data-table thead {
                display: table-header-group;
            }

            .data-table th {
                background: #1a1a1a !important;
                color: #fff !important;
                padding: 5px 6px !important;
                text-align: left !important;
                font-weight: bold !important;
                border: 1px solid #000 !important;
                font-size: 8pt !important;
                white-space: normal !important;
                overflow-wrap: anywhere !important;
                word-break: break-word !important;
            }

            .data-table th.sortable a,
            .data-table th a {
                color: #fff !important;
                text-decoration: none !important;
            }

            .data-table th.sortable::after {
                content: '' !important;
                display: none !important;
            }

            .data-table td {
                padding: 5px 6px !important;
                border: 1px solid #bbb !important;
                font-size: 8pt !important;
                vertical-align: top;
                overflow-wrap: anywhere !important;
                word-break: break-word !important;
            }

            .movement-table th:nth-child(1),
            .movement-table td:nth-child(1) {
                width: 10% !important;
            }

            .movement-table th:nth-child(2),
            .movement-table td:nth-child(2) {
                width: 10% !important;
            }

            .movement-table th:nth-child(3),
            .movement-table td:nth-child(3) {
                width: 18% !important;
            }

            .movement-table th:nth-child(4),
            .movement-table td:nth-child(4) {
                width: 11% !important;
            }

            .movement-table th:nth-child(5),
            .movement-table td:nth-child(5) {
                width: 11% !important;
            }

            .movement-table th:nth-child(6),
            .movement-table td:nth-child(6) {
                width: 11% !important;
            }

            .movement-table th:nth-child(7),
            .movement-table td:nth-child(7) {
                width: 12% !important;
            }

            .movement-table th:nth-child(8),
            .movement-table td:nth-child(8) {
                width: 17% !important;
            }

            .data-table tbody tr:nth-child(even) td {
                background: #f4f4f4 !important;
            }

            .print-group-row {
                display: table-row !important;
                page-break-after: avoid;
                page-break-inside: avoid;
            }

            .print-group-row td {
                background: #efefef !important;
                color: #000 !important;
                border: 2px solid #000 !important;
                font-weight: bold !important;
                text-transform: none;
                font-size: 9pt !important;
                letter-spacing: 0.02em;
                padding: 6px 8px !important;
            }

            .print-group-separator td {
                height: 7mm !important;
                background: #fff !important;
                border: none !important;
                padding: 0 !important;
            }

            .data-table tbody tr {
                page-break-inside: avoid;
            }

            /* Hide last column (Darbības / actions), but keep print group rows visible */
            .data-table thead th:last-child,
            .data-table tbody tr:not(.print-group-row):not(.print-group-separator) td:last-child {
                display: none !important;
            }

            /* ── Detail card ── */
            .card {
                border: none !important;
                background: #fff !important;
                margin: 0 !important;
                max-width: 100% !important;
                page-break-inside: avoid;
            }

            .card-body {
                padding: 0 !important;
            }

            .card-title {
                font-size: 12pt !important;
                font-weight: bold !important;
                margin: 0 0 4mm !important;
                padding-bottom: 2mm !important;
                border-bottom: 2px solid #000 !important;
            }

            /* Convert card-text rows into a clean label/value table */
            .card-text {
                display: grid !important;
                grid-template-columns: 45mm 1fr !important;
                border-bottom: 1px solid #ddd !important;
                padding: 2.5mm 0 !important;
                font-size: 10pt !important;
                margin: 0 !important;
                gap: 0 4mm;
            }

            .card-text strong {
                font-weight: bold !important;
                color: #222 !important;
            }

            /* ── Print footer ── */
            .print-doc-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                font-size: 8pt;
                color: #555 !important;
                border-top: 1px solid #bbb !important;
                padding-top: 2mm;
                display: flex !important;
                justify-content: space-between;
            }

            .print-doc-footer::after {
                content: attr(data-page);
            }

            /* ── Misc ── */
            img {
                max-width: 100% !important;
                page-break-inside: avoid;
            }

            a {
                text-decoration: none !important;
                color: #000 !important;
            }
        }

        /* ── Light theme: override hardcoded inline dark-palette colours ── */

        /* Text colour on any element with hardcoded #E2D4BB beige */
        html[data-theme="light"] [style*="#E2D4BB"] {
            color: var(--text-main) !important;
        }

        /* Filter selects / inputs with beige-tinted dark background */
        html[data-theme="light"] [style*="rgba(226, 212, 187, 0.06)"],
        html[data-theme="light"] [style*="rgba(226,212,187,0.06)"] {
            background: #ffffff !important;
            color: var(--text-main) !important;
            border-color: rgba(26, 82, 176, 0.3) !important;
        }

        /* Flash messages and wrapper divs with #0F1931 dark background */
        html[data-theme="light"] [style*="#0F1931"] {
            background: var(--surface-bg) !important;
            color: var(--text-main) !important;
        }
        html[data-theme="light"] #flash-message {
            border-left-color: var(--navy-2) !important;
        }

        /* Admin notice panel with beige translucent background */
        html[data-theme="light"] [style*="rgba(226, 212, 187, 0.08)"] {
            background: rgba(26, 82, 176, 0.06) !important;
            border-color: rgba(26, 82, 176, 0.2) !important;
        }

        /* Count badge / pill with beige background */
        html[data-theme="light"] [style*="background:#E2D4BB"] {
            background: var(--navy-2) !important;
            color: #ffffff !important;
        }

        /* Details-page cards with dark semi-transparent background */
        html[data-theme="light"] [style*="rgba(45, 65, 89, 0.65)"] {
            background: var(--surface-bg) !important;
            border-color: rgba(26, 82, 176, 0.2) !important;
        }

        /* Pending-item rows with deep-navy background */
        html[data-theme="light"] [style*="rgba(15, 25, 49, 0.35)"] {
            background: rgba(26, 82, 176, 0.05) !important;
            border-color: rgba(26, 82, 176, 0.15) !important;
        }

        /* home.blade.php info banner and sidemenu */
        html[data-theme="light"] .navy-maroon {
            background: rgba(26, 82, 176, 0.08) !important;
            color: var(--text-main) !important;
        }
        html[data-theme="light"] .sidemenu {
            background: rgba(26, 82, 176, 0.08) !important;
            color: var(--text-main) !important;
        }
        html[data-theme="light"] .sidemenu a {
            color: var(--navy-2) !important;
        }
        html[data-theme="light"] .sidemenu a:hover {
            color: var(--maroon-2) !important;
        }

        html[data-theme="light"] header,
        html[data-theme="light"] .small header {
            background: var(--header-bg) !important;
            box-shadow: var(--header-shadow) !important;
            border-bottom-color: rgba(12, 35, 95, 0.22) !important;
            color: #ffffff !important;
        }

        html[data-theme="light"] header h1,
        html[data-theme="light"] header nav a,
        html[data-theme="light"] header .auth-user,
        html[data-theme="light"] header .auth-links a {
            color: #ffffff !important;
            text-shadow: none !important;
        }

        html[data-theme="light"] header nav a:hover {
            color: #cde4ff !important;
            text-shadow: 0 0 12px rgba(180, 215, 255, 0.5) !important;
        }

        html[data-theme="light"] header .theme-toggle-btn {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.36) !important;
            background: rgba(255, 255, 255, 0.12) !important;
        }

        html[data-theme="light"] header .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.22) !important;
        }

        html[data-theme="light"] .nav-badge {
            background: #cde4ff;
            color: #0d2a6e;
        }

        html[data-theme="light"] footer {
            background: var(--footer-bg) !important;
            box-shadow: var(--footer-shadow) !important;
            border-top: 1px solid rgba(12, 35, 95, 0.2);
            color: #cde4ff !important;
        }

        html[data-theme="light"] footer a {
            color: #cde4ff !important;
        }

        html[data-theme="light"] footer a:hover {
            color: #ffffff !important;
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

            {{-- Print-only document header (hidden on screen) --}}
            <div class="print-only print-doc-header" id="print-doc-header">
                <div class="print-org-row">
                    <span class="print-org-name">Rīgas Centrālā bibliotēka</span>
                    <span class="print-date" id="print-date"></span>
                </div>
                <hr class="print-divider">
                <p class="print-doc-title" id="print-doc-title"></p>
                <p class="print-doc-subtitle">Inventāra uzskaites sistēma</p>
                <hr class="print-title-divider">
            </div>

            @yield('content')

            {{-- Print-only document footer (hidden on screen) --}}
            <div class="print-only print-doc-footer" id="print-doc-footer">
                <span>RCB Inventāra uzskaite &mdash; <span id="print-footer-date"></span></span>
                <span></span>
            </div>

        </section>
    </main>

  

    <footer class="container" style="margin-top:1.25rem;">
        @include('inc.footer')
    </footer>

    <div class="confirm-overlay" id="confirm-overlay" aria-hidden="true">
        <div class="confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message">
            <h3 class="confirm-title" id="confirm-title">Apstiprināt darbību</h3>
            <p class="confirm-message" id="confirm-message">Vai tiešām vēlaties turpināt?</p>
            <div class="confirm-actions">
                <button type="button" class="bloom-button sm" id="confirm-cancel">Atcelt</button>
                <button type="button" class="bloom-button sm" id="confirm-accept">Apstiprināt</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        (function(){
            const THEME_KEY = 'rcb-theme';

            const getCurrentTheme = () => {
                const active = document.documentElement.getAttribute('data-theme');
                return active === 'light' ? 'light' : 'dark';
            };

            const updateThemeMeta = (theme) => {
                const meta = document.querySelector('meta[name="theme-color"]');
                if (!meta) return;
                meta.setAttribute('content', theme === 'light' ? '#e8eef7' : '#0F1931');
            };

            const updateThemeButton = () => {
                const toggleButton = document.querySelector('[data-theme-toggle]');
                if (!toggleButton) return;

                const current = getCurrentTheme();
                const iconEl = toggleButton.querySelector('[data-theme-icon]');
                const labelEl = toggleButton.querySelector('[data-theme-label]');

                if (iconEl) {
                    iconEl.className = current === 'light' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                }

                if (labelEl) {
                    labelEl.textContent = current === 'light' ? 'Gaiša tēma' : 'Tumša tēma';
                }

                toggleButton.setAttribute('aria-pressed', current === 'dark' ? 'true' : 'false');
                toggleButton.setAttribute('title', current === 'light' ? 'Pārslēgt uz tumšo tēmu' : 'Pārslēgt uz gaišo tēmu');
            };

            const applyTheme = (theme, persist = true) => {
                const resolved = theme === 'light' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', resolved);
                updateThemeMeta(resolved);
                updateThemeButton();

                if (persist) {
                    localStorage.setItem(THEME_KEY, resolved);
                }
            };

            const initThemeToggle = () => {
                const toggleButton = document.querySelector('[data-theme-toggle]');
                updateThemeMeta(getCurrentTheme());
                updateThemeButton();

                if (!toggleButton || toggleButton.dataset.themeBound === '1') return;

                toggleButton.addEventListener('click', () => {
                    const next = getCurrentTheme() === 'dark' ? 'light' : 'dark';
                    applyTheme(next);
                });

                toggleButton.dataset.themeBound = '1';
            };

            const initDatePickers = () => {
                // Inicializē visus datuma laukus ar latviešu lokalizāciju.
                if (!window.flatpickr) return;

                const lvLocale = (window.flatpickr.l10ns && window.flatpickr.l10ns.lv)
                    ? window.flatpickr.l10ns.lv
                    : 'lv';

                document.querySelectorAll('input[type="date"], input[data-datepicker="lv"]').forEach((input) => {
                    // Neradām otro flatpickr instanci, ja lauks jau ir inicializēts.
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
                // Klienta puses teksta filtrēšana tikai redzamajai tabulai.
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
                // Klienta puses kārtošana pēc izvēlētās kolonnas.
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
                // Pieslēdz meklēšanu un klikšķa kārtošanu vienas tabulas vadīklām.
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

            const initDateRangeFilters = () => {
                // Nodrošina, ka datumu intervālos "līdz" nekad nav agrāks par "no".
                const normalizeDateValue = (value) => {
                    const trimmed = (value || '').trim();
                    return /^\d{4}-\d{2}-\d{2}$/.test(trimmed) ? trimmed : '';
                };

                const syncPickerMinDate = (input, minDate) => {
                    if (!input?._flatpickr) return;
                    input._flatpickr.set('minDate', minDate || null);
                };

                const findToInput = (form, fromName) => {
                    if (fromName.endsWith('_no')) {
                        const base = fromName.slice(0, -3);
                        return form.querySelector(`input[name="${base}_lidz"]`);
                    }

                    if (fromName.endsWith('_from')) {
                        const base = fromName.slice(0, -5);
                        return form.querySelector(`input[name="${base}_to"]`);
                    }

                    return null;
                };

                const bindRange = (form, fromInput, toInput) => {
                    // Pārbaude notiek gan ievades brīdī, gan pirms formas iesniegšanas.
                    const validate = () => {
                        const fromValue = normalizeDateValue(fromInput.value);
                        const toValue = normalizeDateValue(toInput.value);

                        toInput.min = fromValue || '';
                        syncPickerMinDate(toInput, fromValue);

                        if (fromValue && toValue && toValue < fromValue) {
                            toInput.value = fromValue;
                            if (toInput._flatpickr) {
                                toInput._flatpickr.setDate(fromValue, false);
                            }
                            toInput.setCustomValidity('Datums "līdz" nevar būt agrāks par datumu "no".');
                            return false;
                        }

                        toInput.setCustomValidity('');
                        return true;
                    };

                    fromInput.addEventListener('change', validate);
                    fromInput.addEventListener('input', validate);
                    toInput.addEventListener('change', validate);
                    toInput.addEventListener('input', validate);

                    form.addEventListener('submit', (event) => {
                        if (!validate()) {
                            event.preventDefault();
                            toInput.reportValidity();
                        }
                    });

                    validate();
                };

                document.querySelectorAll('form').forEach((form) => {
                    const fromInputs = form.querySelectorAll('input[name$="_no"], input[name$="_from"]');
                    fromInputs.forEach((fromInput) => {
                        const toInput = findToInput(form, fromInput.name || '');
                        if (!toInput) return;
                        bindRange(form, fromInput, toInput);
                    });
                });
            };

            const nativePrint = window.print.bind(window);
            const printGroupState = new WeakMap();
            const printLimitState = new WeakMap();
            // Karogs, kas pēc drukas dialoga aizvēršanas atgriež URL uz paginēto skatu.
            let shouldResetPrintAllAfterDialog = false;

            const initAppConfirm = () => {
                // Universāls projekta apstiprinājuma dialogs (aizvieto browser confirm()).
                const overlay = document.getElementById('confirm-overlay');
                const title = document.getElementById('confirm-title');
                const message = document.getElementById('confirm-message');
                const cancelBtn = document.getElementById('confirm-cancel');
                const acceptBtn = document.getElementById('confirm-accept');

                if (!overlay || !title || !message || !cancelBtn || !acceptBtn) {
                    window.appConfirm = (text) => Promise.resolve(window.confirm(text || 'Vai tiešām vēlaties turpināt?'));
                    return;
                }

                let resolver = null;

                const close = (accepted) => {
                    // Aizveram dialogu un atgriežam lietotāja izvēli Promise izsaucējam.
                    overlay.classList.remove('is-visible');
                    overlay.setAttribute('aria-hidden', 'true');
                    if (resolver) {
                        resolver(accepted);
                        resolver = null;
                    }
                };

                acceptBtn.addEventListener('click', () => close(true));
                cancelBtn.addEventListener('click', () => close(false));
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) {
                        close(false);
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (!overlay.classList.contains('is-visible')) return;
                    if (event.key === 'Escape') {
                        event.preventDefault();
                        close(false);
                    }
                });

                window.appConfirm = (text, options = {}) => {
                    // Dinamiski ielādējam virsrakstu/pogas, lai dialogu var lietot dažādiem scenārijiem.
                    title.textContent = options.title || 'Apstiprināt darbību';
                    message.textContent = text || 'Vai tiešām vēlaties turpināt?';
                    acceptBtn.textContent = options.acceptText || 'Apstiprināt';
                    cancelBtn.textContent = options.cancelText || 'Atcelt';

                    overlay.classList.add('is-visible');
                    overlay.setAttribute('aria-hidden', 'false');
                    setTimeout(() => acceptBtn.focus(), 0);

                    return new Promise((resolve) => {
                        resolver = resolve;
                    });
                };
            };

            const getPrintOptions = () => {
                // Nolasa lietotāja izvēles no drukas izvēlnes.
                const container = document.querySelector('.print-options');
                if (!container) {
                    return {
                        limit: null,
                        group: true,
                    };
                }

                const limitInput = container.querySelector('.print-row-limit');
                const groupInput = container.querySelector('.print-group-toggle');
                const parsedLimit = parseInt((limitInput?.value || '').trim(), 10);

                return {
                    limit: Number.isFinite(parsedLimit) && parsedLimit > 0 ? parsedLimit : null,
                    group: groupInput ? !!groupInput.checked : true,
                };
            };

            const applyPrintRowLimit = () => {
                // Drukas režīmā varam paslēpt daļu redzamo rindu, ja norādīts limits.
                const options = getPrintOptions();
                if (!options.limit) {
                    return;
                }

                document.querySelectorAll('table.data-table').forEach((table) => {
                    if (printLimitState.has(table)) return;

                    const tbody = table.tBodies[0];
                    if (!tbody) return;

                    const allRows = Array.from(tbody.querySelectorAll('tr')).filter((row) => !row.classList.contains('print-group-row') && !row.classList.contains('print-group-separator'));
                    const visibleRows = allRows.filter((row) => window.getComputedStyle(row).display !== 'none');

                    if (visibleRows.length <= options.limit) {
                        return;
                    }

                    const rowsToHide = visibleRows.slice(options.limit);
                    rowsToHide.forEach((row) => {
                        row.dataset.printHiddenByLimit = '1';
                        row.style.display = 'none';
                    });

                    printLimitState.set(table, rowsToHide);
                });
            };

            const restorePrintRowLimit = () => {
                // Pēc drukas atjaunojam visas iepriekš paslēptās rindas.
                document.querySelectorAll('table.data-table').forEach((table) => {
                    const rows = printLimitState.get(table);
                    if (!rows || rows.length === 0) return;

                    rows.forEach((row) => {
                        if (row.dataset.printHiddenByLimit === '1') {
                            row.style.display = '';
                            delete row.dataset.printHiddenByLimit;
                        }
                    });

                    printLimitState.delete(table);
                });
            };

            const getPrintableText = (row, columnIndex) => {
                // Droši iegūstam drukājamo teksta vērtību no kolonnas,
                // neatkarīgi no tā, vai šūnā ir plain text vai iekšējie elementi.
                const cell = row.cells[columnIndex];
                if (!cell) return '-';

                const text = Array.from(cell.childNodes)
                    .map((node) => node.textContent || '')
                    .join(' ')
                    .replace(/\s+/g, ' ')
                    .trim();

                return text || '-';
            };

            const resolveGroupValue = (value, mode) => {
                // Grupēšanas režīms "initial" izmanto tikai pirmo burtu,
                // citos režīmos grupējam pēc pilnās vērtības.
                if (mode === 'initial') {
                    const firstLetter = (value || '').trim().charAt(0).toUpperCase();
                    return firstLetter || '#';
                }

                return value || '-';
            };

            const preparePrintGroups = () => {
                // Grupēšanu pielietojam tikai tad, ja lietotājs to ir atzīmējis drukas izvēlnē.
                const options = getPrintOptions();
                if (!options.group) {
                    return;
                }

                document.querySelectorAll('table[data-print-group-column]').forEach((table) => {
                    // Ja tabula jau sagatavota drukai, otro reizi to nepārkārtojam.
                    if (printGroupState.has(table)) return;

                    const tbody = table.tBodies[0];
                    if (!tbody) return;

                    const allRows = Array.from(tbody.querySelectorAll('tr')).filter((row) => !row.classList.contains('print-group-row'));
                    if (allRows.length === 0) return;

                    const columnIndex = Number(table.dataset.printGroupColumn);
                    if (Number.isNaN(columnIndex)) return;

                    const visibleRows = allRows.filter((row) => window.getComputedStyle(row).display !== 'none');
                    if (visibleRows.length === 0) return;

                    printGroupState.set(table, { rows: allRows });

                    const hiddenRows = allRows.filter((row) => window.getComputedStyle(row).display === 'none');
                    const label = table.dataset.printGroupLabel || 'Grupa';
                    const mode = table.dataset.printGroupMode || 'text';
                    const columnCount = table.querySelectorAll('thead th').length || visibleRows[0].cells.length || 1;

                    const sortedVisibleRows = [...visibleRows].sort((rowA, rowB) => {
                        // Pirms grupēšanas sakārtojam rindas pēc grupas vērtības,
                        // lai katra grupa izdrukātos vienā blokā.
                        const valueA = resolveGroupValue(getPrintableText(rowA, columnIndex), mode);
                        const valueB = resolveGroupValue(getPrintableText(rowB, columnIndex), mode);
                        return valueA.localeCompare(valueB, 'lv', { numeric: true, sensitivity: 'base' });
                    });

                    const groupCounts = sortedVisibleRows.reduce((accumulator, row) => {
                        // Aprēķinām, cik ierakstu ir katrā grupā (virsraksta skaitam).
                        const groupValue = resolveGroupValue(getPrintableText(row, columnIndex), mode);
                        accumulator[groupValue] = (accumulator[groupValue] || 0) + 1;
                        return accumulator;
                    }, {});

                    let currentGroup = null;
                    sortedVisibleRows.forEach((row) => {
                        const groupValue = resolveGroupValue(getPrintableText(row, columnIndex), mode);
                        if (groupValue !== currentGroup) {
                            if (currentGroup !== null) {
                                // Starp grupām pievienojam atstarpi labākai lasāmībai drukā.
                                const separatorRow = document.createElement('tr');
                                separatorRow.className = 'print-group-separator';

                                const separatorCell = document.createElement('td');
                                separatorCell.colSpan = columnCount;
                                separatorCell.textContent = '';

                                separatorRow.appendChild(separatorCell);
                                tbody.appendChild(separatorRow);
                            }

                            const groupRow = document.createElement('tr');
                            groupRow.className = 'print-group-row';

                            const groupCell = document.createElement('td');
                            groupCell.colSpan = columnCount;
                            // Grupas virsraksts ar ierakstu skaitu ātrākai orientācijai.
                            groupCell.textContent = `${label}: ${groupValue} (${groupCounts[groupValue] || 0} ieraksti)`;

                            groupRow.appendChild(groupCell);
                            tbody.appendChild(groupRow);
                            currentGroup = groupValue;
                        }

                        tbody.appendChild(row);
                    });

                    hiddenRows.forEach((row) => tbody.appendChild(row));
                });
            };

            const restorePrintGroups = () => {
                // Pēc drukas atgriežam tabulas sākotnējo rindu secību un noņemam grupēšanas rindas.
                document.querySelectorAll('table[data-print-group-column]').forEach((table) => {
                    const state = printGroupState.get(table);
                    if (!state) return;

                    const tbody = table.tBodies[0];
                    if (!tbody) return;

                    tbody.querySelectorAll('.print-group-row, .print-group-separator').forEach((row) => row.remove());
                    state.rows.forEach((row) => tbody.appendChild(row));
                    printGroupState.delete(table);
                });
            };

            const initPrintControls = () => {
                // Pie visām drukas pogām piesaistām vienotu projekta drukas izvēlni.
                const triggers = Array.from(document.querySelectorAll('a[onclick*="window.print"], button[onclick*="window.print"], .js-print-trigger'));

                triggers.forEach((trigger) => {
                    if (trigger.dataset.printBound === '1') return;

                    trigger.removeAttribute('onclick');
                    trigger.classList.add('js-print-trigger');

                    trigger.dataset.printBound = '1';

                    const container = trigger.parentElement;
                    if (!container || container.querySelector('.print-options')) {
                        // Ja izvēlne jau eksistē, poga tikai atver/aizver esošo paneli.
                        trigger.addEventListener('click', (event) => {
                            event.preventDefault();
                            const existingOptions = container ? container.querySelector('.print-options') : null;
                            if (existingOptions) {
                                existingOptions.classList.toggle('is-visible');
                            }
                        });
                        return;
                    }

                    const options = document.createElement('span');
                    options.className = 'print-options';
                    // Drukas izvēlne tiek ģenerēta dinamiski, lai nebūtu jādublē HTML katrā lapā.
                    options.innerHTML = `
                        <span class="print-options-title">Drukāšanas izvēlne:</span>
                        <label>Rindu skaits
                            <input type="number" class="print-row-limit" min="1" step="1" placeholder="Visas rindas" title="Norādiet, cik rindas drukāt">
                        </label>
                        <label>
                            <input type="checkbox" class="print-group-toggle" checked>
                            Grupēt datus
                        </label>
                        <label>
                            <input type="checkbox" class="print-all-pages-toggle">
                            Iekļaut visas lapas
                        </label>
                        <span class="print-actions">
                            <button type="button" class="bloom-button sm print-confirm">Drukāt</button>
                            <button type="button" class="bloom-button sm print-pdf">Lejupielādēt PDF</button>
                            <button type="button" class="bloom-button sm print-cancel">Aizvērt</button>
                        </span>
                    `;

                    container.appendChild(options);

                    trigger.addEventListener('click', (event) => {
                        event.preventDefault();
                        // Poga "Printēt" vispirms atver izvēlni, nevis uzreiz drukā.
                        options.classList.toggle('is-visible');
                    });

                    const printConfirmBtn = options.querySelector('.print-confirm');
                    if (printConfirmBtn) {
                        printConfirmBtn.addEventListener('click', (event) => {
                            event.preventDefault();

                            const printAllPagesInput = options.querySelector('.print-all-pages-toggle');
                            if (printAllPagesInput && printAllPagesInput.checked && !new URL(window.location.href).searchParams.has('print_all')) {
                                // Pilnās drukas scenārijs: atveram jaunu tabu ar visiem lapojuma datiem.
                                const printUrl = new URL(window.location.href);
                                printUrl.searchParams.set('print_all', '1');
                                printUrl.searchParams.set('print_autorun', '1');
                                window.open(printUrl.toString(), '_blank');
                                return;
                            }

                            // Standarta scenārijā drukājam pašreizējo lapu/tabulu.
                            window.print();
                        });
                    }

                    const printPdfBtn = options.querySelector('.print-pdf');
                    if (printPdfBtn) {
                        printPdfBtn.addEventListener('click', async (event) => {
                            event.preventDefault();

                            // PDF eksports notiek pilnībā projekta pusē bez pārlūka print dialoga.
                            if (typeof window.html2pdf !== 'function') {
                                window.appConfirm('PDF eksporta bibliotēku neizdevās ielādēt. Vai turpināt ar parasto drukāšanu?', {
                                    title: 'PDF eksports nav pieejams',
                                    acceptText: 'Turpināt drukāt',
                                    cancelText: 'Atcelt'
                                }).then((accepted) => {
                                    if (accepted) {
                                        window.print();
                                    }
                                });
                                return;
                            }

                            preparePrintView();

                            try {
                                const source = document.querySelector('main .card-surface');
                                if (!source) {
                                    restorePrintView();
                                    return;
                                }

                                // Strādājam ar klonu, lai neizjauktu aktīvās lapas DOM stāvokli.
                                const exportRoot = document.createElement('div');
                                exportRoot.style.background = '#ffffff';
                                exportRoot.style.color = '#000000';
                                exportRoot.style.padding = '12px';
                                exportRoot.style.width = '100%';

                                const cloned = source.cloneNode(true);
                                cloned.querySelectorAll('.print-options, .confirm-overlay, .auth-links, .pagination, #flash-message, #flash-error').forEach((node) => node.remove());
                                exportRoot.appendChild(cloned);

                                const now = new Date();
                                const datePart = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

                                await window.html2pdf()
                                    .set({
                                        margin: [10, 10, 10, 10],
                                        filename: `RCB-atskaite-${datePart}.pdf`,
                                        image: { type: 'jpeg', quality: 0.96 },
                                        html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff' },
                                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                                        pagebreak: { mode: ['css', 'legacy'] },
                                    })
                                    .from(exportRoot)
                                    .save();
                            } finally {
                                restorePrintView();
                                options.classList.remove('is-visible');
                            }
                        });
                    }

                    const printCancelBtn = options.querySelector('.print-cancel');
                    if (printCancelBtn) {
                        printCancelBtn.addEventListener('click', (event) => {
                            event.preventDefault();
                            options.classList.remove('is-visible');
                        });
                    }
                });
            };

            const preparePrintView = () => {
                // Vienā solī sagatavojam drukai gan rindu limitu, gan grupēšanu.
                applyPrintRowLimit();
                preparePrintGroups();
            };

            const restorePrintView = () => {
                // Atgriežam UI stāvokli pēc drukas (grupas, limiti, atvērti paneļi).
                restorePrintGroups();
                restorePrintRowLimit();
                document.querySelectorAll('.print-options.is-visible').forEach((panel) => {
                    panel.classList.remove('is-visible');
                });
            };

            const cleanupPrintAllMode = () => {
                // Pēc drukas dialoga aizvēršanas atgriežamies no print_all režīma uz parasto pagināciju.
                if (!shouldResetPrintAllAfterDialog) {
                    return;
                }

                const currentUrl = new URL(window.location.href);
                if (currentUrl.searchParams.get('print_all') !== '1') {
                    shouldResetPrintAllAfterDialog = false;
                    return;
                }

                currentUrl.searchParams.delete('print_all');
                currentUrl.searchParams.delete('print_autorun');
                shouldResetPrintAllAfterDialog = false;
                window.location.replace(currentUrl.toString());
            };

            window.print = () => {
                // Pārrakstām window.print, lai pirms sistēmas dialoga vienmēr
                // izpildītos mūsu sagatavošanas loģika (grupēšana/limiti).
                preparePrintView();
                nativePrint();
            };

            window.addEventListener('beforeprint', preparePrintView);
            window.addEventListener('afterprint', () => {
                restorePrintView();
                cleanupPrintAllMode();
            });

            window.addEventListener('focus', () => {
                // Dažos pārlūkos afterprint var nenostrādāt uzticami,
                // tādēļ papildus pārbaudām stāvokli, kad logs atgūst fokusu.
                if (!shouldResetPrintAllAfterDialog) {
                    return;
                }

                setTimeout(() => {
                    cleanupPrintAllMode();
                }, 180);
            });

            const initAutoPrintFromQuery = () => {
                // Ja URL satur print_autorun=1, automātiski startējam drukas dialogu.
                const url = new URL(window.location.href);
                if (url.searchParams.get('print_autorun') !== '1') {
                    return;
                }

                // Atceramies, vai pēc dialoga aizvēršanas jāatgriežas uz paginēto URL.
                shouldResetPrintAllAfterDialog = url.searchParams.get('print_all') === '1';
                url.searchParams.delete('print_autorun');
                window.history.replaceState({}, '', url.toString());

                setTimeout(() => {
                    window.print();
                }, 120);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    initAppConfirm();
                    initThemeToggle();
                    initDatePickers();
                    initAllTableControls();
                    initDateRangeFilters();
                    initPrintControls();
                    initAutoPrintFromQuery();
                });
            } else {
                initAppConfirm();
                initThemeToggle();
                initDatePickers();
                initAllTableControls();
                initDateRangeFilters();
                initPrintControls();
                initAutoPrintFromQuery();
            }
        })();
    </script>
</body>
</html>

