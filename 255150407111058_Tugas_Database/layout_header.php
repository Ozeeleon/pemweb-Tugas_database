<?php
// layout.php — dipanggil di setiap halaman
// Diharapkan $pageTitle sudah di-set sebelum include ini
$pageTitle = $pageTitle ?? 'MangaVerse';
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> — MangaVerse</title>
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
:root {
    --ink: #0d0d0d;
    --paper: #faf6ef;
    --panel: #ffffff;
    --accent: #e63946;
    --accent2: #f4a261;
    --blue: #457b9d;
    --shadow: #0d0d0d;
    --border: 3px solid #0d0d0d;
    --radius: 4px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: var(--paper);
    color: var(--ink);
    font-family: 'Nunito', sans-serif;
    font-size: 15px;
    min-height: 100vh;
    /* Halftone dot background */
    background-image: radial-gradient(circle, #c9b99a44 1px, transparent 1px);
    background-size: 20px 20px;
}

/* ── HEADER ── */
header {
    background: var(--ink);
    color: var(--paper);
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    border-bottom: 4px solid var(--accent);
    position: sticky;
    top: 0;
    z-index: 100;
}

.logo {
    font-family: 'Bangers', cursive;
    font-size: 2.2rem;
    letter-spacing: 3px;
    color: var(--paper);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}
.logo span { color: var(--accent); }

nav { display: flex; gap: 6px; align-items: center; }

nav a, nav button {
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    padding: 7px 14px;
    border-radius: var(--radius);
    transition: all .15s;
    border: 2px solid transparent;
    cursor: pointer;
    background: transparent;
    color: var(--paper);
    text-transform: uppercase;
    letter-spacing: 1px;
}
nav a:hover { border-color: var(--paper); }
nav a.active, nav a:focus { background: var(--accent); color: #fff; border-color: var(--accent); }

nav .btn-logout {
    background: var(--accent);
    color: #fff;
    border-color: var(--accent);
}
nav .btn-logout:hover { background: #c1121f; border-color: #c1121f; }

/* ── MAIN WRAPPER ── */
main {
    max-width: 900px;
    margin: 2.5rem auto;
    padding: 0 1.5rem;
}

/* ── PANEL (comic-book card) ── */
.panel {
    background: var(--panel);
    border: var(--border);
    border-radius: var(--radius);
    box-shadow: 6px 6px 0 var(--shadow);
    padding: 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.panel::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: repeating-linear-gradient(90deg, var(--accent) 0 18px, var(--ink) 18px 36px);
}

.panel-title {
    font-family: 'Bangers', cursive;
    font-size: 2rem;
    letter-spacing: 2px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.panel-title::after {
    content: '';
    flex: 1;
    height: 3px;
    background: var(--ink);
}

/* ── FORM ── */
.form-group { margin-bottom: 1.1rem; }

label {
    display: block;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="password"],
textarea {
    width: 100%;
    padding: 10px 14px;
    border: var(--border);
    border-radius: var(--radius);
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    background: var(--paper);
    color: var(--ink);
    transition: box-shadow .15s;
    outline: none;
}
input:focus, textarea:focus {
    box-shadow: 4px 4px 0 var(--accent);
}
textarea { resize: vertical; min-height: 90px; }

/* ── BUTTONS ── */
.btn {
    display: inline-block;
    padding: 10px 24px;
    font-family: 'Bangers', cursive;
    font-size: 1.1rem;
    letter-spacing: 2px;
    border: var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
    box-shadow: 4px 4px 0 var(--shadow);
}
.btn:active { transform: translate(3px, 3px); box-shadow: 1px 1px 0 var(--shadow); }

.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover { background: #c1121f; }

.btn-secondary { background: var(--paper); color: var(--ink); }
.btn-secondary:hover { background: var(--ink); color: var(--paper); }

.btn-blue { background: var(--blue); color: #fff; }
.btn-blue:hover { background: #1d3557; }

/* ── ALERTS ── */
.alert {
    padding: 12px 16px;
    border: var(--border);
    border-radius: var(--radius);
    font-weight: 700;
    margin-bottom: 1.2rem;
    position: relative;
    padding-left: 44px;
}
.alert::before {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.3rem;
}
.alert-error   { background: #ffe0e0; border-color: var(--accent); color: #7a0019; }
.alert-error::before { content: '✖'; }
.alert-success { background: #d4edda; border-color: #155724; color: #155724; }
.alert-success::before { content: '✔'; }
.alert-info    { background: #d1ecf1; border-color: var(--blue); color: #0c5460; }
.alert-info::before { content: '💬'; }

/* ── USER TABLE ── */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
thead th {
    background: var(--ink);
    color: var(--paper);
    font-family: 'Bangers', cursive;
    font-size: 1rem;
    letter-spacing: 1.5px;
    padding: 10px 14px;
    text-align: left;
}
tbody tr:nth-child(even) { background: #f0ece2; }
tbody tr:hover { background: #ffe8cc; }
tbody td { padding: 10px 14px; border-bottom: 1px solid #ccc; vertical-align: top; }

/* ── BADGE ── */
.badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 2px solid var(--ink);
}
.badge-you { background: var(--accent); color: #fff; }

/* ── AVATAR ── */
.avatar {
    width: 60px; height: 60px;
    border-radius: 50%;
    border: 3px solid var(--ink);
    background: var(--accent2);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Bangers', cursive;
    font-size: 1.6rem;
    color: #fff;
    flex-shrink: 0;
}
.avatar-lg { width: 90px; height: 90px; font-size: 2.4rem; }

.user-card {
    display: flex;
    align-items: center;
    gap: 1.2rem;
}
.user-info h2 { font-size: 1.4rem; font-weight: 900; }
.user-info p  { color: #555; font-size: 13px; margin-top: 2px; }
.user-info .bio { margin-top: 8px; color: var(--ink); font-size: 14px; }

/* ── SPEECH BUBBLE ── */
.bubble {
    background: #fff;
    border: var(--border);
    border-radius: 12px;
    padding: 12px 16px;
    position: relative;
    font-style: italic;
    font-size: 14px;
}
.bubble::after {
    content: '';
    position: absolute;
    bottom: -14px; left: 20px;
    border: 7px solid transparent;
    border-top-color: var(--ink);
}
.bubble::before {
    content: '';
    position: absolute;
    bottom: -10px; left: 20px;
    border: 7px solid transparent;
    border-top-color: #fff;
    z-index: 1;
}

footer {
    text-align: center;
    padding: 1.5rem;
    font-size: 12px;
    color: #888;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.link { color: var(--blue); font-weight: 700; text-decoration: none; }
.link:hover { text-decoration: underline; }

/* ── Speed lines decoration ── */
.speed-lines {
    position: absolute;
    top: 0; right: 0;
    width: 120px; height: 100%;
    background: repeating-linear-gradient(
        -60deg,
        transparent,
        transparent 8px,
        rgba(0,0,0,0.03) 8px,
        rgba(0,0,0,0.03) 10px
    );
    pointer-events: none;
}
</style>
</head>
<body>

<header>
    <a href="index.php" class="logo">📖 Manga<span>Verse</span></a>
    <nav>
        <?php if ($currentUser): ?>
            <a href="profile.php">👤 Profil</a>
            <a href="users.php">📋 Semua User</a>
            <a href="edit_profile.php">✏️ Edit Profil</a>
            <form method="POST" action="logout.php" style="margin:0">
                <button type="submit" class="btn-logout">⛩ Logout</button>
            </form>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="active">Register</a>
        <?php endif; ?>
    </nav>
</header>

<main>