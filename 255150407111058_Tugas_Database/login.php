<?php
require_once 'config.php';

if (isLoggedIn()) redirect('profile.php');

$error = '';
$oldEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $oldEmail = $email;

    if ($email === '' || $password === '') {
        $error = 'Email dan password tidak boleh kosong.';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(); // fetch() untuk satu user

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Email atau password salah. Coba lagi, pahlawan!';
        } else {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            redirect('profile.php');
        }
    }
}

$pageTitle = 'Login';
require_once 'layout_header.php';
?>

<div class="panel">
    <div class="speed-lines"></div>
    <div class="panel-title">🔑 Masuk ke Dunia Manga</div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($oldEmail) ?>"
                   placeholder="hero@manga.com" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" required>
        </div>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap">
            <button type="submit" class="btn btn-primary">⚡ Login</button>
            <span>Belum punya akun? <a href="register.php" class="link">Daftar di sini</a></span>
        </div>
    </form>
</div>

<!-- Dekorasi speech bubble -->
<div style="max-width:380px;margin-top:-.5rem">
    <div class="bubble">
        "Setiap petualangan besar dimulai dari satu langkah... atau satu login!" 🗡️
    </div>
</div>

<?php require_once 'layout_footer.php'; ?>