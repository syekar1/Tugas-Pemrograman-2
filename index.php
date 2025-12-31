<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Syekar Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">

  <style>
    body {
      background: #0d1117;
      font-family: 'Segoe UI', sans-serif;
      color: #e6e6e6;
      margin: 0;
    }

    .hero-section {
      background: linear-gradient(135deg, #111827, #1f2937);
      padding: 100px 20px;
      text-align: center;
      border-bottom: 3px solid #1f2937;
    }

    .hero-section h2 {
      font-size: 2.6rem;
      font-weight: 800;
      color: #58a6ff;
    }

    .hero-section span {
      color: #f05454;
    }

    .hero-logo {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      margin-top: 20px;
      object-fit: cover;
      box-shadow: 0 0 20px rgba(88, 166, 255, 0.5);
      border: 3px solid #58a6ff;
    }

    .hero-quote {
      margin-top: 30px;
      font-style: italic;
      font-size: 1.2rem;
      color: #9ca3af;
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }

    .fade-in {
      opacity: 1 !important;
    }

    .stat-card {
      background: #161b22;
      border-radius: 16px;
      padding: 25px;
      text-align: center;
      transition: 0.3s ease;
      border: 1px solid #21262d;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 20px rgba(88, 166, 255, 0.2);
    }

    .stat-icon {
      font-size: 2.5rem;
      margin-bottom: 10px;
      color: #58a6ff;
    }

    .gallery img {
      border-radius: 12px;
      width: 100%;
      height: 240px;
      object-fit: cover;
      border: 2px solid #21262d;
      transition: 0.3s;
    }

    .gallery img:hover {
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(240, 84, 84, 0.4);
    }

    .footer {
      background: #111827;
      padding: 30px 20px;
      color: #9ca3af;
      text-align: center;
      border-top: 2px solid #1f2937;
      margin-top: 50px;
    }

    .footer a {
      color: #58a6ff;
      text-decoration: none;
      margin: 0 10px;
    }

    .footer a:hover {
      color: #f05454;
    }
    /* ANIMASI GLOBAL */
    .fade-up {
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1s forwards;
    }
    @keyframes fadeUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .hover-zoom {
      transition: transform 0.35s ease, box-shadow 0.35s ease;
    }
    .hover-zoom:hover {
      transform: scale(1.08);
      box-shadow: 0 0 25px rgba(88, 166, 255, 0.35);
    }

    /* DASHBOARD BOX EFFECT */
    .stat-card {
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: "";
      position: absolute;
      top: -60%;
      left: -60%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(88,166,255,0.15), transparent 60%);
      animation: rotateGlow 8s linear infinite;
    }
    @keyframes rotateGlow {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* NAVBAR GLASS EFFECT (opsional kalau navbar.php mendukung) */
    .glass-nav {
      backdrop-filter: blur(10px);
      background: rgba(15, 23, 42, 0.6) !important;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .gallery img {
      border-radius: 14px;
      border: 2px solid #21262d;
      transition: 0.4s;
    }
    .gallery img:hover {
      transform: scale(1.12) rotate(1.5deg);
      box-shadow: 0 0 25px rgba(240, 84, 84, 0.5);
      filter: brightness(1.15);
    }

    /* BUTTON FUTURISTIC */
    .btn-future {
      background: linear-gradient(135deg, #58a6ff, #1f6feb);
      border: none;
      padding: 12px 25px;
      border-radius: 10px;
      color: white;
      font-weight: 600;
      transition: 0.3s;
    }
    .btn-future:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(88,166,255,0.4);
    }
  </style>
</head>

<body>

  <?php include 'koneksi.php'; ?>
<?php include 'navbar.php'; ?>

<!-- HERO -->
  <div class="hero-section">
    <h2>Open the Page, Begin the Journey — Welcome to <span>Syekar Library</span></h2>
    <p class="lead text-secondary">Knowledge. Focus. Power.</p>
    <img src="asset/ogoy.png" class="hero-logo" alt="Logo" />
    <p class="hero-quote" id="quote"></p>
  </div>

  <?php
$anggota = $conn->query("SELECT COUNT(*) AS total FROM anggota")->fetch_assoc();
$buku = $conn->query("SELECT COUNT(*) AS total FROM buku")->fetch_assoc();
$peminjaman = $conn->query("SELECT COUNT(*) AS total FROM peminjaman")->fetch_assoc();
?>
  <!-- STATS -->
  <div class="container my-5">
    <div class="row text-center g-4">
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <h5 class="fw-bold">Total Anggota</h5>
          <p class=\"fs-3 fw-bold text-light\"><?= $anggota['total']; ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-book"></i></div>
          <h5 class="fw-bold">Total Buku</h5>
          <p class=\"fs-3 fw-bold text-light\"><?= $buku['total']; ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-book-reader"></i></div>
          <h5 class="fw-bold">Total Peminjaman</h5>
          <p class=\"fs-3 fw-bold text-light\"><?= $peminjaman['total']; ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>&copy; 2025 Syekar Library</p>
    <a href="#">Kebijakan Privasi</a> |
    <a href="#">Kontak</a>
    <div class="mt-3 d-flex justify-content-center gap-4">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </div>


  <script>
    const quotes = [
      "Discipline beats talent when talent lacks discipline.",
      "Knowledge is power, but focus is dominance.",
      "Strong mind, strong future.",
      "Grind in silence, read in peace.",
      "Level up your mind every day."
    ];

    let quoteIndex = 0;
    const quoteEl = document.getElementById("quote");

    function showQuote() {
      quoteEl.classList.remove("fade-in");
      setTimeout(() => {
        quoteEl.textContent = quotes[quoteIndex];
        quoteEl.classList.add("fade-in");
        quoteIndex = (quoteIndex + 1) % quotes.length;
      }, 300);
    }

    showQuote();
    setInterval(showQuote, 3000);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
