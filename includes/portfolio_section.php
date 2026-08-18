<?php
// Buscar projetos para o Portfólio
$portfolio_projects_stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
$portfolio_projects = $portfolio_projects_stmt->fetchAll();
?>
<section id="portfolio" class="min-h-screen flex flex-col justify-center py-8 sm:py-16 px-2 sm:px-4 md:px-0">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 sm:mb-12 gap-4">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 flex items-center">
            <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-4 rounded-full"></span> Meu <span class="text-<?php echo $theme_color; ?>-600 ml-2">Portfólio</span>
        </h2>
        <div class="h-px bg-slate-200 flex-grow mx-8 hidden md:block"></div>
        <span class="text-xs sm:text-sm font-mono font-bold text-slate-400 uppercase tracking-widest">Projetos & Aplicações</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <?php if (!empty($portfolio_projects)): ?>
            <?php foreach ($portfolio_projects as $proj): ?>
            <div class="bg-white rounded-3xl border border-slate-100 p-5 sm:p-6 flex flex-col justify-between card-hover shadow-sm group">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-<?php echo $theme_color; ?>-50 border border-<?php echo $theme_color; ?>-100 flex items-center justify-center text-<?php echo $theme_color; ?>-600 text-xl font-bold group-hover:bg-<?php echo $theme_color; ?>-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-folder font-normal"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full <?php echo ($proj['status'] ?? '') == 'Ativo' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-600'; ?>">
                            <?php echo htmlspecialchars($proj['status'] ?? 'Concluído'); ?>
                        </span>
                    </div>

                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 leading-snug group-hover:text-<?php echo $theme_color; ?>-600 transition-colors">
                        <?php echo htmlspecialchars($proj['name']); ?>
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-600 mb-6 leading-relaxed">
                        <?php echo htmlspecialchars($proj['description'] ?? 'Projeto desenvolvido com foco em alta performance, usabilidade moderna e arquitetura escalável.'); ?>
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs uppercase tracking-wider font-bold text-slate-400">Progresso</span>
                        <span class="text-xs font-mono font-bold text-<?php echo $theme_color; ?>-600"><?php echo intval($proj['progress'] ?? 100); ?>%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden mb-6">
                        <div class="bg-<?php echo $theme_color; ?>-500 h-full rounded-full transition-all duration-1000" style="width: <?php echo intval($proj['progress'] ?? 100); ?>%;"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono text-slate-400">Full Stack</span>
                        <a href="#" class="inline-flex items-center gap-2 text-xs font-black text-<?php echo $theme_color; ?>-600 hover:text-<?php echo $theme_color; ?>-700 uppercase tracking-wider">
                            <span>Ver Detalhes</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full bg-slate-50 p-8 rounded-3xl text-center border border-dashed border-slate-200">
                <i class="fas fa-folder-open text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 text-sm">Nenhum projeto cadastrado no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
