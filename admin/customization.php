<?php 
require_once '../config/db.php';
include 'includes/header.php'; 

// Buscar configurações atuais
$stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$success = "";

if (isset($_POST['save_customization'])) {
    $updates = [
        'theme_color' => $_POST['theme_color'],
        'availability_status' => $_POST['availability_status'],
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
        'github_prof_url' => trim($_POST['github_prof_url'] ?? ''),
        'github_personal_url' => trim($_POST['github_personal_url'] ?? '')
    ];

    foreach ($updates as $key => $val) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $check->execute([$key]);
        if ($check->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$val, $key]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $stmt->execute([$key, $val]);
        }
        $settings[$key] = $val; // Atualiza o array local
    }

    // Sincronizar social_links se aplicável
    if (!empty($_POST['linkedin_url'])) {
        $stmt_l = $pdo->prepare("UPDATE social_links SET url = ? WHERE LOWER(platform) = 'linkedin'");
        $stmt_l->execute([$_POST['linkedin_url']]);
    }
    if (!empty($_POST['github_prof_url'])) {
        $stmt_g = $pdo->prepare("UPDATE social_links SET url = ? WHERE LOWER(platform) = 'github'");
        $stmt_g->execute([$_POST['github_prof_url']]);
    }

    $success = "Aparência e links sociais atualizados com sucesso!";
}

$theme_color = $settings['theme_color'] ?? 'indigo';
$colors = [
    'indigo' => ['name' => 'Índigo (Padrão)', 'hex' => '#6366f1'],
    'blue' => ['name' => 'Azul Oceano', 'hex' => '#3b82f6'],
    'emerald' => ['name' => 'Esmeralda', 'hex' => '#10b981'],
    'rose' => ['name' => 'Ruby/Rosa', 'hex' => '#f43f5e'],
    'amber' => ['name' => 'Âmbar', 'hex' => '#f59e0b'],
    'violet' => ['name' => 'Violeta', 'hex' => '#8b5cf6'],
    'cyan' => ['name' => 'Ciano', 'hex' => '#06b6d4'],
    'slate' => ['name' => 'Grafite', 'hex' => '#64748b']
];
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Aparência & Links Sociais</h2>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        
        <!-- Seção: Cores -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-palette text-indigo-500 mr-2"></i> Tema Principal (Cor)</h3>
            <p class="text-sm text-gray-500 mb-6">Escolha a cor principal que será aplicada nos botões, ícones, linhas do tempo e detalhes de todo o site.</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach ($colors as $key => $color): ?>
                <label class="relative cursor-pointer group">
                    <input type="radio" name="theme_color" value="<?php echo $key; ?>" class="peer sr-only" <?php echo $theme_color === $key ? 'checked' : ''; ?>>
                    <div class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-<?php echo $key; ?>-500 peer-checked:bg-<?php echo $key; ?>-50 hover:bg-gray-50 transition-all text-center">
                        <div class="w-10 h-10 rounded-full mx-auto mb-2 shadow-inner" style="background-color: <?php echo $color['hex']; ?>"></div>
                        <span class="block text-sm font-bold text-gray-700 group-hover:text-<?php echo $key; ?>-600 peer-checked:text-<?php echo $key; ?>-700">
                            <?php echo $color['name']; ?>
                        </span>
                        
                        <!-- Checked Icon -->
                        <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-<?php echo $key; ?>-500 transition-opacity">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Seção: Links Sociais & GitHub -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-share-alt text-indigo-500 mr-2"></i> Links das Redes Sociais & GitHub</h3>
            <p class="text-xs text-gray-500 mb-6">Configure o LinkedIn e os repositórios do GitHub exibidos na página principal e no Laboratório.</p>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-linkedin text-blue-600 mr-2"></i> Link do Perfil no LinkedIn
                    </label>
                    <input type="url" name="linkedin_url" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? 'https://www.linkedin.com/in/gabrielrobertson-s/'); ?>" class="w-full p-3 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="https://www.linkedin.com/in/seu-perfil">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-1">
                            <i class="fab fa-github text-slate-900 mr-2"></i> GitHub Profissional
                        </label>
                        <p class="text-xs text-slate-500 mb-3">Exibido na **Página Principal** e na sidebar do portfólio.</p>
                        <input type="url" name="github_prof_url" value="<?php echo htmlspecialchars($settings['github_prof_url'] ?? $settings['github_url'] ?? 'https://github.com/Gabrielrsc'); ?>" class="w-full p-3 border border-slate-300 rounded-lg text-sm bg-white" placeholder="https://github.com/seu-github-profissional">
                    </div>

                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                        <label class="block text-sm font-bold text-purple-900 mb-1">
                            <i class="fab fa-github text-purple-700 mr-2"></i> GitHub Pessoal / Projetos
                        </label>
                        <p class="text-xs text-purple-600 mb-3">Exibido no **Laboratório de Hobbies & Experimentos**.</p>
                        <input type="url" name="github_personal_url" value="<?php echo htmlspecialchars($settings['github_personal_url'] ?? 'https://github.com/Gabrielrsc'); ?>" class="w-full p-3 border border-purple-300 rounded-lg text-sm bg-white" placeholder="https://github.com/seu-github-pessoal">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-circle text-emerald-500 text-xs mr-2"></i> Status de Disponibilidade (Bolinha Verde)
                    </label>
                    <input type="text" name="availability_status" value="<?php echo htmlspecialchars($settings['availability_status'] ?? 'Disponível para novos projetos'); ?>" class="w-full p-3 border rounded-lg text-sm" required>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end bg-white p-4 rounded-xl shadow-sm border border-gray-100 sticky bottom-4 z-50">
            <button type="submit" name="save_customization" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-md flex items-center">
                <i class="fas fa-save mr-2"></i> Salvar Customizações
            </button>
        </div>

    </form>
</div>

<?php include 'includes/footer.php'; ?>
