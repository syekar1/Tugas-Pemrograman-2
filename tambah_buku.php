<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard — Tambah Buku</title>

  <!-- Bootstrap & icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* ======= Dark Neon Futuristic Theme ======= */
    :root{
      --bg:#0b0d0f;
      --panel:#111315;
      --muted:#bfc7d1;
      --accent:#00bfff;
      --accent-2:#0099cc;
      --danger:#e74c3c;
      --glass: rgba(255,255,255,0.03);
      --soft: rgba(255,255,255,0.02);
    }

    html,body{height:100%;}
    body{
      margin:0;
      min-height:100%;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: linear-gradient(180deg,var(--bg) 0%, #060708 100%);
      color: #e6eef8;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      overflow-x:hidden;
    }

    /* layout */
    .app {
      display:flex;
      min-height:100vh;
    }
    /* sidebar */
    .sidebar {
      width:260px;
      background: linear-gradient(180deg,#070809,#0f1113 60%);
      border-right:1px solid rgba(255,255,255,0.03);
      padding:20px;
      position:fixed;
      inset:0 auto 0 0;
      overflow:auto;
      transform:translateX(0);
      transition:transform .35s cubic-bezier(.2,.9,.3,1);
      z-index:1020;
    }
    .sidebar.closed { transform:translateX(-320px); }

    .brand {
      display:flex;
      gap:12px;
      align-items:center;
      margin-bottom:18px;
    }
    .brand .logo {
      width:46px;
      height:46px;
      border-radius:10px;
      background:linear-gradient(135deg,var(--accent), var(--accent-2));
      display:flex;
      align-items:center;
      justify-content:center;
      color:#001;
      font-weight:700;
      box-shadow:0 6px 20px rgba(0,191,255,0.08);
    }
    .brand h1{ font-size:1rem; margin:0; color:var(--muted); font-weight:700; }

    .nav-link {
      color:var(--muted);
      padding:.65rem .6rem;
      display:flex;
      align-items:center;
      gap:10px;
      border-radius:8px;
      transition:all .18s;
      text-decoration:none;
      font-weight:600;
    }
    .nav-link:hover{
      color:#002;
      background: linear-gradient(90deg, rgba(0,191,255,0.12), rgba(0,153,204,0.06));
      box-shadow:0 6px 20px rgba(0,191,255,0.06);
      transform:translateY(-2px);
      color:#eaf6ff;
      text-decoration:none;
    }
    .nav-icon { color:var(--accent); font-size:1.05rem; }

    /* content area */
    .content {
      margin-left:260px;
      padding:28px;
      width:100%;
      transition:margin-left .35s;
    }
    .sidebar.closed + .content { margin-left:48px; }

    /* topbar */
    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:18px;
    }
    .toggle-btn{
      background:transparent;
      border:1px solid rgba(255,255,255,0.03);
      color:var(--muted);
      padding:8px 10px;
      border-radius:8px;
      cursor:pointer;
      transition:all .18s;
    }
    .toggle-btn:hover{
      box-shadow:0 8px 24px rgba(0,191,255,0.06);
      color:var(--accent);
    }

    .searchbar {
      background:var(--soft);
      border-radius:12px;
      padding:6px;
      display:flex;
      align-items:center;
      gap:8px;
      border:1px solid rgba(255,255,255,0.02);
    }
    .searchbar input{
      background:transparent;
      border:0;
      outline:0;
      color:var(--muted);
      width:260px;
    }
    .searchbar input::placeholder { color:#9aa6b9; }

    /* dashboard cards */
    .grid {
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:18px;
      margin-bottom:18px;
    }
    @media(max-width:992px){ .grid{grid-template-columns:repeat(2,1fr);} }
    @media(max-width:650px){ .grid{grid-template-columns:1fr;} .content{margin-left:0;} .sidebar{position:relative;width:100%;transform:none;}}

    .card {
      background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      border:1px solid rgba(255,255,255,0.03);
      border-radius:12px;
      padding:18px;
      transition:transform .2s, box-shadow .2s;
      overflow:hidden;
    }
    .card:hover{
      transform:translateY(-6px);
      box-shadow:0 18px 60px rgba(0,191,255,0.06);
    }

    .stat {
      display:flex; align-items:center; gap:14px;
    }
    .stat .icon {
      width:56px;height:56px;border-radius:12px;
      background: linear-gradient(135deg,var(--accent),var(--accent-2));
      display:flex; align-items:center; justify-content:center;
      color:#001; font-weight:800;
      box-shadow:0 6px 30px rgba(0,191,255,0.06);
      font-size:1.25rem;
    }
    .stat h3{ margin:0; color:#eaf6ff; font-size:1.25rem;}
    .stat p{ margin:0; color:#9fb3c8; font-size:.9rem; }

    /* form and table styles */
    .form-section .form-label{ color:#cfe9ff; font-weight:600; }
    .form-section .form-control{ background:transparent; color:#dceffb; border:1px solid rgba(255,255,255,0.04); }
    .form-section .form-control:focus{ box-shadow:0 0 12px rgba(0,191,255,0.08); border-color:var(--accent); }

    .btn-neon {
      background:linear-gradient(90deg,var(--accent),var(--accent-2));
      border:none;
      color:#001;
      font-weight:700;
      box-shadow:0 8px 30px rgba(0,191,255,0.12);
      transition:transform .18s, box-shadow .18s;
      border-radius:10px;
    }
    .btn-neon:hover{ transform:translateY(-3px); box-shadow:0 18px 60px rgba(0,191,255,0.22); }

    .btn-outline-soft{
      background:transparent;border:1px solid rgba(255,255,255,0.04); color:var(--muted);
    }
    .btn-outline-soft:hover{ color:var(--accent); border-color:rgba(0,191,255,0.12); box-shadow:0 8px 30px rgba(0,191,255,0.04); }

    /* table */
    .table thead th{ border-bottom:1px solid rgba(255,255,255,0.03); color:var(--accent); background:transparent; }
    .table tbody tr{ background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00)); }
    .table td, .table th{ color:#cfe9ff; vertical-align:middle; border-top:1px solid rgba(255,255,255,0.02); }

    /* file preview */
    .cover-preview { width:64px; border-radius:8px; border:1px solid rgba(255,255,255,0.04); }

    /* small helpers */
    .muted { color:#98aebf; font-size:.9rem; }
    .gap-12{ gap:12px; display:flex; align-items:center; }

    /* subtle animations */
    .pulse {
      animation: pulse 2.6s infinite;
    }
    @keyframes pulse {
      0%{ box-shadow:0 0 0 0 rgba(0,191,255,0.06); }
      70%{ box-shadow:0 0 0 10px rgba(0,191,255,0.00); }
      100%{ box-shadow:0 0 0 0 rgba(0,191,255,0.00); }
    }

  </style>
</head>
<body>
  <div class="app">
    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar" role="navigation" aria-label="Sidebar menu">
      <div class="brand">
        <div class="logo">SY</div>
        <div>
          <h1>Syekar Library</h1>
          <div class="muted">Admin Dashboard</div>
        </div>
      </div>

      <nav class="mt-3">
        <a href="index.php" class="nav-link"><i class="bi bi-house nav-icon"></i> Beranda</a>
        <a href="tambah_buku.php" class="nav-link"><i class="bi bi-book nav-icon"></i> Tambah Buku</a>
        <a href="pinjam.php" class="nav-link"><i class="bi bi-journal-arrow-up nav-icon"></i> Peminjaman</a>
        <a href="kembalikan.php" class="nav-link"><i class="bi bi-arrow-counterclockwise nav-icon"></i> Pengembalian</a>
        <a href="laporan.php" class="nav-link"><i class="bi bi-journal-text nav-icon"></i> Laporan</a>
        <a href="tambah_anggota.php" class="nav-link"><i class="bi bi-people nav-icon"></i> Mendaftar Anggota</a>
      </nav>

      <div style="margin-top:20px;">
        <button id="sidebarToggle" class="btn btn-outline-soft w-100"><i class="bi bi-app-indicator"></i> Toggle Sidebar</button>
      </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">
      <div class="topbar">
        <div class="gap-12">
          <button id="mobileToggle" class="toggle-btn" aria-label="toggle sidebar"><i class="bi bi-list"></i></button>
          <div>
            <h2 style="margin:0; font-size:1.3rem;">Tambah Buku</h2>
            <div class="muted">Dashboard / Buku / Tambah</div>
          </div>
        </div>

        <div class="d-flex align-items-center gap-12">
          <div class="searchbar">
            <i class="bi bi-search" style="color:var(--accent);"></i>
            <form id="quickSearch" method="GET" action="tambah_buku.php" style="display:flex;">
              <input name="search" placeholder="Cari buku..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </form>
          </div>

          <button class="btn btn-outline-soft"><i class="bi bi-bell"></i></button>
          <button class="btn btn-outline-soft"><i class="bi bi-person-circle"></i></button>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid">
        <div class="card">
          <div class="stat">
            <div class="icon pulse"><i class="bi bi-people-fill"></i></div>
            <div>
              <?php
                $anggota = $conn->query("SELECT COUNT(*) AS total FROM anggota")->fetch_assoc();
              ?>
              <h3><?php echo (int)$anggota['total']; ?></h3>
              <p class="muted">Total Anggota</p>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="stat">
            <div class="icon"><i class="bi bi-journal-bookmark-fill"></i></div>
            <div>
              <?php
                $bukuCount = $conn->query("SELECT COUNT(*) AS total FROM buku")->fetch_assoc();
              ?>
              <h3><?php echo (int)$bukuCount['total']; ?></h3>
              <p class="muted">Total Buku</p>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="stat">
            <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
            <div>
              <?php
                $pinjamCount = $conn->query("SELECT COUNT(*) AS total FROM peminjaman")->fetch_assoc();
              ?>
              <h3><?php echo (int)$pinjamCount['total']; ?></h3>
              <p class="muted">Total Peminjaman</p>
            </div>
          </div>
        </div>
      </div>

      <!-- MAIN FORM + LIST -->
      <div class="row g-3">
        <div class="col-lg-5">
          <!-- FORM -->
          <div class="card form-section">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
              <div>
                <h5 style="margin:0;color:#eaf6ff;"><?php echo isset($_GET['edit']) ? 'Edit Buku' : 'Tambah Buku Baru'; ?></h5>
                <div class="muted">Isi data buku di sini</div>
              </div>
            </div>

            <?php
            /* Hapus */
            if (isset($_GET['hapus'])) {
              // already handled above; but keep compatibility
            }

            /* Load edit data when ?edit= */
            $edit_mode = false;
            $edit_data = array();
            if (isset($_GET['edit'])) {
              $edit_mode = true;
              $id = intval($_GET['edit']);
              $rs = $conn->query("SELECT * FROM buku WHERE id_buku = '$id'");
              if ($rs && $rs->num_rows > 0) {
                $edit_data = $rs->fetch_assoc();
              } else {
                $edit_mode = false;
              }
            }

            /* Handle POST (simpan/update) */
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              // detect action
              if (isset($_POST['simpan']) || isset($_POST['update'])) {
                $judul = $conn->real_escape_string($_POST['judul']);
                $pengarang = $conn->real_escape_string($_POST['pengarang']);
                $tahun = (int)$_POST['tahun_terbit'];
                $stok = (int)$_POST['stok'];
                $cover_filename = '';

                // handle upload
                if (!empty($_FILES['cover']['name'])) {
                  $allowed = array('jpg','jpeg','png');
                  $name = $_FILES['cover']['name'];
                  $tmp = $_FILES['cover']['tmp_name'];
                  $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                  if (in_array($ext, $allowed)) {
                    if (!is_dir('uploads')) mkdir('uploads', 0777, true);
                    $cover_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/","_",$name);
                    move_uploaded_file($tmp, "uploads/".$cover_filename);
                  }
                }

                if (isset($_POST['simpan'])) {
                  // insert
                  $sql = "INSERT INTO buku (judul,pengarang,tahun_terbit,stok,cover) VALUES ('$judul','$pengarang','$tahun','$stok','$cover_filename')";
                  if ($conn->query($sql)) {
                    echo '<div class="alert alert-success mt-3"><i class="bi bi-check-circle"></i> Buku berhasil ditambahkan.</div>';
                  } else {
                    echo '<div class="alert alert-danger mt-3">Gagal menambahkan: ' . htmlspecialchars($conn->error) . '</div>';
                  }
                } else {
                  // update
                  $id_upd = (int)$_POST['id_buku'];
                  // if new cover uploaded, replace previous file
                  if ($cover_filename) {
                    // get old and delete
                    $old = $conn->query("SELECT cover FROM buku WHERE id_buku='$id_upd'")->fetch_assoc();
                    if (!empty($old['cover']) && file_exists('uploads/'.$old['cover'])) unlink('uploads/'.$old['cover']);
                    $sql = "UPDATE buku SET judul='$judul', pengarang='$pengarang', tahun_terbit='$tahun', stok='$stok', cover='$cover_filename' WHERE id_buku='$id_upd'";
                  } else {
                    $sql = "UPDATE buku SET judul='$judul', pengarang='$pengarang', tahun_terbit='$tahun', stok='$stok' WHERE id_buku='$id_upd'";
                  }
                  if ($conn->query($sql)) {
                    echo '<div class="alert alert-success mt-3"><i class="bi bi-pencil"></i> Data buku diperbarui.</div>';
                  } else {
                    echo '<div class="alert alert-danger mt-3">Gagal update: ' . htmlspecialchars($conn->error) . '</div>';
                  }
                }
              }
            }
            ?>

            <!-- Form -->
            <form method="POST" enctype="multipart/form-data" class="form-section">
              <?php if ($edit_mode) { ?>
                <input type="hidden" name="id_buku" value="<?php echo (int)$edit_data['id_buku']; ?>">
              <?php } ?>

              <div class="mb-2">
                <label class="form-label">Judul Buku</label>
                <input type="text" name="judul" class="form-control" required value="<?php echo $edit_mode ? htmlspecialchars($edit_data['judul']) : ''; ?>">
              </div>

              <div class="mb-2">
                <label class="form-label">Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required value="<?php echo $edit_mode ? htmlspecialchars($edit_data['pengarang']) : ''; ?>">
              </div>

              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label">Tahun Terbit</label>
                  <input type="number" name="tahun_terbit" class="form-control" required value="<?php echo $edit_mode ? htmlspecialchars($edit_data['tahun_terbit']) : ''; ?>">
                </div>
                <div class="col-6">
                  <label class="form-label">Stok</label>
                  <input type="number" name="stok" class="form-control" required value="<?php echo $edit_mode ? htmlspecialchars($edit_data['stok']) : ''; ?>">
                </div>
              </div>

              <div class="mb-2 mt-2">
                <label class="form-label">Cover Buku (jpg/png)</label>
                <input type="file" name="cover" class="form-control">
                <?php if ($edit_mode && !empty($edit_data['cover'])) { ?>
                  <img src="uploads/<?php echo htmlspecialchars($edit_data['cover']); ?>" class="cover-preview mt-2" alt="cover">
                <?php } ?>
              </div>

              <div class="d-grid">
                <?php if ($edit_mode) { ?>
                  <button type="submit" name="update" class="btn btn-neon"><i class="bi bi-pencil-square"></i> Perbarui Buku</button>
                  <a href="tambah_buku.php" class="btn btn-outline-soft mt-2">Batal</a>
                <?php } else { ?>
                  <button type="submit" name="simpan" class="btn btn-neon"><i class="bi bi-save"></i> Simpan Buku</button>
                <?php } ?>
              </div>
            </form>
          </div>
        </div>

        <div class="col-lg-7">
          <!-- LIST / TABLE -->
          <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
              <div>
                <h5 style="margin:0;color:#eaf6ff;">Daftar Buku</h5>
                <div class="muted">Kelola buku perpustakaan</div>
              </div>
              <div>
                <a href="tambah_buku.php" class="btn btn-outline-soft"><i class="bi bi-plus-lg"></i> Baru</a>
              </div>
            </div>

            <?php
            // handle delete if user clicked delete (confirmation via link)
            if (isset($_GET['hapus'])) {
              $id_hapus = (int)$_GET['hapus'];
              // get cover to remove file
              $rowC = $conn->query("SELECT cover FROM buku WHERE id_buku='$id_hapus'")->fetch_assoc();
              if ($rowC && !empty($rowC['cover']) && file_exists('uploads/'.$rowC['cover'])) unlink('uploads/'.$rowC['cover']);
              $conn->query("DELETE FROM buku WHERE id_buku='$id_hapus'");
              echo '<div class="alert alert-success mt-2"><i class="bi bi-trash"></i> Buku dihapus.</div>';
            }
            ?>

            <div class="table-responsive mt-2">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th style="width:120px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $searchQ = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                  $sql = "SELECT * FROM buku WHERE judul LIKE '%$searchQ%' OR pengarang LIKE '%$searchQ%' ORDER BY id_buku DESC";
                  $res = $conn->query($sql);
                  if ($res && $res->num_rows > 0) {
                    while ($r = $res->fetch_assoc()) {
                      echo '<tr>';
                      echo '<td>';
                      if (!empty($r['cover']) && file_exists('uploads/'.$r['cover'])) {
                        echo '<img src="uploads/'.htmlspecialchars($r['cover']).'" class="cover-preview" alt="cover">';
                      } else {
                        echo '-';
                      }
                      echo '</td>';
                      echo '<td>'.htmlspecialchars($r['judul']).'</td>';
                      echo '<td>'.htmlspecialchars($r['pengarang']).'</td>';
                      echo '<td>'.htmlspecialchars($r['tahun_terbit']).'</td>';
                      echo '<td>'.htmlspecialchars($r['stok']).'</td>';
                      echo '<td>';
                      echo '<a href="?edit='.intval($r['id_buku']).'" class="btn btn-warning btn-sm me-1"><i class="bi bi-pencil-square"></i></a>';
                      echo '<a href="?hapus='.intval($r['id_buku']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus?\')"><i class="bi bi-trash"></i></a>';
                      echo '</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="6" class="text-muted">Tidak ada data buku.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- small help card -->
          <div class="card mt-3">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div>
                <strong>Tip</strong>
                <div class="muted">Gunakan tombol edit untuk ubah. Unggah cover untuk tampilan lebih rapi.</div>
              </div>
              <div>
                <button class="btn btn-outline-soft" id="downloadSample">Download CSV</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <footer style="margin-top:28px; text-align:center; color:#7f99ad;">
        &copy; <?php echo date('Y'); ?> Syekar Library — Built with neon.
      </footer>
    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileToggle = document.getElementById('mobileToggle');

    sidebarToggle && sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('closed');
    });
    mobileToggle && mobileToggle.addEventListener('click', function() {
      sidebar.classList.toggle('closed');
    });

    // sample csv download (front-end demo)
    document.getElementById('downloadSample').addEventListener('click', function(){
      const csv = 'judul,pengarang,tahun,stok\n"Contoh Buku","Pengarang",2023,5\n';
      const blob = new Blob([csv], { type: 'text/csv' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'sample_buku.csv'; document.body.appendChild(a); a.click();
      URL.revokeObjectURL(url); a.remove();
    });
  </script>
</body>
</html>
