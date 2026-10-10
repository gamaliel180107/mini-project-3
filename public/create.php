<?php
require_once __DIR__ . '/../config/db.php';

$errors = [];
$name = $category = "";
$price = $stock = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "Umum");
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, "stock", FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) {
        $errors["name"] = "Nama minimal 3 karakter.";
    } else {
        // Cek unik
        $stmt = $pdo->prepare("SELECT id FROM products WHERE name = :name");
        $stmt->execute(["name" => $name]);
        if ($stmt->fetch()) {
            $errors["name"] = "Nama produk sudah digunakan.";
        }
    }
    
    if ($price === false || $price <= 0) {
        $errors["price"] = "Harga harus > 0.";
    }
    
    if ($stock === false || $stock < 0) {
        $errors["stock"] = "Stok tidak boleh negatif.";
    }

    if (empty($category)) {
        $errors["category"] = "Kategori tidak boleh kosong.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
        try {
            $stmt->execute([
                "name" => $name,
                "category" => $category,
                "price" => $price,
                "stock" => $stock
            ]);
            header("Location: index.php?status=created");
            exit;
        } catch (PDOException $e) {
            $errors["general"] = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Tambah Produk</h1>
            <a href="index.php" class="btn btn-warning">Kembali</a>
        </header>

        <div class="form-container">
            <?php if (isset($errors["general"])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errors["general"]) ?></div>
            <?php endif; ?>

            <form action="create.php" method="POST">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>" required minlength="3">
                    <?php if (isset($errors["name"])): ?>
                        <span class="error-message"><?= $errors["name"] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="category">Kategori</label>
                    <input type="text" id="category" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES, "UTF-8") ?>" required>
                    <?php if (isset($errors["category"])): ?>
                        <span class="error-message"><?= $errors["category"] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars((string)$price, ENT_QUOTES, "UTF-8") ?>" required min="0.01">
                    <?php if (isset($errors["price"])): ?>
                        <span class="error-message"><?= $errors["price"] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="stock">Stok</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars((string)$stock, ENT_QUOTES, "UTF-8") ?>" required min="0">
                    <?php if (isset($errors["stock"])): ?>
                        <span class="error-message"><?= $errors["stock"] ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </form>
        </div>
    </div>
</body>
</html>
