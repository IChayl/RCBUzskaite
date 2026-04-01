<header>
  <style>
    /* Galvenes pamatstils */
    header {
      position: relative;
      background: linear-gradient(90deg, #0F1931, #2D4159);
      color: #E2D4BB;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      transparency: true;
      box-shadow: 0 4px 12px rgba(15, 25, 49, 0.45);
      border-bottom: 2px solid rgba(226, 212, 187, 0.1);
    }

      .small header {
      position: relative;
      background: linear-gradient(90deg, #0F1931, #2D4159);
      color: #E2D4BB;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      transparency: true;
      border-radius: 18px;
      
      box-shadow: 0 4px 12px rgba(15, 25, 49, 0.45);
      border-bottom: 2px solid rgba(226, 212, 187, 0.1);
    }

    /* Dekoratīva forma aiz galvenes */
    header::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -40px;
      width: 160px;
      height: 160px;
      background: radial-gradient(circle at center, rgba(226, 212, 187, 0.2), transparent 75%);
      filter: blur(18px);
    }

    header::after {
      content: '';
      position: absolute;
      bottom: -30px;
      left: -40px;
      width: 160px;
      height: 160px;
      background: radial-gradient(circle at center, rgba(226, 212, 187, 0.2), transparent 75%);
      filter: blur(18px);
    }

    /* Zīmola nosaukums */
    .brand a {
      color: #E2D4BB;
      font-size: 1.6em;
      font-weight: bold;
      text-decoration: none;
      letter-spacing: 1px;
      position: relative;
      z-index: 1;
      transition: color 0.3s ease;
    }

    .brand a:hover {
      color: #2D4159;
      text-shadow: 0 0 10px #2D4159, 0 0 20px #2D4159;
    }

    /* Navigācija */
    nav {
      display: flex;
      align-items: center;
      gap: 35px;
      z-index: 1;
    }

    nav a {
      position: relative;
      color: #E2D4BB;
      text-decoration: none;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      padding: 8px 14px;
      border-radius: 12px;
      overflow: hidden;
    }

    nav a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, rgba(226, 212, 187, 0.35), rgba(226, 212, 187, 0.12));
      transition: all 0.4s ease;
      border-radius: 12px;
      z-index: -1;
    }

    nav a:hover::before {
      left: 0;
    }

    nav a:hover {
      color: #E2D4BB;
      text-shadow: 0 0 10px #E2D4BB, 0 0 22px #E2D4BB;
    }

    .nav-link-with-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .nav-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 22px;
      height: 22px;
      padding: 0 7px;
      border-radius: 999px;
      background: #E2D4BB;
      color: #0F1931;
      font-size: 0.75rem;
      font-weight: 700;
      line-height: 1;
      box-shadow: 0 0 0 1px rgba(15, 25, 49, 0.15);
    }

    /* Autentifikācijas sadaļa (Ielogoties/Reģistrēties) */
    .auth-links {
      display: flex;
      gap: 20px;
      z-index: 1;
    }

    .auth-links a {
      color: #E2D4BB;
      text-decoration: none;
      font-weight: 500;
      letter-spacing: 0.5px;
      padding: 8px 14px;
      border-radius: 20px;
      border: 1px solid rgba(226, 212, 187, 0.3);
      transition: all 0.3s ease;
      background: rgba(226, 212, 187, 0.05);
      backdrop-filter: blur(3px);
    }

    .auth-links a:hover {
      background: rgba(226, 212, 187, 0.15);
      color: #E2D4BB;
      border: 1px solid #2D4159;
      border-radius: 25px;
      box-shadow: 0 0 10px #2D4159, 0 0 20px rgba(226, 212, 187, 0.4);
      transform: translateY(-2px);
    }

    /* Responsīvais izkārtojums (pēc izvēles) */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
      }
      nav {
        gap: 15px;
      }
    }
  </style>
  <nav>
   
       @if(Auth::check())
      <a href="/inventars">Inventārs</a>
      <a href="/inventara_kustiba">Kustības</a>
        <a href="/norakstishana" class="nav-link-with-badge">
          <span>Norakstīšanas</span>
          @if(Auth::user()->admina_tiesibas && ($pendingNorakstishanaCount ?? 0) > 0)
            <span class="nav-badge" title="Neakceptēti norakstīšanas pieteikumi">{{ $pendingNorakstishanaCount }}</span>
          @endif
        </a>
      <a href="/kategorija">Kategorijas tabula</a>
      <a href="/telpa">Telpas</a>
      <a href="/lietotajs">Lietotāji</a>
      @else
         @endif
  </nav>

  <div class="auth-links">
    @if(Auth::check())
      <a href="/Logout">Izlogoties</a>
      {{ Auth::user()->lietotajvards }} {{ Auth::user()->admina_tiesibas ? '(Admin)' : '' }}
    @else
    <a href="/Login">Ielogoties</a>
    
    @endif
  </div>
</header>
