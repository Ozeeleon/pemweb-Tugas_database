<?php
require_once 'config.php';

if (isLoggedIn()) redirect('profile.php');

$errors = [];
$success = '';
$old = ['name' => '', 'email' => '', 'bio' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']    ?? '');
    $email    = trim($_POST['email']   ?? '');
    $password = $_POST['password']     ?? '';
    $bio      = trim($_POST['bio']     ?? '');

    $old = ['name' => $name, 'email' => $email, 'bio' => $bio];

    // Validasi
    if ($name === '')                    $errors[] = 'Nama tidak boleh kosong.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (strlen($password) < 6)           $errors[] = 'Password minimal 6 karakter.';

    if (empty($errors)) {
        $pdo = getDB();

        // Cek email sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain.';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, bio) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$name, $email, $hashedPassword, $bio]);

            $success = 'Registrasi berhasil! Silakan login sekarang.';
            $old = ['name' => '', 'email' => '', 'bio' => ''];
        }
    }
}

$pageTitle = 'Register';
require_once 'layout_header.php';
?>

<div class="panel">
    <div class="speed-lines"></div>
    <div class="panel-title">✨ Gabung Sekarang</div>

    <?php if ($errors): ?>
        <?php foreach ($errors as $e): ?>
            <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <p style="margin-bottom:1rem">
            <a href="login.php" class="btn btn-primary">🔑 Login Sekarang</a>
        </p>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="form-group">
            <label for="name">Nama Karakter</label>
            <input type="text" id="name" name="name"
                   placeholder="Contoh: Naruto Uzumaki"
                   value="<?= htmlspecialchars($old['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   placeholder="hero@manga.com"
                   value="<?= htmlspecialchars($old['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password <small style="font-weight:400;text-transform:none">(min. 6 karakter)</small></label>
            <input type="password" id="password" name="password"
                   placeholder="Buat password rahasiamu" required>
        </div>
        <div class="form-group">
            <label for="bio">Biografi / Backstory</label>
            <textarea id="bio" name="bio"
                      placeholder="Ceritakan sedikit tentang karaktermu..."><?= htmlspecialchars($old['bio']) ?></textarea>
        </div>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap">
            <button type="submit" class="btn btn-primary">⚡ Daftar Sekarang</button>
            <span>Sudah punya akun? <a href="login.php" class="link">Login di sini</a></span>
        </div>
    </form>
</div>

<?php require_once 'layout_footer.php'; ?>