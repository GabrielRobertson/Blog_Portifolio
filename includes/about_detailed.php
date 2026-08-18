<section id="about-detailed" class="py-6 sm:py-12 px-2 sm:px-4 md:px-0">
    <!-- Seção: Skills Categorizadas -->
    <div id="hard-skills" class="min-h-screen flex flex-col justify-center mb-12 sm:mb-16 mt-0">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 sm:mb-10 gap-4">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 flex items-center">
                <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-4 rounded-full"></span> Hard Skills & Competências
            </h2>
            <span class="text-xs sm:text-sm font-mono font-bold text-slate-400 uppercase tracking-widest">Matriz Técnica & Ferramentas</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <?php
            // Buscar Skills e agrupar por categoria
            $skills_stmt = $pdo->query("SELECT * FROM skills ORDER BY id ASC");
            $grouped_skills = [];
            while ($skill = $skills_stmt->fetch()) {
                $grouped_skills[$skill['category']][] = $skill;
            }
            ?>
            
            <?php if (!empty($grouped_skills)): ?>
                <?php foreach ($grouped_skills as $category => $skills): ?>
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="text-xs sm:text-sm font-black text-<?php echo $theme_color; ?>-600 mb-6 uppercase tracking-widest pb-3 border-b border-slate-100 flex items-center justify-between">
                            <span><?php echo htmlspecialchars($category); ?></span>
                            <i class="fas fa-layer-group text-slate-300 text-sm"></i>
                        </h3>

                        <div class="space-y-6">
                            <?php foreach ($skills as $skill): ?>
                            <div class="group">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 group-hover:bg-<?php echo htmlspecialchars($skill['color_class']); ?> group-hover:border-<?php echo htmlspecialchars($skill['color_class']); ?> transition-all duration-300 shadow-xs">
                                        <i class="<?php echo htmlspecialchars($skill['icon_class']); ?> text-base text-<?php echo htmlspecialchars($skill['color_class']); ?> group-hover:text-white transition-colors"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm sm:text-base font-bold text-slate-800 leading-snug group-hover:text-<?php echo $theme_color; ?>-600 transition-colors">
                                            <?php echo htmlspecialchars($skill['name']); ?>
                                        </h4>
                                        <?php if (!empty($skill['description'])): ?>
                                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-1">
                                            <?php echo htmlspecialchars($skill['description']); ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full bg-slate-50 p-8 rounded-3xl text-center border border-dashed border-slate-200">
                    <i class="fas fa-code text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 text-sm italic">Nenhuma habilidade cadastrada ainda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <!-- Seção: Formação -->
    <div id="formacao" class="min-h-screen flex flex-col justify-center mb-12 sm:mb-16">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-8 sm:mb-12 flex items-center">
            <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-4 rounded-full"></span> Formação
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            <?php
            $edu_stmt = $pdo->query("SELECT * FROM education ORDER BY order_index ASC, id DESC");
            while ($edu = $edu_stmt->fetch()):
            ?>
            <div class="flex gap-4 group bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all overflow-hidden">
                        <?php if (!empty($edu['logo_url'])): ?>
                            <img src="<?php echo htmlspecialchars($edu['logo_url']); ?>" alt="Logo <?php echo htmlspecialchars($edu['institution']); ?>" class="w-full h-full object-contain">
                        <?php else: ?>
                            <i class="fas fa-graduation-cap text-lg text-emerald-500"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm sm:text-base font-bold text-slate-800 leading-tight mb-1"><?php echo htmlspecialchars($edu['course']); ?></h4>
                    <p class="text-xs sm:text-sm font-bold text-<?php echo $theme_color; ?>-600 mb-1"><?php echo htmlspecialchars($edu['institution']); ?></p>
                    <p class="text-xs font-mono text-slate-400 uppercase tracking-widest"><?php echo htmlspecialchars($edu['year']); ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Seção: Experiência / Jornada -->
    <div id="jornada" class="min-h-screen flex flex-col justify-center">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-8 sm:mb-12 flex items-center">
            <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-4 rounded-full"></span> Jornada
        </h2>


        <div class="space-y-12">
            <?php
            // Funções de Data
            if (!function_exists('parseDate')) {
                function parseDate($dateStr) {
                    $dateStr = trim(mb_strtolower($dateStr));
                    $currentKeywords = ['atual', 'atualmente', 'presente', 'present', 'now', 'o momento', 'hoje'];
                    if (in_array($dateStr, $currentKeywords)) return new DateTime();
                    $formats = ['m/Y', 'd/m/Y', 'M/Y', 'Y', 'm-Y', 'd-m-Y'];
                    foreach ($formats as $f) {
                        $d = DateTime::createFromFormat($f, $dateStr);
                        if ($d) return $d;
                    }
                    try { return new DateTime($dateStr); } catch (Exception $e) { return new DateTime(); }
                }
            }
            if (!function_exists('formatInterval')) {
                function formatInterval($start, $end) {
                    $interval = $start->diff($end);
                    $out = [];
                    if ($interval->y > 0) $out[] = $interval->y . ($interval->y > 1 ? ' anos' : ' ano');
                    if ($interval->m > 0) $out[] = $interval->m . ($interval->m > 1 ? ' meses' : ' mês');
                    return empty($out) ? 'Menos de 1 mês' : implode(' e ', $out);
                }
            }

            // Buscar empresas
            $exp_stmt = $pdo->query("SELECT * FROM experiences WHERE parent_id IS NULL ORDER BY order_index ASC, id DESC");
            
            while ($exp = $exp_stmt->fetch()):
                $sub_stmt = $pdo->prepare("SELECT * FROM experiences WHERE parent_id = ? ORDER BY order_index ASC, id DESC");
                $sub_stmt->execute([$exp['id']]);
                $sub_roles = $sub_stmt->fetchAll();

                $all_dates = [parseDate($exp['start_date']), parseDate($exp['end_date'])];
                foreach ($sub_roles as $s) {
                    $all_dates[] = parseDate($s['start_date']);
                    $all_dates[] = parseDate($s['end_date']);
                }
                $min_date = min($all_dates);
                $max_date = max($all_dates);
                $total_time = formatInterval($min_date, $max_date);
            ?>
            
            <!-- Bloco de Uma Empresa -->
            <div class="relative">
                
                <!-- 1. Cabeçalho da Empresa -->
                <div class="flex items-center gap-4 relative z-10">
                    <!-- Logo (w-12 = 48px, centro = 24px) -->
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                        <?php if (!empty($exp['logo_url'])): ?>
                            <img src="<?php echo htmlspecialchars($exp['logo_url']); ?>" alt="Logo <?php echo htmlspecialchars($exp['company']); ?>" class="w-full h-full object-contain">
                        <?php else: ?>
                            <div class="w-full h-full bg-slate-900 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-building text-lg"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Textos da Empresa -->
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 leading-tight"><?php echo htmlspecialchars($exp['company']); ?></h3>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-xs text-<?php echo $theme_color; ?>-600 font-bold uppercase tracking-wider"><?php echo $total_time; ?></p>
                            <span class="text-slate-300 text-xs">•</span>
                            <p class="text-xs font-mono text-slate-400 uppercase tracking-widest">
                                <?php echo htmlspecialchars($exp['start_date']); ?> — <?php echo htmlspecialchars($exp['end_date']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Linha do Tempo dos Cargos -->
                <div class="ml-6 border-l-2 border-slate-200 pl-6 sm:pl-8 pt-5 pb-2 space-y-6 relative">
                    
                    <!-- Cargo Principal -->
                    <div class="relative">
                        <!-- Bolinha Colorida -->
                        <div class="absolute top-1.5 w-3 h-3 bg-<?php echo $theme_color; ?>-500 rounded-full border-2 border-white shadow-sm z-20" style="left: -31px;"></div>
                        
                        <h4 class="text-sm sm:text-base font-bold text-slate-800"><?php echo htmlspecialchars($exp['role']); ?></h4>
                        <p class="text-xs font-mono text-slate-400 mb-2 uppercase tracking-tighter">
                            <?php echo htmlspecialchars($exp['start_date']); ?> — <?php echo htmlspecialchars($exp['end_date']); ?> 
                            <span class="ml-1 text-<?php echo $theme_color; ?>-600">• <?php echo formatInterval(parseDate($exp['start_date']), parseDate($exp['end_date'])); ?></span>
                        </p>
                        <?php if (!empty($exp['description'])): ?>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                            <?php echo nl2br(htmlspecialchars($exp['description'])); ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- Sub-cargos -->
                    <?php foreach ($sub_roles as $sub): ?>
                    <div class="relative">
                        <!-- Bolinha Colorida -->
                        <div class="absolute top-1.5 w-3 h-3 bg-<?php echo $theme_color; ?>-500 rounded-full border-2 border-white shadow-sm z-20" style="left: -31px;"></div>
                        
                        <h4 class="text-sm sm:text-base font-bold text-slate-700"><?php echo htmlspecialchars($sub['role']); ?></h4>
                        <p class="text-xs font-mono text-slate-400 mb-2 uppercase tracking-tighter">
                            <?php echo htmlspecialchars($sub['start_date']); ?> — <?php echo htmlspecialchars($sub['end_date']); ?>
                            <span class="ml-1 text-slate-500">• <?php echo formatInterval(parseDate($sub['start_date']), parseDate($sub['end_date'])); ?></span>
                        </p>
                        <?php if (!empty($sub['description'])): ?>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                            <?php echo nl2br(htmlspecialchars($sub['description'])); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
