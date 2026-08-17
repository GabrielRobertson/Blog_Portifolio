<?php
ob_start();
session_start();
if (!isset($_SESSION['logged_in']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit;
}


// Buscar cor do tema global
$theme_color = 'indigo';
if (isset($pdo)) {
    $stmt = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'theme_color'");
    if ($row = $stmt->fetch()) {
        $theme_color = $row['setting_value'];
    }
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo | Gabriel Robertson</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col text-slate-700">
    <!-- Navbar Superior -->
    <header class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex flex-wrap justify-between items-center gap-4">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold leading-tight">Painel ADM</h1>
                    <p class="text-[10px] text-slate-400 font-mono">Gabriel Robertson • Portfólio</p>
                </div>
            </a>

            <!-- Links Rápidos de Gestão -->
            <nav class="flex flex-wrap items-center gap-1 text-xs font-semibold">
                <a href="index.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo $current_page == 'index.php' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-chart-pie text-xs"></i> <span>Dashboard</span>
                </a>
                <a href="projects.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo in_array($current_page, ['projects.php', 'edit_project.php']) ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-folder-open text-xs"></i> <span>Portfólio</span>
                </a>
                <a href="skills.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo $current_page == 'skills.php' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-code text-xs"></i> <span>Skills</span>
                </a>
                <a href="education.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo $current_page == 'education.php' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-graduation-cap text-xs"></i> <span>Formação</span>
                </a>
                <a href="experience.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo $current_page == 'experience.php' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-briefcase text-xs"></i> <span>Jornada</span>
                </a>
                <a href="posts.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo in_array($current_page, ['posts.php', 'edit_post.php']) ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-flask text-xs"></i> <span>Laboratório</span>
                </a>
                <a href="profile.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo $current_page == 'profile.php' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-id-card text-xs"></i> <span>Perfil & Textos</span>
                </a>
                <a href="customization.php" class="px-3 py-2 rounded-lg transition-all flex items-center gap-1.5 <?php echo $current_page == 'customization.php' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
                    <i class="fas fa-paint-brush text-xs"></i> <span>Aparência</span>
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="../index.php" target="_blank" class="bg-slate-800 text-slate-200 hover:bg-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 border border-slate-700">
                    <i class="fas fa-external-link-alt text-[10px]"></i> <span>Ver Site</span>
                </a>
                <a href="logout.php" class="bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                    <i class="fas fa-sign-out-alt"></i> <span>Sair</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 flex-grow">
