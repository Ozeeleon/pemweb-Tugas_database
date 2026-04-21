<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('login.php');

$pdo  = getDB();
$stmt = $pdo->query("SELECT id, name, email, bio, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(); // fetchAll() untuk daftar user

$pageTitle = 'Daftar User';
require_once 'layout_header.php';
?>

<div class="panel">
    <div class="speed-lines"></div>
    <div class="panel-title">📋 Semua Penghuni MangaVerse</div>

    <div class="alert alert-info">
        Total <strong><?= count($users) ?></strong> karakter terdaftar di dunia ini.
    </div>

    <?php if (empty($users)): ?>
        <p style="text-align:center;color:#888;padding:2rem">Belum ada user yang terdaftar.</p>
    <?php else: ?>
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Karakter</th>
                    <th>Email</th>
                    <th>Backstory</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $u): ?>
                <tr>
                    <td style="font-weight:700;color:#aaa"><?= $i + 1 ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar" style="width:38px;height:38px;font-size:1rem">
                                <?= mb_strtoupper(mb_substr($u['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <strong><?= htmlspecialchars($u['name']) ?></strong>
                                <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                    <span class="badge badge-you">Kamu</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td style="max-width:200px;color:#555">
                        <?php if ($u['bio']): ?>
                            <?= htmlspecialchars(mb_substr($u['bio'], 0, 80)) ?>
                            <?= mb_strlen($u['bio']) > 80 ? '...' : '' ?>
                        <?php else: ?>
                            <em style="color:#bbb">—</em>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;color:#888;font-size:13px">
                        <?= date('d M Y', strtotime($u['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'layout_footer.php'; ?>