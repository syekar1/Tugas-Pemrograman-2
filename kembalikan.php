<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengembalian Buku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* === DARK NEON STYLE === */
    body {
      background: #0a0f24;
      color: #e2e8f0;
      font-family: "Poppins", sans-serif;
    }

    .card {
      border-radius: 15px;
      background: rgba(20, 25, 54, 0.85);
      backdrop-filter: blur(8px);
      padding: 25px;
      box-shadow: 0 0 15px rgba(0, 255, 255, 0.15);
      border: 1px solid rgba(0, 255, 255, 0.1);
    }

    h3 {
      color: #00e5ff;
      text-shadow: 0 0 8px #00e5ff;
    }

    .form-label {
      color: #cfd8e3;
      font-weight: 500;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(0, 255, 255, 0.2);
      color: #e2e8f0;
    }

    .form-control:focus {
      border-color: #00e5ff;
      box-shadow: 0 0 8px rgba(0, 229, 255, 0.6);
      background: rgba(255,255,255,0.08);
      color: white;
    }

    .btn-warning {
      background-color: #00e5ff;
      border-color: #00e5ff;
      color: #000;
      font-weight: 600;
      transition: .3s;
    }

    .btn-warning:hover {
      background-color: #00bcd4;
      border-color: #00bcd4;
      box-shadow: 0 0 12px #00e5ff;
      transform: scale(1.04);
    }

    .modal-content {
      border-radius: 15px;
      background: rgba(15, 18, 40, 0.95);
      color: #e2e8f0;
      border: 1px solid rgba(0, 229, 255, 0.25);
      box-shadow: 0 0 20px rgba(0,255,255,0.2);
    }

    .modal-title {
      color: #00e5ff;
      text-shadow: 0 0 8px #00e5ff;
      font-weight: 600;
    }

    .btn-secondary {
      background: #1e293b;
      border-color: #334155;
      color: #fff;
    }

    .btn-secondary:hover {
      background: #334155;
      box-shadow: 0 0 10px rgba(255,255,255,0.2);
    }

    .text-danger {
      color: #ff3864 !important;
      font-weight: 600;
    }

  </style>
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      
      <div class="card">

        <h3 class="text-center mb-4"><i class="bi bi-arrow-repeat"></i> Pengembalian Buku</h3>

        <form method="POST">

          <div class="mb-3">
            <label class="form-label">ID Peminjaman</label>
            <input type="number" class="form-control" name="id_peminjaman" placeholder="Masukkan ID Peminjaman" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Kembali</label>
            <input type="date" class="form-control" name="tanggal_kembali" required>
          </div>

          <div class="d-grid">
            <button type="submit" name="kembalikan" class="btn btn-warning">
              <i class="bi bi-box-arrow-in-down-left"></i> Kembalikan Buku
            </button>
          </div>

        </form>

        <?php
        if (isset($_POST['kembalikan'])) {

          $id_peminjaman = $_POST['id_peminjaman'];
          $tanggal_kembali = $_POST['tanggal_kembali'];

          // Update stok buku
          $cek = $conn->query("SELECT * FROM detail_peminjaman WHERE id_peminjaman='$id_peminjaman'");
          while ($d = $cek->fetch_assoc()) {
            $conn->query("UPDATE buku SET stok = stok + {$d['jumlah']} WHERE id_buku = {$d['id_buku']}");
          }

          // Ambil tanggal pinjam
          $getPinjam = $conn->query("SELECT tanggal_pinjam FROM peminjaman WHERE id_peminjaman='$id_peminjaman'");
          $dataPinjam = $getPinjam->fetch_assoc();

          $tanggal_pinjam = $dataPinjam['tanggal_pinjam'];
          $datetime1 = new DateTime($tanggal_pinjam);
          $datetime2 = new DateTime($tanggal_kembali);
          $selisih = $datetime1->diff($datetime2)->days;

          // Hitung denda
          $denda = 0;
          if ($selisih > 7) {
            $terlambat = $selisih - 7;
            $denda = $terlambat * 1000;
          }

          // Update tanggal kembali
          $conn->query("UPDATE peminjaman SET tanggal_kembali='$tanggal_kembali' WHERE id_peminjaman='$id_peminjaman'");

          // Modal Notifikasi
          echo "
          <div class='modal fade' id='successModal' tabindex='-1'>
            <div class='modal-dialog'>
              <div class='modal-content'>

                <div class='modal-header'>
                  <h5 class='modal-title'><i class='bi bi-check-circle-fill'></i> Pengembalian Berhasil!</h5>
                  <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>

                <div class='modal-body'>
                  Pengembalian dicatat pada <strong>{$tanggal_kembali}</strong>.<br>
          ";

          if ($denda > 0) {
            echo "<br><span class='text-danger'>Denda: Rp " . number_format($denda, 0, ',', '.') . "</span>";
          } else {
            echo "<br>Buku dikembalikan tepat waktu.";
          }

          echo "
                </div>

                <div class='modal-footer'>
                  <button class='btn btn-secondary' data-bs-dismiss='modal'>Tutup</button>
                </div>

              </div>
            </div>
          </div>

          <script>
            window.onload = function() {
              new bootstrap.Modal(document.getElementById('successModal')).show();
            };
          </script>";
        }
        ?>

      </div>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
