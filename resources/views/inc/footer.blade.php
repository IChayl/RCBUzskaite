<footer>
  <style>
    footer {
      background: var(--footer-bg, linear-gradient(90deg, #0F1931, #2D4159));
      color: var(--accent, #E2D4BB);
      text-align: center;
      padding: 20px 10px;
      font-size: 0.95rem;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      box-shadow: var(--footer-shadow, 0 -4px 10px rgba(15, 25, 49, 0.4));
      z-index: 100;
    }

    footer .container {
      max-width: 900px;
      margin: 0 auto;
    }

    footer a {
      color: var(--accent, #E2D4BB);
      text-decoration: none;
      transition: all 0.3s ease;
      margin: 0 5px;
      font-weight: 500;
    }

    footer a:hover {
      color: #2D4159;
      text-shadow: 0 0 10px rgba(226, 212, 187, 0.8), 0 0 20px #2D4159;
    }

    footer p {
      margin: 5px 0;
    }

    /* Bloom efekts aktīvā stāvoklī */
    @keyframes glow {
      0% { text-shadow: 0 0 5px #E2D4BB, 0 0 10px #2D4159, 0 0 20px #2D4159; }
      100% { text-shadow: 0 0 10px #E2D4BB, 0 0 20px #2D4159, 0 0 40px #2D4159; }
    }

    footer a:active {
      animation: glow 0.3s ease-in-out;
    }

  </style>

  <div class="container">
    <p>&copy; {{ date('Y') }} Rēzeknes Centrālās bibliotēkas inventāra uzskaite. Visas tiesības aizsargātas.</p>
    <a href="https://www.rezeknesbiblioteka.lv" target="_blank">RCB mājaslapa</a>

  </div>
</footer>
