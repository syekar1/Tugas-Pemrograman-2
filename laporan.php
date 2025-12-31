<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Peminjaman</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    /* === DARK NEON MODE === */
    body {
      background: #0a0f24; 
      color: #e2e8f0;
      font-family: "Poppins", sans-serif;
    }

    .card {
      background: rgba(20, 25, 54, 0.85);
      border-radius: 15px;
      backdrop-filter: blur(8px);
      box-shadow: 0 0 15px rgba(0, 255, 255, 0.12);
      border: 1px solid rgba(0, 255, 255, 0.1);
    }

    h3 {
      color: #00e5ff;
      text-shadow: 0 0 8px #00e5ff;
    }

    /* Tabel */
    .table {
      color: #e2e8f0;
      background: rgba(14, 18, 40, 0.7);
    }

    .table thead {
      background: rgba(0, 255, 255, 0.1) !important;
      color: #00e5ff;
      border-bottom: 2px solid #00e5ff;
      text-shadow: 0 0 5px #00e5ff;
    }

    .table tbody tr {
      transition: 0.25s ease;
    }

    .table-hover tbody tr:hover {
      background: rgba(0, 255, 255, 0.12);
      transform: scale(1.01);
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background: rgba(255, 255, 255, 0.03);
    }

    /* Tombol */
    .btn-outline-danger {
      border-color: #ff4d6d;
      color: #ff4d6d;
      transition: 0.3s ease;
    }

    .btn-outline-danger:hover {
      background: #ff4d6d;
      color: white;
      box-shadow: 0 0 10px #ff4d6d;
      transform: scale(1.05);
    }

    /* Badge */
    .text-warning {
      color: #ffc857 !important;
      font-weight: 600;
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
  <div class="card p-4">
    <h3 class="mb-4 text-center"><i class="bi bi-journal-text"></i> Laporan Peminjaman</h3>
    
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="text-center">
          <tr>
            <th>ID</th>
            <th>Nama Anggota</th>
            <th>Judul Buku</th>
            <th>Jumlah</th>
            <th>Tanggal Pinjam</th>
            <th>Batas Akhir</th>
            <th>Tanggal Kembali</th>
            <th>Denda</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $sql = "SELECT p.id_peminjaman, a.nama, b.judul, d.jumlah, 
                         p.tanggal_pinjam, p.tanggal_kembali, 
                         DATE_ADD(p.tanggal_pinjam, INTERVAL 7 DAY) AS batas_akhir
                  FROM peminjaman p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                  JOIN buku b ON d.id_buku = b.id_buku
                  ORDER BY p.id_peminjaman DESC";

          $res = $conn->query($sql);

          if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
              
              $batas = new DateTime($row['batas_akhir']);
              $denda = 0;

              if ($row['tanggal_kembali']) {
                $kembali = new DateTime($row['tanggal_kembali']);
                if ($kembali > $batas) {
                  $selisih = $kembali->diff($batas)->days;
                  $denda = $selisih * 1000;
                }
              }

              echo "
              <tr>
                <td class='text-center'>{$row['id_peminjaman']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['judul']}</td>
                <td class='text-center'>{$row['jumlah']}</td>
                <td class='text-center'>{$row['tanggal_pinjam']}</td>
                <td class='text-center text-warning'>{$row['batas_akhir']}</td>
                <td class='text-center'>".($row['tanggal_kembali'] ?: "<span class='text-danger'>Belum Kembali</span>")."</td>
                <td class='text-center'>
                  ".($denda > 0 ? "Rp " . number_format($denda, 0, ',', '.') : "-")."
                </td>
                <td class='text-center'>
                  <a href='hapus_laporan.php?id={$row['id_peminjaman']}'
                     class='btn btn-sm btn-outline-danger'
                     onclick=\"return confirm('Yakin ingin menghapus laporan ini?');\">
                    <i class='bi bi-trash'></i> Hapus
                  </a>
                </td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='9' class='text-center text-muted'>Tidak ada data peminjaman.</td></tr>";
          }
          ?>
        </tbody>

      </table>
    </div>
  </div>
</div>

</body>
</html>
