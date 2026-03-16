<header>
  <style>
    /* General header style */
    header {
      position: relative;
      background: linear-gradient(90deg, #0D203A, #243B53);
      color: #FAF8F2;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      transparency: true;
      box-shadow: 0 4px 12px rgba(13, 32, 58, 0.45);
      border-bottom: 2px solid rgba(209, 195, 165, 0.1);
    }

      .small header {
      position: relative;
      background: linear-gradient(90deg, #0D203A, #243B53);
      color: #FAF8F2;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      transparency: true;
      border-radius: 18px;
      
      box-shadow: 0 4px 12px rgba(13, 32, 58, 0.45);
      border-bottom: 2px solid rgba(209, 195, 165, 0.1);
    }

    /* Decorative shape behind header */
    header::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -40px;
      width: 160px;
      height: 160px;
      background: radial-gradient(circle at center, rgba(209, 195, 165, 0.2), transparent 75%);
      filter: blur(18px);
    }

    header::after {
      content: '';
      position: absolute;
      bottom: -30px;
      left: -40px;
      width: 160px;
      height: 160px;
      background: radial-gradient(circle at center, rgba(209, 195, 165, 0.2), transparent 75%);
      filter: blur(18px);
    }

    /* Brand name */
    .brand a {
      color: #FAF8F2;
      font-size: 1.6em;
      font-weight: bold;
      text-decoration: none;
      letter-spacing: 1px;
      position: relative;
      z-index: 1;
      transition: color 0.3s ease;
    }

    .brand a:hover {
      color: #243B53;
      text-shadow: 0 0 10px #243B53, 0 0 20px #243B53;
    }

    /* Navigation */
    nav {
      display: flex;
      align-items: center;
      gap: 35px;
      z-index: 1;
    }

    nav a {
      position: relative;
      color: #FAF8F2;
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
      background: linear-gradient(90deg, rgba(209, 195, 165, 0.2), rgba(209, 195, 165, 0.05));
      transition: all 0.4s ease;
      border-radius: 12px;
      z-index: -1;
    }

    nav a:hover::before {
      left: 0;
    }

    nav a:hover {
      color: #243B53;
      text-shadow: 0 0 8px #FAF8F2, 0 0 18px #243B53;
    }

    /* Auth section (Login/Register) */
    .auth-links {
      display: flex;
      gap: 20px;
      z-index: 1;
    }

    .auth-links a {
      color: #FAF8F2;
      text-decoration: none;
      font-weight: 500;
      letter-spacing: 0.5px;
      padding: 8px 14px;
      border-radius: 20px;
      border: 1px solid rgba(209, 195, 165, 0.3);
      transition: all 0.3s ease;
      background: rgba(209, 195, 165, 0.05);
      backdrop-filter: blur(3px);
    }

    .auth-links a:hover {
      background: rgba(209, 195, 165, 0.15);
      color: #243B53;
      border: 1px solid #243B53;
      border-radius: 25px;
      box-shadow: 0 0 10px #243B53, 0 0 20px rgba(209, 195, 165, 0.4);
      transform: translateY(-2px);
    }

    /* Responsive (optional) */
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
      <a href="/kustibas_veidi">Kustības veidi</a>
      <a href="/kategorija">Kategorijas tabula</a>
      <a href="/telpa">Telpas</a>
      <a href="/lietotajs">Lietotāji</a>
      @else
         @endif
  </nav>

  <div class="auth-links">
    @if(Auth::check())
      <a href="/Logout">Izlogoties</a>
    @else
    <a href="/Login">Ielogoties</a>
    
    @endif
  </div>
</header>
