<?php
// Buscar configurações de perfil
$settings_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $settings_stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$profile_name    = $settings['profile_name'] ?? 'Gabriel Robertson';
$profile_role    = $settings['profile_role'] ?? 'Analista Técnico de Produção';
$profile_image   = $settings['profile_image'] ?? 'assets/img/gabriel-profile.png';
$cv_url          = $settings['cv_url'] ?? 'assets/curriculo.pdf';
$contact_email   = $settings['contact_email'] ?? 'contato@gabrielrobertson.com.br';
$theme_color     = $settings['theme_color'] ?? 'indigo';
$freelance_on    = ($settings['freelance_available'] ?? '1') === '1';
$avail_status    = $settings['availability_status'] ?? 'Disponível para novos projetos';
?>

<!-- CABEÇALHO SUPERIOR COMPACTO COM HAMBÚRGUER (EXIBIDO APENAS EM TELAS PEQUENAS < lg) -->
<div class="lg:hidden flex items-center justify-between p-4 bg-slate-900 text-white sticky top-0 z-50 shadow-md w-full">
    <a href="index.php" class="flex items-center gap-3 min-w-0">
        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Foto de Perfil" class="w-10 h-10 rounded-xl object-cover border-2 border-indigo-500 shrink-0">
        <div class="truncate">
            <h2 class="text-sm font-bold leading-tight truncate text-white"><?php echo htmlspecialchars($profile_name); ?></h2>
            <p class="text-[11px] text-slate-400 font-medium truncate"><?php echo htmlspecialchars($profile_role); ?></p>
        </div>
    </a>
    <button id="mobile-menu-btn" class="w-10 h-10 rounded-xl bg-slate-800 text-slate-200 hover:bg-slate-700 flex items-center justify-center text-lg transition-colors shrink-0 focus:outline-none" aria-label="Abrir Menu">
        <i id="mobile-menu-icon" class="fas fa-bars"></i>
    </button>
</div>

