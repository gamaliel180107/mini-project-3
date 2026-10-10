<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Generate CSRF token for delete form
$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

$q = trim($_GET["q"] ?? "");
if ($q !== "") {
    $sql = "SELECT * FROM products WHERE name LIKE :q OR category LIKE :q ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["q" => "%$q%"]);
    $products = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Product Manager</h1>
            <a href="create.php" class="btn btn-primary">Tambah Produk</a>
        </header>

        <?php if (($_GET["status"] ?? "") === "created"): ?>
            <div class="alert alert-success">Produk berhasil ditambahkan.</div>
        <?php endif; ?>
        <?php if (($_GET["status"] ?? "") === "updated"): ?>
            <div class="alert alert-success">Produk berhasil diubah.</div>
        <?php endif; ?>
        <?php if (($_GET["status"] ?? "") === "deleted"): ?>
            <div class="alert alert-success">Produk berhasil dihapus.</div>
        <?php endif; ?>
        <?php if (($_GET["status"] ?? "") === "error"): ?>
            <div class="alert alert-danger">Terjadi kesalahan.</div>
        <?php endif; ?>

        <form method="GET" class="search-bar">
            <input type="text" name="q" placeholder="Cari nama atau kategori..." value="<?= htmlspecialchars($q, ENT_QUOTES, "UTF-8") ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q !== ""): ?>
                <a href="index.php" class="btn btn-warning">Reset</a>
            <?php endif; ?>
        </form>

        <div class="products">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($p["name"], ENT_QUOTES, "UTF-8") ?></h3>
                        <p>Kategori: <?= htmlspecialchars($p["category"], ENT_QUOTES, "UTF-8") ?></p>
                        <p>Stok: <?= (int)$p["stock"] ?></p>
                        <p class="price">Rp <?= number_format($p["price"], 0, ",", ".") ?></p>
                        
                        <div class="card-actions">
                            <a href="edit.php?id=<?= $p["id"] ?>" class="btn btn-warning">Edit</a>
                            <form method="POST" action="delete.php" style="margin: 0;" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                <input type="hidden" name="id" value="<?= $p["id"] ?>">
                                <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada produk ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
