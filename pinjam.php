<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Peminjaman Buku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
/* ===== DARK THEME CLEAN + NEON BLUE ===== */
body {
  background: #0d0d0d;
  color: #fff;
  font-family: 'Segoe UI', sans-serif;
}

.card {
  background: #1a1a1a;
  border-radius: 1rem;
  border: 1px solid #2a2a2a;
  box-shadow: 0 0 10px rgba(0,0,0,0.4);
  transition: transform .3s ease, box-shadow .3s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 0 18px rgba(0,255,255,0.2);
}

.card-header {
  background: #111;
  border-bottom: 1px solid #333;
  color: #00eaff;
  font-weight: bold;
}

.form-label {
  font-weight: 500;
  color: #ddd;
}

.form-control, .form-select {
  background: #121212;
  color: #fff;
  border: 1px solid #2e2e2e;
  border-radius: 8px;
}

.form-control:focus, .form-select:focus {
  border-color: #00eaff;
  box-shadow: 0 0 5px #00eaff;
}

.btn-custom {
  background-color: #00aaff;
  color: white;
  border-radius: 8px;
  padding: 10px;
  transition: all .3s ease;
}

.btn-custom:hover {
  background-color: #0088cc;
  box-shadow: 0 0 10px rgba(0,170,255,0.5);
}

/* Search box */
.search-input {
  width: 300px;
  border-radius: 14px;
  background: #121212;
  border: 1px solid #2e2e2e;
  color: #fff;
  padding: 10px;
}

/* Stok badge */
.badge-stok {
  background: #00aaff;
  color: white;
  border-radius: 12px;
  font-size: 0.85rem;
  padding: 6px 12px;
}

/* TABLE */
.table {
  color: #fff;
}

.table-striped tbody tr:nth-of-type(odd) {
  background: #1a1a1a;
}

.table-striped tbody tr:nth-of-type(even) {
  background: #141414;
}

.table-light {
  background: #0088cc !important;
  color: white;
}

/* ===== NAVBAR SAMA DENGAN INDEX ===== */
.navbar {
  background: #ffffff !important;
  border-bottom: 1px solid #e5e7eb;
}

.navbar-brand {
  color: #000000 !important;
  font-weight: 700;
}

.navbar .nav-link {
  color: #000000 !important;
  font-weight: 500;
}

.navbar .nav-link:hover {
  color: #000000 !important;
}

.navbar .nav-link.active {
  color: #000000 !important;
  font-weight: 700;
}


  </style>
</head>

<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">

  <!-- FORM PEMINJAMAN -->
  <div class="card p-4">
    <div class="card-header text-center">
      <h2><i class="bi bi-journal-arrow-up"></i> Formulir Peminjaman Buku</h2>
    </div>

    <form method="POST">

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Nama Anggota</label>
          <select class="form-select" name="id_anggota" required>
            <option value="">Pilih Anggota</option>
            <?php
            $q = $conn->query("SELECT * FROM anggota");
            while ($a = $q->fetch_assoc()) {
              echo "<option value='$a[id_anggota]'>$a[nama]</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Tanggal Pinjam</label>
          <input type="date" class="form-control" name="tanggal_pinjam" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-8">
          <label class="form-label">Buku</label>
          <select class="form-select" name="id_buku" required>
            <option value="">Pilih Buku</option>
            <?php
            $b = $conn->query("SELECT * FROM buku WHERE stok > 0");
            while ($bk = $b->fetch_assoc()) {
              echo "<option value='$bk[id_buku]'>$bk[judul]</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Jumlah</label>
          <input type="number" class="form-control" name="jumlah" min="1" required>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" name="pinjam" class="btn btn-custom btn-lg">
          <i class="bi bi-arrow-right-circle"></i> Pinjam Sekarang
        </button>
      </div>

    </form>

    <?php
    if (isset($_POST['pinjam'])) {
      $conn->begin_transaction();
      try {
        $conn->query("INSERT INTO peminjaman (id_anggota, tanggal_pinjam) 
                      VALUES ('$_POST[id_anggota]', '$_POST[tanggal_pinjam]')");

        $id_peminjaman = $conn->insert_id;

        $conn->query("INSERT INTO detail_peminjaman (id_peminjaman, id_buku, jumlah)
                      VALUES ('$id_peminjaman', '$_POST[id_buku]', '$_POST[jumlah]')");

        $conn->query("UPDATE buku SET stok = stok - $_POST[jumlah] WHERE id_buku = $_POST[id_buku]");

        $conn->commit();

        echo "<script>
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Peminjaman berhasil dicatat.',
            footer: 'Denda berlaku jika terlambat mengembalikan buku.'
          });
        </script>";

      } catch (Exception $e) {
        $conn->rollback();
        echo "<script>
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan!'
          });
        </script>";
      }
    }
    ?>

  </div>

  <!-- TABEL BUKU -->
  <div class="card mt-5 p-4">
    <div class="table-search mb-3 d-flex justify-content-end">
      <input type="text" class="search-input" id="searchBuku" placeholder="Cari buku...">
    </div>

    <h3><i class="bi bi-book"></i> Daftar Buku Tersedia</h3>

    <table class="table table-striped mt-3">
      <thead class="table-light">
        <tr>
          <th>Judul Buku</th>
          <th>Pengarang</th>
          <th>Tahun Terbit</th>
          <th>Stok</th>
        </tr>
      </thead>
      <tbody id="bukuTable">
        <?php
        $buku = $conn->query("SELECT * FROM buku WHERE stok > 0");
        while ($bk = $buku->fetch_assoc()) {
          echo "<tr>
                  <td>$bk[judul]</td>
                  <td>$bk[pengarang]</td>
                  <td>$bk[tahun_terbit]</td>
                  <td><span class='badge-stok'>$bk[stok] tersedia</span></td>
                </tr>";
        }
        ?>
      </tbody>
    </table>

  </div>
</div>

<script>
  document.getElementById('searchBuku').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#bukuTable tr');
    rows.forEach(function(row) {
      let title = row.querySelectorAll('td')[0].textContent.toLowerCase();
      row.style.display = title.includes(filter) ? '' : 'none';
    });
  });
</script>

</body>
</html>
