<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

$user = getCurrentUser(); // menggunakan fetch() di dalam fungsi

$pageTitle = 'Profil';
require_once 'layout_header.php';
?>

<div class="panel">
    <div class="speed-lines"></div>
    <div class="panel-title">👤 Profil Saya</div>

    <div class="user-card" style="margin-bottom:1.5rem">
        <div class="avatar avatar-lg">
            <?= mb_strtoupper(mb_substr($user['name'], 0, 1)) ?>
        </div>
        <div class="user-info">
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p>📧 <?= htmlspecialchars($user['email']) ?></p>
            <p>📅 Bergabung: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
            <?php if ($user['bio']): ?>
                <div class="bio" style="margin-top:10px">
                    <strong>Backstory:</strong><br>
                    <?= nl2br(htmlspecialchars($user['bio'])) ?>
                </div>
            <?php else: ?>
                <p style="color:#aaa;margin-top:8px;font-style:italic">Belum ada biografi.</p>
            <?php endif; ?>
        </div>
    </div>

    <div style="display:flex;gap:.8rem;flex-wrap:wrap">
        <a href="edit_profile.php" class="btn btn-blue">✏️ Edit Profil</a>
        <a href="users.php" class="btn btn-secondary">📋 Lihat Semua User</a>
    </div>
</div>

<!-- Info Panel -->
<div class="panel" style="padding:1.2rem 2rem">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;text-align:center">
        <div>
            <div style="font-family:'Bangers',cursive;font-size:2rem;color:var(--accent)">
                <?= date('Y') - date('Y', strtotime($user['created_at'])) > 0
                    ? date('Y') - date('Y', strtotime($user['created_at'])) . ' Thn'
                    : 'Baru' ?>
            </div>
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#888">Bergabung</div>
        </div>
        <div>
            <div style="font-family:'Bangers',cursive;font-size:2rem;color:var(--blue)">
                <?= strlen($user['bio'] ?? '') ?> <small style="font-size:1rem">char</small>
            </div>
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#888">Panjang Bio</div>
        </div>
        <div>
            <div style="font-family:'Bangers',cursive;font-size:2rem;color:var(--accent2)">
                <?= date('H:i', strtotime($user['updated_at'] ?? $user['created_at'])) ?>
            </div>
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#888">Edit Terakhir</div>
        </div>
    </div>
</div>

<?php require_once 'layout_footer.php'; ?>