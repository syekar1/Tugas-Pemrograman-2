<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Anggota</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

<style>
/* ======== DARK BLUE NEON THEME ======== */
body {
    background: #0a0f1f;
    color: #d8e6ff;
}

.card {
    background: #111726;
    border-radius: 12px;
    border: 1px solid #264d80;
    box-shadow: 0 0 12px rgba(60,120,255,0.2);
}

.form-section {
    padding: 30px;
}

h2, h3 {
    color: #4cc9f0;
    text-shadow: 0 0 6px #3a86ff;
    font-weight: bold;
}

.form-label {
    font-weight: 600;
    color: #bcd0e6;
}

input, textarea, select {
    background: #0f1628;
    color: #d8e6ff;
    border: 1px solid #244a7a;
    border-radius: 8px;
    transition: 0.2s;
}

input:focus, textarea:focus {
    border-color: #4cc9f0;
    box-shadow: 0 0 10px #3a86ff;
    color: #fff;
}

.btn-custom {
    background: linear-gradient(90deg, #3a86ff, #4cc9f0);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 10px;
    transition: 0.3s;
}

.btn-custom:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px #4cc9f0;
}

.btn-sm {
    background: #1b263b;
    border: 1px solid #3a86ff;
    color: #4cc9f0;
}

.btn-sm:hover {
    transform: scale(1.1);
    background: #243550;
}

.table thead {
    background: #1f3b70;
    color: #fff;
}

.table tbody tr:hover {
    background: rgba(76,201,240,0.1);
}

.search-bar input {
    background: #0f1628;
    border: 1px solid #244a7a;
    color: #d8e6ff;
}

.search-bar button {
    background: #3a86ff;
    color: white;
}

.alert {
    background: #102033;
    color: #bcd0e6;
    border-left: 4px solid #4cc9f0;
}

</style>
</head>

<body>

<?php include 'navbar.php'; ?>

<?php
$editMode = false;
$editData = [
  'id_anggota' => '',
  'nama' => '',
  'alamat' => '',
  'tanggal_daftar' => ''
];

if (isset($_GET['edit'])) {
  $id_edit = (int)$_GET['edit'];
  $sql_edit = "SELECT * FROM anggota WHERE id_anggota = $id_edit";
  $result_edit = $conn->query($sql_edit);

  if ($result_edit && $result_edit->num_rows > 0) {
    $editMode = true;
    $editData = $result_edit->fetch_assoc();
  }
}

if (isset($_POST['simpan'])) {
  $nama = $conn->real_escape_string($_POST['nama']);
  $alamat = $conn->real_escape_string($_POST['alamat']);
  $tanggal = $_POST['tanggal_daftar'];

  $query = "INSERT INTO anggota (nama, alamat, tanggal_daftar) VALUES ('$nama', '$alamat', '$tanggal')";
  if ($conn->query($query)) {
    echo "<div class='alert mt-3'>Berhasil menambahkan anggota baru.</div>";
  }
}

if (isset($_POST['update'])) {
  $id = (int)$_POST['id_anggota'];
  $nama = $conn->real_escape_string($_POST['nama']);
  $alamat = $conn->real_escape_string($_POST['alamat']);
  $tanggal = $_POST['tanggal_daftar'];

  $query = "UPDATE anggota SET nama='$nama', alamat='$alamat', tanggal_daftar='$tanggal' WHERE id_anggota=$id";
  if ($conn->query($query)) {
    echo "<div class='alert mt-3'>Data anggota berhasil diperbarui.</div>";
    echo "<meta http-equiv='refresh' content='1; url=tambah_anggota.php'>";
  }
}
?>

<div class="container mt-5">

  <!-- FORM -->
  <div class="card mb-4">
    <div class="card-body form-section">
      <h2 class="mb-4 text-center"><?= $editMode ? 'Edit Anggota' : 'Tambah Anggota' ?></h2>

      <form method="POST">
        <input type="hidden" name="id_anggota" value="<?= $editData['id_anggota'] ?>">

        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" name="nama" required value="<?= htmlspecialchars($editData['nama']) ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea class="form-control" name="alamat" required><?= htmlspecialchars($editData['alamat']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Daftar</label>
          <input type="date" class="form-control" name="tanggal_daftar" required value="<?= $editData['tanggal_daftar'] ?>">
        </div>

        <button type="submit" name="<?= $editMode ? 'update' : 'simpan' ?>" class="btn btn-custom btn-lg w-100">
          <?= $editMode ? 'Update' : 'Simpan' ?>
        </button>
      </form>

    </div>
  </div>

  <!-- TABEL -->
  <div class="card">
    <div class="card-body form-section">
      <h3 class="mb-3">Daftar Anggota</h3>

      <form method="GET" class="search-bar mb-3">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari nama..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
          <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>Tanggal Daftar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="daftarAnggota">

<?php
$where = "";
if (isset($_GET['cari']) && $_GET['cari'] != '') {
  $keyword = $conn->real_escape_string($_GET['cari']);
  $where = "WHERE nama LIKE '%$keyword%'";
}

$sql = "SELECT * FROM anggota $where ORDER BY tanggal_daftar DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $no = 1;
  while ($row = $result->fetch_assoc()) {
    echo "
      <tr id='anggota-{$row['id_anggota']}'>
        <td>{$no}</td>
        <td>".htmlspecialchars($row['nama'])."</td>
        <td>".htmlspecialchars($row['alamat'])."</td>
        <td>{$row['tanggal_daftar']}</td>
        <td>
          <a href='?edit={$row['id_anggota']}' class='btn btn-sm'><i class='fas fa-edit'></i></a>
          <button class='btn btn-danger btn-sm' onclick='hapusAnggota({$row['id_anggota']}, this)'><i class='fas fa-trash'></i></button>
        </td>
      </tr>
    ";
    $no++;
  }
} else {
  echo "<tr><td colspan='5' class='text-center'>Data tidak ditemukan.</td></tr>";
}
?>

          </tbody>
        </table>
      </div>

    </div>
  </div>

</div>


<script>
function hapusAnggota(id, el) {
  if (confirm("Yakin ingin menghapus anggota ini?")) {
    fetch('hapus_anggota.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        el.closest("tr").remove();
      } else {
        alert("Gagal menghapus.");
      }
    });
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
