<?php
$host = 'localhost';
$db = 'projectpweb';
$user = 'root';
$pass = '';

try {
    $dsn = "mysql:host=$host;dbname=$db";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Menambah data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tambah') {
        $kodepemasok = $_POST['kodepemasok'];
        $namapemasok = $_POST['namapemasok'];
        $alamat = $_POST['alamat'];
        $kota = $_POST['kota'];
        $telepon = $_POST['telepon'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare("INSERT INTO pemasok (kodepemasok, namapemasok, alamat, kota, telepon, email) VALUES (:kodepemasok, :namapemasok, :alamat, :kota, :telepon, :email)");
        $stmt->execute([
            'kodepemasok' => $kodepemasok,
            'namapemasok' => $namapemasok,
            'alamat' => $alamat,
            'kota' => $kota,
            'telepon' => $telepon,
            'email' => $email
        ]);

        $message = "Data berhasil ditambahkan!";
    }

    // Mengedit data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
        $kodepemasok = $_POST['kodepemasok'];
        $namapemasok = $_POST['namapemasok'];
        $alamat = $_POST['alamat'];
        $kota = $_POST['kota'];
        $telepon = $_POST['telepon'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare("UPDATE pemasok SET namapemasok = :namapemasok, alamat = :alamat, kota = :kota, telepon = :telepon, email = :email WHERE kodepemasok = :kodepemasok");
        $stmt->execute([
            'kodepemasok' => $kodepemasok,
            'namapemasok' => $namapemasok,
            'alamat' => $alamat,
            'kota' => $kota,
            'telepon' => $telepon,
            'email' => $email
        ]);

        $message = "Data berhasil diupdate!";
    }

    // Menghapus data
    if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['kodepemasok'])) {
        $kodepemasok = $_GET['kodepemasok'];

        $stmt = $pdo->prepare("DELETE FROM pemasok WHERE kodepemasok = :kodepemasok");
        $stmt->execute(['kodepemasok' => $kodepemasok]);

        $message = "Data berhasil dihapus!";
    }

    // Mengambil data untuk ditampilkan dan diedit
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['kodepemasok'])) {
        $kodepemasok = $_GET['kodepemasok'];

        $stmt = $pdo->prepare("SELECT * FROM pemasok WHERE kodepemasok = :kodepemasok");
        $stmt->execute(['kodepemasok' => $kodepemasok]);
        $pemasok = $stmt->fetch();
    }

    // Menampilkan data
    $stmt = $pdo->query("SELECT * FROM pemasok");
    $pemasoks = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pemasok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h2 {
            color: #333;
        }
        .message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        form input, form button {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background: #333;
            color: #fff;
            cursor: pointer;
        }
        form button:hover {
            background: #555;
        }
        table {
            width: 100%;
            max-width: 1000px;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            color: #fff;
            border-radius: 3px;
            margin-right: 5px;
        }
        .actions a.edit {
            background: #007bff;
        }
        .actions a.delete {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <h2><?php echo isset($pemasok) ? 'Edit Pemasok' : 'Tambah Pemasok'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?php echo isset($pemasok) ? 'edit' : 'tambah'; ?>">
        <?php if (isset($pemasok)): ?>
            <input type="hidden" name="kodepemasok" value="<?php echo $pemasok['kodepemasok']; ?>">
        <?php endif; ?>
        Kode Pemasok: <input type="text" name="kodepemasok" value="<?php echo isset($pemasok) ? $pemasok['kodepemasok'] : ''; ?>" <?php echo isset($pemasok) ? 'readonly' : ''; ?> required><br>
        Nama Pemasok: <input type="text" name="namapemasok" value="<?php echo isset($pemasok) ? $pemasok['namapemasok'] : ''; ?>" required><br>
        Alamat: <input type="text" name="alamat" value="<?php echo isset($pemasok) ? $pemasok['alamat'] : ''; ?>" required><br>
        Kota: <input type="text" name="kota" value="<?php echo isset($pemasok) ? $pemasok['kota'] : ''; ?>" required><br>
        Telepon: <input type="text" name="telepon" value="<?php echo isset($pemasok) ? $pemasok['telepon'] : ''; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo isset($pemasok) ? $pemasok['email'] : ''; ?>" required><br>
        <button type="submit"><?php echo isset($pemasok) ? 'Update' : 'Tambah'; ?></button>
    </form>

    <h2>Data Pemasok</h2>
    <table>
        <tr>
            <th>Kode Pemasok</th>
            <th>Nama Pemasok</th>
            <th>Alamat</th>
            <th>Kota</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($pemasoks as $pemasok): ?>
        <tr>
            <td><?php echo $pemasok['kodepemasok']; ?></td>
            <td><?php echo $pemasok['namapemasok']; ?></td>
            <td><?php echo $pemasok['alamat']; ?></td>
            <td><?php echo $pemasok['kota']; ?></td>
            <td><?php echo $pemasok['telepon']; ?></td>
            <td><?php echo $pemasok['email']; ?></td>
            <td class="actions">
                <a href="?action=edit&kodepemasok=<?php echo $pemasok['kodepemasok']; ?>" class="edit">Edit</a>
                <a href="?action=hapus&kodepemasok=<?php echo $pemasok['kodepemasok']; ?>" class="delete" onclick="return confirm('Anda yakin ingin menghapus data ini?');">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
