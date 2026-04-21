<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

$pdo    = getDB();
$errors = [];
$success = '';

// Ambil data user yang sedang login menggunakan fetch()
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(); // fetch() untuk satu user

if (!empty($_FILES['gambar']['name'])) {
    $namaFile = $_FILES['gambar']['name'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // make sure the uploads folder exists, create if necessary
    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destination = $uploadDir . DIRECTORY_SEPARATOR . $namaFile;
    if (!move_uploaded_file($tmpName, $destination)) {
        // handle error if move fails
        echo "<p style='color:red;'>Gagal mengunggah file. Pastikan folder 'uploads' dapat ditulisi.</p>";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']        ?? '');
    $email       = trim($_POST['email']       ?? '');
    $bio         = trim($_POST['bio']         ?? '');
    $newPassword = $_POST['new_password']     ?? '';
    $currPassword= $_POST['current_password'] ?? '';

    if ($name === '') $errors[] = 'Nama tidak boleh kosong.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

    // Cek email duplikat (kecuali email sendiri)
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user['id']]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah dipakai user lain.';
        }
    }

    // Ganti password (opsional)
    if ($newPassword !== '') {
        if (!password_verify($currPassword, $user['password'])) {
            $errors[] = 'Password saat ini tidak benar.';
        } elseif (strlen($newPassword) < 6) {
            $errors[] = 'Password baru minimal 6 karakter.';
        }
    }

    if (empty($errors)) {
        if ($newPassword !== '') {
            $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                "UPDATE users SET name=?, email=?, bio=?, password=? WHERE id=?"
            );
            $stmt->execute([$name, $email, $bio, $hashed, $user['id']]);
        } else {
            $stmt = $pdo->prepare(
                "UPDATE users SET name=?, email=?, bio=? WHERE id=?"
            );
            $stmt->execute([$name, $email, $bio, $user['id']]);
        }

        $_SESSION['user_name'] = $name;

        // Refresh data user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $user = $stmt->fetch(); // fetch() untuk satu user

        $success = 'Profil berhasil diperbarui!';
    }
}

$pageTitle = 'Edit Profil';
require_once 'layout_header.php';
?>

<div class="panel">
    <div class="speed-lines"></div>
    <div class="panel-title">✏️ Edit Profil</div>

    <!-- Tampilkan data user yang sedang login -->
    <div class="user-card" style="margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:2px dashed #ddd">
        <div class="avatar">
            <?= mb_strtoupper(mb_substr($user['name'], 0, 1)) ?>
        </div>
        <div class="user-info">
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p>📧 <?= htmlspecialchars($user['email']) ?></p>
        </div>
    </div>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name"
                       value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Biografi / Backstory</label>
            <textarea id="bio" name="bio"
                      placeholder="Tulis ulang kisahmu..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>

        <div style="border:2px dashed #ccc;border-radius:4px;padding:1rem;margin-bottom:1rem">
            <p style="font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:1px;margin-bottom:.8rem">
                🔒 Ganti Password <small style="font-weight:400;text-transform:none">(kosongkan jika tidak ingin ganti)</small>
            </p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group" style="margin:0">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password"
                           placeholder="Password lama">
                </div>
                <div class="form-group" style="margin:0">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password"
                           placeholder="Min. 6 karakter">
                </div>
            </div>
        </div>

        <div style="display:flex;gap:.8rem;flex-wrap:wrap">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="profile.php" class="btn btn-secondary">← Kembali</a>
        </div>
    </form>
</div>

<?php require_once 'layout_footer.php'; ?>