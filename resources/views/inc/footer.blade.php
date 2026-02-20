<footer>
  <style>
    footer {
      background: linear-gradient(90deg, #490700, #5400A8);
      color: #FFFFFF;
      text-align: center;
      padding: 20px 10px;
      font-size: 0.95rem;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.2);
      z-index: 100;
    }

    footer .container {
      max-width: 900px;
      margin: 0 auto;
    }

    footer a {
      color: #fff;
      text-decoration: none;
      transition: all 0.3s ease;
      margin: 0 5px;
      font-weight: 500;
    }

    footer a:hover {
      color: #ffd6d6;
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px maroon;
    }

    footer p {
      margin: 5px 0;
    }

    /* Bloom effect when active */
    @keyframes glow {
      0% { text-shadow: 0 0 5px #fff, 0 0 10px #ffcccc, 0 0 20px maroon; }
      100% { text-shadow: 0 0 10px #fff, 0 0 20px #ffb3b3, 0 0 40px maroon; }
    }

    footer a:active {
      animation: glow 0.3s ease-in-out;
    }

    /* Make sure content above doesn't hide behind footer */
    body {
      margin: 0;
      padding-bottom: 70px; /* Adjust depending on footer height */
      background-color: #fdfdfd;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  </style>

  <div class="container">
    <p>&copy; {{ date('Y') }} Rēzeknes Centrālās bibliotēkas inventāra uzskaite. Visas tiesības aizsargātas.</p>
    <a href="https://www.rezeknesbiblioteka.lv" target="_blank">RCB mājaslapa</a>

  </div>
</footer>
