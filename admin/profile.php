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
$error = "";

if (isset($_POST['save_profile'])) {
    $name = trim($_POST['profile_name']);
    $role = trim($_POST['profile_role']);
    $cv_url = trim($_POST['cv_url']);
    $contact_email = trim($_POST['contact_email']);
    $contact_location = trim($_POST['contact_location']);
    $image_path = $settings['profile_image'] ?? 'assets/img/gabriel-profile.png';

    // Lógica de Upload de Imagem de Perfil
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['profile_img']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = "profile_" . time() . "." . $ext;
            $upload_dir = "../assets/img/uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $destination = $upload_dir . $new_name;
            
            if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $destination)) {
                $image_path = "assets/img/uploads/" . $new_name;
            } else {
                $error = "Erro ao mover a imagem para a pasta de destino.";
            }
        } else {
            $error = "Formato não permitido. Use JPG, PNG ou WEBP.";
        }
    }

    if (empty($error)) {
        $linkedin_url = trim($_POST['linkedin_url'] ?? '');
        $github_prof_url = trim($_POST['github_prof_url'] ?? '');
        $github_personal_url = trim($_POST['github_personal_url'] ?? '');

        // Salvar ou atualizar cada chave nas configurações
        $updates = [
            'profile_name' => $name,
            'profile_role' => $role,
            'profile_image' => $image_path,
            'cv_url' => $cv_url,
            'contact_email' => $contact_email,
            'contact_location' => $contact_location,
            'linkedin_url' => $linkedin_url,
            'github_prof_url' => $github_prof_url,
            'github_personal_url' => $github_personal_url
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
            $settings[$key] = $val;
        }

        // Sincronizar tabela social_links
        if (!empty($linkedin_url)) {
            $stmt_l = $pdo->prepare("UPDATE social_links SET url = ? WHERE LOWER(platform) = 'linkedin'");
            $stmt_l->execute([$linkedin_url]);
        }
        if (!empty($github_prof_url)) {
            $stmt_g = $pdo->prepare("UPDATE social_links SET url = ? WHERE LOWER(platform) = 'github'");
            $stmt_g->execute([$github_prof_url]);
        }

        $success = "Dados de perfil, contatos e redes sociais atualizados com sucesso!";
    }
}

// Salvar textos do "Sobre mim"
if (isset($_POST['save_about'])) {
    $about_keys = [
        'about_badge_text', 'about_headline', 'about_subtitle',
        'about_trajectory_title', 'about_trajectory_p1', 'about_trajectory_p2',
        'about_stat1', 'about_stat2', 'about_mission_text',
    ];
    foreach ($about_keys as $key) {
        $val = trim($_POST[$key] ?? '');
        $check = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $check->execute([$key]);
        if ($check->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$val, $key]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $stmt->execute([$key, $val]);
        }
        $settings[$key] = $val;
    }
    $success = "Textos da seção 'Sobre mim' atualizados com sucesso!";
}

?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
            <i class="fas fa-id-card"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Gerenciar Perfil & Informações</h2>
            <p class="text-xs text-slate-500">Altere o nome, cargo, foto de perfil, link do currículo e contatos.</p>
        </div>
    </div>
    
    <?php if ($success): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-xs font-bold flex items-center gap-2">
            <i class="fas fa-check-circle"></i> <span><?php echo $success; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-xs font-bold flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> <span><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <!-- Foto de Perfil -->
        <div class="flex items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="shrink-0">
                <img class="h-20 w-20 object-cover rounded-2xl border-2 border-indigo-500 shadow-md" src="../<?php echo htmlspecialchars($settings['profile_image'] ?? 'assets/img/gabriel-profile.png'); ?>" alt="Foto de Perfil">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Alterar Foto de Perfil</label>
                <input type="file" name="profile_img" class="block w-full text-xs text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-xl file:border-0
                    file:text-xs file:font-bold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100 transition
                "/>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nome Completo *</label>
                <input type="text" name="profile_name" value="<?php echo htmlspecialchars($settings['profile_name'] ?? 'Gabriel Robertson'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Cargo Principal *</label>
                <input type="text" name="profile_role" value="<?php echo htmlspecialchars($settings['profile_role'] ?? 'Analista Técnico de Produção'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Link / URL do Currículo (PDF)</label>
            <input type="text" name="cv_url" value="<?php echo htmlspecialchars($settings['cv_url'] ?? 'assets/curriculo.pdf'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" placeholder="assets/curriculo.pdf ou URL completa do PDF">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">E-mail Profissional</label>
                <input type="email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? 'gabriel@exemplo.com'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Localização</label>
                <input type="text" name="contact_location" value="<?php echo htmlspecialchars($settings['contact_location'] ?? 'São Paulo, Brasil'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
            </div>
        </div>

        <!-- Links das Redes Sociais & GitHub -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2"><i class="fas fa-share-alt text-indigo-500"></i> Redes Sociais & Repositórios</h4>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">LinkedIn URL</label>
                <input type="url" name="linkedin_url" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? 'https://www.linkedin.com/in/gabrielrobertson-s/'); ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" placeholder="https://www.linkedin.com/in/seu-perfil">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">GitHub Profissional (Portfólio Main)</label>
                    <input type="url" name="github_prof_url" value="<?php echo htmlspecialchars($settings['github_prof_url'] ?? $settings['github_url'] ?? 'https://github.com/Gabrielrsc'); ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-purple-700 mb-1">GitHub Pessoal (Laboratório)</label>
                    <input type="url" name="github_personal_url" value="<?php echo htmlspecialchars($settings['github_personal_url'] ?? 'https://github.com/Gabrielrsc'); ?>" class="w-full px-4 py-2.5 bg-white border border-purple-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="index.php" class="px-6 py-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Voltar</a>
            <button type="submit" name="save_profile" class="px-6 py-3 bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-indigo-700 transition shadow-md flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> <span>Salvar Perfil</span>
            </button>
        </div>
    </form>
