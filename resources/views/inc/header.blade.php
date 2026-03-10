<header>
  <style>
    /* General header style */
    header {
      position: relative;
      background: linear-gradient(90deg, #490700, #5400A8);
      color: #FFFFFF;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      transparency: true;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
      border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

      .small header {
      position: relative;
      background: linear-gradient(90deg, #490700, #5400A8);
      color: #FFFFFF;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      transparency: true;
      border-radius: 18px;
      
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
      border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    /* Decorative shape behind header */
    header::before {
            border-radius: 18px;
      border: 1px solid #ffd6d6;
      content: '';
      position: absolute;
      top: -80px;
      right: -100px;
      width: 250px;
      height: 250px;
      background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1), transparent 70%);
      transform: rotate(25deg);
      filter: blur(30px);
    }

    header::after {
      
      content: '';
      position: absolute;
      bottom: -100px;
      left: -120px;
      width: 280px;
      height: 280px;
      background: radial-gradient(circle at center, rgba(255, 255, 255, 0.08), transparent 70%);
      transform: rotate(-20deg);
      filter: blur(25px);
    }

    /* Brand name */
    .brand a {
      color: #150024;
      font-size: 1.6em;
      font-weight: bold;
      text-decoration: none;
      letter-spacing: 1px;
      position: relative;
      z-index: 1;
      transition: color 0.3s ease;
    }

    .brand a:hover {
      color: #150024;
      text-shadow: 0 0 10px #ff00d0, 0 0 20px maroon;
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
      color: #FFFFFF;
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
      background: linear-gradient(90deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
      transition: all 0.4s ease;
      border-radius: 12px;
      z-index: -1;
    }

    nav a:hover::before {
      left: 0;
    }

    nav a:hover {
      color: #ffd6d6;
      text-shadow: 0 0 8px #fff, 0 0 18px maroon;
    }

    /* Auth section (Login/Register) */
    .auth-links {
      display: flex;
      gap: 20px;
      z-index: 1;
    }

    .auth-links a {
      color: #FFFFFF;
      text-decoration: none;
      font-weight: 500;
      letter-spacing: 0.5px;
      padding: 8px 14px;
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(3px);
    }

    .auth-links a:hover {
      background: rgba(255, 255, 255, 0.15);
      color: #ffd6d6;
      border: 1px solid #ffd6d6;
      border-radius: 25px;
      box-shadow: 0 0 10px maroon, 0 0 20px rgba(255, 255, 255, 0.4);
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
    <a href="/">Sākums</a>
    <a href="/kategorija">Kategorijas tabula</a>
    <a href="/inventara_kustiba">Kustības</a>
    <a href="/kustibas_veidi">Kustības veidi</a>
    <a href="/inventars">Inventārs</a>
    <a href="/telpa">Telpas</a>
    <a href="/lietotajs">Lietotāji</a>
  </nav>

  <div class="auth-links">
    @if(Auth::check())
      <a href="/Logout">Izlogoties</a>
    @else
    <a href="/Login">Ielogoties</a>
    
    <a href="/register">Reģistrēties</a>
    
    @endif
  </div>
</header>
