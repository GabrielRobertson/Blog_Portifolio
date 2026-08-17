<?php 
require_once 'config/db.php'; 

// Buscar configurações globais
$settings_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
$global_settings = [];
while ($row = $settings_stmt->fetch()) {
    $global_settings[$row['setting_key']] = $row['setting_value'];
}
$theme_color = $global_settings['theme_color'] ?? 'indigo';

$pageTitle = "Laboratório de Hobbies & Experimentos";
include 'includes/head.php'; 
include 'includes/sidebar.php'; 
?>

<div id="main-content" class="flex-grow p-4 md:p-8 flex flex-col justify-between min-h-screen min-w-0 space-y-12">
    <div class="space-y-12">
        <!-- Header do Laboratório -->
        <header class="py-12 px-8 bg-gradient-to-br from-purple-900 via-indigo-950 to-slate-900 rounded-3xl text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full mb-4 border border-white/10">
                    <i class="fas fa-flask text-purple-400 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-purple-200">Espaço Pessoal & Criativo</span>
                </div>

                <h1 class="text-3xl lg:text-5xl font-black mb-4 leading-tight">
                    Meu <span class="text-purple-400">Laboratório</span> & Hobbies
                </h1>

                <p class="text-slate-300 text-sm lg:text-base leading-relaxed font-normal mb-6">
                    Este é o meu espaço pessoal e experimental — onde exploro ideias fora do ambiente profissional. Aqui você vai encontrar projetos paralelos, descobertas, experimentos e tudo aquilo que faço por curiosidade, aprendizado e diversão.
                </p>

                <?php
                $github_personal_url = $global_settings['github_personal_url'] ?? $global_settings['github_prof_url'] ?? 'https://github.com/Gabrielrsc';
                ?>
                <a href="<?php echo htmlspecialchars($github_personal_url); ?>" target="_blank" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs px-5 py-3 rounded-xl transition shadow-md hover:shadow-purple-500/20">
                    <i class="fab fa-github text-sm"></i>
                    <span>Ver Projetos no GitHub Pessoal / Hobbies</span>
                    <i class="fas fa-external-link-alt text-[10px] opacity-70"></i>
                </a>
            </div>
        </header>

        <!-- Lista de Hobbies & Experimentos -->
        <section class="space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-2xl font-black text-slate-900 flex items-center">
                    <i class="fas fa-cubes text-purple-600 mr-3"></i> Experimentos & Artigos
                </h2>
                <span class="text-xs font-mono text-slate-400">Projetos não profissionais</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Buscar posts ou tutoriais cadastrados no banco
                $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
                $posts = $stmt->fetchAll();

                if (!empty($posts)):
                    foreach ($posts as $post):
                ?>
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden flex flex-col card-hover shadow-sm">
                    <div class="w-full h-48 bg-slate-100 relative overflow-hidden">
                        <?php if (!empty($post['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-50 to-indigo-50">
                                <i class="fas fa-flask text-purple-300 text-4xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-4 left-4">
                            <span class="bg-purple-900/90 text-purple-200 backdrop-blur-sm text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm border border-purple-700/50">
                                <?php echo htmlspecialchars($post['category'] ?? 'Hobby'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-grow justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 mb-3 leading-snug hover:text-purple-600 transition-colors">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-3 mb-6">
                                <?php echo htmlspecialchars(mb_strimwidth($post['summary'] ?? '', 0, 110, "...")); ?>
                            </p>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                            <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-widest">
                                <?php echo date("M d, Y", strtotime($post['created_at'])); ?>
                            </span>
                            <a href="post.php?slug=<?php echo $post['slug']; ?>" class="text-purple-600 text-xs font-black uppercase tracking-widest hover:text-purple-800 transition-colors flex items-center gap-1">
                                <span>Ver Projeto</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <!-- Estado vazio: nenhum projeto publicado ainda -->
                    <div class="col-span-full flex flex-col items-center justify-center py-20 px-8 bg-white rounded-3xl border border-dashed border-slate-200 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-400 text-3xl mb-6">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 mb-2">Nenhuma publicação por enquanto</h3>
                        <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                            O laboratório está sendo preparado. Em breve vou compartilhar projetos pessoais, experimentos e descobertas feitas fora do ambiente de trabalho.
                        </p>
                        <span class="mt-6 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-purple-400 bg-purple-50 px-4 py-2 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                            Em breve
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>

