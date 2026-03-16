<footer>
  <style>
    footer {
      background: linear-gradient(90deg, #0D203A, #243B53);
      color: #FAF8F2;
      text-align: center;
      padding: 20px 10px;
      font-size: 0.95rem;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      box-shadow: 0 -4px 10px rgba(13, 32, 58, 0.4);
      z-index: 100;
    }

    footer .container {
      max-width: 900px;
      margin: 0 auto;
    }

    footer a {
      color: #FAF8F2;
      text-decoration: none;
      transition: all 0.3s ease;
      margin: 0 5px;
      font-weight: 500;
    }

    footer a:hover {
      color: #243B53;
      text-shadow: 0 0 10px rgba(209, 195, 165, 0.8), 0 0 20px #243B53;
    }

    footer p {
      margin: 5px 0;
    }

    /* Bloom effect when active */
    @keyframes glow {
      0% { text-shadow: 0 0 5px #FAF8F2, 0 0 10px #243B53, 0 0 20px #243B53; }
      100% { text-shadow: 0 0 10px #FAF8F2, 0 0 20px #243B53, 0 0 40px #243B53; }
    }

    footer a:active {
      animation: glow 0.3s ease-in-out;
    }

    /* Make sure content above doesn't hide behind footer */
    body {
      margin: 0;
      padding-bottom: 70px; /* Adjust depending on footer height */
      background-color: #0D203A;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  </style>

  <div class="container">
    <p>&copy; {{ date('Y') }} Rēzeknes Centrālās bibliotēkas inventāra uzskaite. Visas tiesības aizsargātas.</p>
    <a href="https://www.rezeknesbiblioteka.lv" target="_blank">RCB mājaslapa</a>

  </div>
</footer>