<!-- SIDEBAR DA PÁGINA (OCULTA NO MOBILE ATÉ O CLIQUE NO HAMBÚRGUER, VISÍVEL SEMPRE EM lg:) -->
<aside id="sidebar" class="hidden lg:flex flex-shrink-0 w-full lg:w-80 bg-slate-50 p-4 sm:p-5 lg:p-6 flex-col self-start lg:sticky lg:top-0 h-auto lg:h-screen overflow-y-auto border-b lg:border-b-0 lg:border-r border-slate-200 z-40 min-w-0">
    
    <!-- CARD PRINCIPAL DO PERFIL -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm mb-6 text-center w-full min-w-0">
        <div class="relative inline-block mb-4">
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-4 border-slate-50 shadow-md mx-auto ring-1 ring-slate-200">
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Foto de Perfil <?php echo htmlspecialchars($profile_name); ?>" class="w-full h-full object-cover object-top">
            </div>
            <!-- Indicador de disponibilidade dinâmico -->
            <?php if ($freelance_on): ?>
            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    Disponível para Freelance
                </span>
            </div>
            <?php else: ?>
            <div class="absolute -bottom-1 -right-1 bg-slate-400 w-5 h-5 rounded-full border-2 border-white shadow-sm" title="<?php echo htmlspecialchars($avail_status); ?>"></div>
            <?php endif; ?>
        </div>
        
        <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-1 leading-snug break-words px-1"><?php echo htmlspecialchars($profile_name); ?></h2>
        <p class="text-xs text-slate-500 font-medium mb-2 leading-normal break-words px-1"><?php echo htmlspecialchars($profile_role); ?></p>
        <?php if ($freelance_on): ?>
        <p class="text-[10px] text-indigo-500 font-semibold italic leading-snug px-1 mb-3">
            Transformando desafios operacionais e analíticos em soluções sob medida para o seu negócio.
        </p>
        <?php endif; ?>

        <!-- Botão Baixar Currículo -->
        <!-- Alteração Feita por mim em 18/08/2026 para remover o botão de download -->
        <!--
        <a href="<?php echo htmlspecialchars($cv_url); ?>" download target="_blank" class="flex items-center justify-center gap-2 bg-slate-900 hover:bg-<?php echo $theme_color; ?>-600 text-white text-xs font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg w-full group">
            <i class="fas fa-file-pdf text-red-400 group-hover:text-white transition-colors"></i>
            <span>Baixar Currículo</span>
            <i class="fas fa-download text-[10px] opacity-70 group-hover:translate-y-0.5 transition-transform"></i>
        </a> -->

        <!-- Links de Redes Sociais Rápidas -->
        <div class="flex justify-center items-center gap-2 mt-4 pt-3 border-t border-slate-100">
            <?php
            $is_laboratorio = (basename($_SERVER['PHP_SELF']) == 'laboratorio.php');
            $linkedin_url = $settings['linkedin_url'] ?? 'https://www.linkedin.com/in/gabrielrobertson-s/';
            $github_url = $is_laboratorio 
                ? ($settings['github_personal_url'] ?? $settings['github_prof_url'] ?? 'https://github.com/Gabrielrsc')
                : ($settings['github_prof_url'] ?? 'https://github.com/GabrielRobertson');
            $github_label = $is_laboratorio ? 'GitHub Pessoal / Experimentos' : 'GitHub Profissional';
            ?>
            <a href="<?php echo htmlspecialchars($github_url); ?>" target="_blank" title="<?php echo htmlspecialchars($github_label); ?>" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-500 rounded-lg hover:bg-<?php echo $theme_color; ?>-600 hover:text-white transition-all duration-300 text-xs">
                <i class="fab fa-github"></i>
            </a>
            <a href="<?php echo htmlspecialchars($linkedin_url); ?>" target="_blank" title="LinkedIn" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-500 rounded-lg hover:bg-<?php echo $theme_color; ?>-600 hover:text-white transition-all duration-300 text-xs">
                <i class="fab fa-linkedin"></i>
            </a>
            <?php
            // Exibir demais redes cadastradas se houver
            $social_stmt = $pdo->query("SELECT * FROM social_links WHERE LOWER(platform) NOT IN ('github', 'linkedin') ORDER BY order_index ASC");
            while ($social = $social_stmt->fetch()):
            ?>
            <a href="<?php echo htmlspecialchars($social['url']); ?>" target="_blank" title="<?php echo htmlspecialchars($social['platform']); ?>" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-500 rounded-lg hover:bg-<?php echo $theme_color; ?>-600 hover:text-white transition-all duration-300 text-xs">
                <i class="<?php echo htmlspecialchars($social['icon_class']); ?>"></i>
            </a>
            <?php endwhile; ?>
        </div>
    </div>


    <!-- MENU LATERAL DE NAVEGAÇÃO -->
    <nav class="flex flex-col flex-grow justify-between space-y-6">
        <div>
            <h3 class="text-<?php echo $theme_color; ?>-600 uppercase font-bold text-xs tracking-[0.15em] mb-3 px-2">Navegação Principal</h3>
            <ul class="space-y-1">
                <li>
                    <a href="index.php#sobre-mim" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-<?php echo $theme_color; ?>-600 hover:bg-white border border-transparent hover:border-slate-100 transition-all">
                        <i class="fas fa-user w-4 text-center text-slate-400"></i>
                        <span>Sobre Mim</span>
                    </a>
                </li>
                <li>
                    <a href="index.php#hard-skills" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-<?php echo $theme_color; ?>-600 hover:bg-white border border-transparent hover:border-slate-100 transition-all">
                        <i class="fas fa-code w-4 text-center text-slate-400"></i>
                        <span>Hard Skills</span>
                    </a>
                </li>
                <li>
                    <a href="index.php#formacao" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-<?php echo $theme_color; ?>-600 hover:bg-white border border-transparent hover:border-slate-100 transition-all">
                        <i class="fas fa-graduation-cap w-4 text-center text-slate-400"></i>
                        <span>Formação</span>
                    </a>
                </li>
                <li>
                    <a href="index.php#jornada" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-<?php echo $theme_color; ?>-600 hover:bg-white border border-transparent hover:border-slate-100 transition-all">
                        <i class="fas fa-briefcase w-4 text-center text-slate-400"></i>
                        <span>Jornada</span>
                    </a>
                </li>
                <li>
                    <a href="index.php#portfolio" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-<?php echo $theme_color; ?>-600 hover:bg-white border border-transparent hover:border-slate-100 transition-all">
                        <i class="fas fa-folder-open w-4 text-center text-slate-400"></i>
                        <span>Portfólio</span>
                    </a>
                </li>
                <li>
                    <a href="index.php#contatos" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:text-<?php echo $theme_color; ?>-600 hover:bg-white border border-transparent hover:border-slate-100 transition-all">
                        <i class="fas fa-paper-plane w-4 text-center text-slate-400"></i>
                        <span>Contatos</span>
                    </a>
                </li>
            </ul>

            <!-- DESTAQUE LABORATÓRIO -->
            <div class="mt-6 pt-4 border-t border-slate-200">
                <h3 class="text-purple-600 uppercase font-bold text-xs tracking-[0.15em] mb-3 px-2">Espaço Pessoal</h3>
                <a href="laboratorio.php" class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gradient-to-r from-purple-900 to-indigo-900 text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all hover:scale-[1.02] group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm group-hover:rotate-12 transition-transform">
                            <i class="fas fa-flask text-purple-300"></i>
                        </div>
                        <div>
                            <span class="block leading-none text-white font-bold">Laboratório</span>
                            <span class="text-[10px] sm:text-xs font-normal text-purple-200">Hobbies & Experimentos</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-purple-300 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400 font-mono">© <?php echo date("Y"); ?> <?php echo htmlspecialchars($profile_name); ?></p>
        </div>
    </nav>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const menuIcon = document.getElementById('mobile-menu-icon');
    const sidebar = document.getElementById('sidebar');

    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
            if (sidebar.classList.contains('hidden')) {
                menuIcon.className = 'fas fa-bars';
            } else {
                menuIcon.className = 'fas fa-times text-indigo-400';
            }
        });

        // Fechar menu mobile automaticamente ao clicar em qualquer link de navegação
        const navLinks = sidebar.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('hidden');
                    if (menuIcon) menuIcon.className = 'fas fa-bars';
                }
            });
        });
    }
});
</script>
