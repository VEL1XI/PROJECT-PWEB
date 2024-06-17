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
        $kodeitem = $_POST['kodeitem'];
        $nama = $_POST['nama'];
        $hargabeli = $_POST['hargabeli'];
        $hargajual = $_POST['hargajual'];
        $stok = $_POST['stok'];
        $satuan = $_POST['satuan'];

        $stmt = $pdo->prepare("INSERT INTO item (kodeitem, nama, hargabeli, hargajual, stok, satuan) VALUES (:kodeitem, :nama, :hargabeli, :hargajual, :stok, :satuan)");
        $stmt->execute([
            'kodeitem' => $kodeitem,
            'nama' => $nama,
            'hargabeli' => $hargabeli,
            'hargajual' => $hargajual,
            'stok' => $stok,
            'satuan' => $satuan
        ]);

        echo "<p>Data berhasil ditambahkan!</p>";
    }

    // Mengedit data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
        $kodeitem = $_POST['kodeitem'];
        $nama = $_POST['nama'];
        $hargabeli = $_POST['hargabeli'];
        $hargajual = $_POST['hargajual'];
        $stok = $_POST['stok'];
        $satuan = $_POST['satuan'];

        $stmt = $pdo->prepare("UPDATE item SET nama = :nama, hargabeli = :hargabeli, hargajual = :hargajual, stok = :stok, satuan = :satuan WHERE kodeitem = :kodeitem");
        $stmt->execute([
            'kodeitem' => $kodeitem,
            'nama' => $nama,
            'hargabeli' => $hargabeli,
            'hargajual' => $hargajual,
            'stok' => $stok,
            'satuan' => $satuan
        ]);

        echo "<p>Data berhasil diupdate!</p>";
    }

    // Menghapus data
    if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['kodeitem'])) {
        $kodeitem = $_GET['kodeitem'];

        $stmt = $pdo->prepare("DELETE FROM item WHERE kodeitem = :kodeitem");
        $stmt->execute(['kodeitem' => $kodeitem]);

        echo "<p>Data berhasil dihapus!</p>";
    }

    // Mengambil data untuk ditampilkan dan diedit
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['kodeitem'])) {
        $kodeitem = $_GET['kodeitem'];

        $stmt = $pdo->prepare("SELECT * FROM item WHERE kodeitem = :kodeitem");
        $stmt->execute(['kodeitem' => $kodeitem]);
        $item = $stmt->fetch();
    }

    // Menampilkan data
    $stmt = $pdo->query("SELECT * FROM item");
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Item</title>
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
    <h2><?php echo isset($item) ? 'Edit Item' : 'Tambah Item'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?php echo isset($item) ? 'edit' : 'tambah'; ?>">
        <?php if (isset($item)): ?>
            <input type="hidden" name="kodeitem" value="<?php echo $item['kodeitem']; ?>">
        <?php endif; ?>
        Kode Item: <input type="text" name="kodeitem" value="<?php echo isset($item) ? $item['kodeitem'] : ''; ?>" <?php echo isset($item) ? 'readonly' : ''; ?> required><br>
        Nama: <input type="text" name="nama" value="<?php echo isset($item) ? $item['nama'] : ''; ?>" required><br>
        Harga Beli: <input type="number" name="hargabeli" value="<?php echo isset($item) ? $item['hargabeli'] : ''; ?>" required><br>
        Harga Jual: <input type="number" name="hargajual" value="<?php echo isset($item) ? $item['hargajual'] : ''; ?>" required><br>
        Stok: <input type="number" name="stok" value="<?php echo isset($item) ? $item['stok'] : ''; ?>" required><br>
        Satuan: <input type="text" name="satuan" value="<?php echo isset($item) ? $item['satuan'] : ''; ?>" required><br>
        <button type="submit"><?php echo isset($item) ? 'Update' : 'Tambah'; ?></button>
    </form>

    <h2>Data Item</h2>
    <table>
        <tr>
            <th>Kode Item</th>
            <th>Nama</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo $item['kodeitem']; ?></td>
            <td><?php echo $item['nama']; ?></td>
            <td><?php echo $item['hargabeli']; ?></td>
            <td><?php echo $item['hargajual']; ?></td>
            <td><?php echo $item['stok']; ?></td>
            <td><?php echo $item['satuan']; ?></td>
            <td class="actions">
                <a class="edit" href="?action=edit&kodeitem=<?php echo $item['kodeitem']; ?>">Edit</a>
                <a class="delete" href="?action=hapus&kodeitem=<?php echo $item['kodeitem']; ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?');">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html
