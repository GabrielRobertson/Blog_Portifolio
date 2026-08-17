<?php 
require_once 'config/db.php'; 

// Buscar configurações globais
$settings_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
$global_settings = [];
while ($row = $settings_stmt->fetch()) {
    $global_settings[$row['setting_key']] = $row['setting_value'];
}
$theme_color = $global_settings['theme_color'] ?? 'indigo';

$pageTitle = "Blog & Portfólio Tech";
include 'includes/head.php'; 
include 'includes/sidebar.php'; 
?>

<div id="main-content" class="flex-grow p-4 md:p-8 flex flex-col justify-between min-h-screen min-w-0 space-y-12">
    <div class="space-y-12">
        <!-- 1. Sobre Mim -->
        <?php include 'includes/header_profile.php'; ?>

        <!-- 2. Hard Skills, 3. Formação, 4. Jornada -->
        <?php include 'includes/about_detailed.php'; ?>

        <!-- 5. Portfólio -->
        <?php include 'includes/portfolio_section.php'; ?>

        <!-- 6. Contatos -->
        <?php include 'includes/contact_section.php'; ?>
    </div>

    <?php include 'includes/footer.php'; ?>