</div>
<!-- SEÇÃO SOBRE MIM - EDIÇÃO DE TEXTOS -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 max-w-2xl mx-auto mt-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
            <i class="fas fa-user-edit"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Editar Textos — "Sobre Mim"</h2>
            <p class="text-xs text-slate-500">Altere os textos exibidos na seção principal da página.</p>
        </div>
    </div>

    <form method="POST" class="space-y-5">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Badge / Área de Atuação</label>
            <input type="text" name="about_badge_text" value="<?php echo htmlspecialchars($settings['about_badge_text'] ?? ''); ?>"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                placeholder="Ex: Engenharia de Processos • Transformação Digital">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Frase de Destaque (Headline)</label>
            <textarea name="about_headline" rows="2"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition resize-y"
                placeholder="A verdadeira inovação nasce quando..."><?php echo htmlspecialchars($settings['about_headline'] ?? ''); ?></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Subtítulo (texto em itálico abaixo do headline)</label>
            <textarea name="about_subtitle" rows="2"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition resize-y"><?php echo htmlspecialchars($settings['about_subtitle'] ?? ''); ?></textarea>
        </div>

        <div class="pt-2 border-t border-slate-100">
            <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Card de Trajetória</p>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Título do Card</label>
                    <input type="text" name="about_trajectory_title" value="<?php echo htmlspecialchars($settings['about_trajectory_title'] ?? ''); ?>"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Parágrafo 1 <span class="font-normal text-slate-400">(aceita &lt;strong&gt; para negrito)</span></label>
                    <textarea name="about_trajectory_p1" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition resize-y font-mono text-xs"><?php echo htmlspecialchars($settings['about_trajectory_p1'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Parágrafo 2 <span class="font-normal text-slate-400">(aceita &lt;strong&gt; para negrito)</span></label>
                    <textarea name="about_trajectory_p2" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition resize-y font-mono text-xs"><?php echo htmlspecialchars($settings['about_trajectory_p2'] ?? ''); ?></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Estatística 1 (ícone verde)</label>
                        <input type="text" name="about_stat1" value="<?php echo htmlspecialchars($settings['about_stat1'] ?? ''); ?>"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Estatística 2 (ícone azul)</label>
                        <input type="text" name="about_stat2" value="<?php echo htmlspecialchars($settings['about_stat2'] ?? ''); ?>"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-2 border-t border-slate-100">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Texto de Missão & Propósito <span class="font-normal text-slate-400">(aceita &lt;strong&gt; para negrito)</span></label>
            <textarea name="about_mission_text" rows="3"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition resize-y font-mono text-xs"><?php echo htmlspecialchars($settings['about_mission_text'] ?? ''); ?></textarea>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" name="save_about" class="px-6 py-3 bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-emerald-700 transition shadow-md flex items-center gap-2">
                <i class="fas fa-save text-xs"></i> <span>Salvar Textos "Sobre mim"</span>
            </button>
        </div>
    </form>
</div>

<!-- SEÇÃO DE ALTERAÇÃO DE SENHA / CREDENCIAIS ADM -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 max-w-2xl mx-auto mt-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">
            <i class="fas fa-key"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Alterar Credenciais de Acesso ao Painel</h2>
            <p class="text-xs text-slate-500">Defina o nome de usuário e a nova senha para login no ADM.</p>
        </div>
    </div>

    <?php
    if (isset($_POST['save_credentials'])) {
        $new_admin_user = trim($_POST['admin_user']);
        $new_admin_pass = trim($_POST['admin_pass']);

        if (empty($new_admin_user)) {
            echo '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-xs font-bold flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i><span>O nome de usuário não pode estar vazio.</span></div>';
        } else {
            // Salvar/Atualizar usuário
            $check_u = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = 'admin_user'");
            $check_u->execute();
            if ($check_u->fetchColumn() > 0) {
                $stmt_u = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'admin_user'");
                $stmt_u->execute([$new_admin_user]);
            } else {
                $stmt_u = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('admin_user', ?)");
                $stmt_u->execute([$new_admin_user]);
            }
            $settings['admin_user'] = $new_admin_user;

            // Salvar/Atualizar senha com HASH seguro se fornecida
            if (!empty($new_admin_pass)) {
                $hashed_pass = password_hash($new_admin_pass, PASSWORD_DEFAULT);
                $check_p = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = 'admin_password'");
                $check_p->execute();
                if ($check_p->fetchColumn() > 0) {
                    $stmt_p = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'admin_password'");
                    $stmt_p->execute([$hashed_pass]);
                } else {
                    $stmt_p = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('admin_password', ?)");
                    $stmt_p->execute([$hashed_pass]);
                }
                $settings['admin_password'] = $hashed_pass;
            }

            echo '<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-xs font-bold flex items-center gap-2"><i class="fas fa-check-circle"></i><span>Credenciais do Admin atualizadas com sucesso!</span></div>';
        }
    }

    $current_admin_user = $settings['admin_user'] ?? 'admin';
    ?>

    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Usuário do Admin *</label>
                <input type="text" name="admin_user" value="<?php echo htmlspecialchars($current_admin_user); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nova Senha</label>
                <input type="password" name="admin_pass" placeholder="Digite a nova senha..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">Deixe em branco se desejar manter a senha atual.</p>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" name="save_credentials" class="px-6 py-3 bg-purple-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-purple-700 transition shadow-md flex items-center gap-2">
                <i class="fas fa-lock text-xs"></i> <span>Atualizar Credenciais</span>
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

