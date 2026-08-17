<?php
// Garantir que os textos do "Sobre mim" existem no array $settings
$s_headline        = $settings['about_headline']         ?? 'A verdadeira inovação nasce quando entendemos o problema no mundo real.';
$s_subtitle        = $settings['about_subtitle']         ?? '';
$s_badge           = $settings['about_badge_text']       ?? 'Engenharia de Processos • Transformação Digital';
$s_traj_title      = $settings['about_trajectory_title'] ?? 'Visão Técnica Moldada no Chão de Fábrica';
$s_traj_p1         = $settings['about_trajectory_p1']    ?? '';
$s_traj_p2         = $settings['about_trajectory_p2']    ?? '';
$s_stat1           = $settings['about_stat1']            ?? '+10 Anos de Vivência Industrial';
$s_stat2           = $settings['about_stat2']            ?? 'Graduado em ADS';
$s_mission         = $settings['about_mission_text']     ?? '';
?>
<section id="sobre-mim" class="min-h-screen py-12 px-4 md:px-0 flex flex-col justify-center">
    <!-- Hero / Headline Principal -->
    <div class="mb-12 py-12 px-8 lg:px-12 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl text-white shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-4xl space-y-6">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300"><?php echo htmlspecialchars($s_badge); ?></span>
            </div>

            <h1 class="text-3xl lg:text-5xl font-black leading-tight text-white">
                "<?php echo htmlspecialchars($s_headline); ?>"
            </h1>

            <?php if (!empty($s_subtitle)): ?>
            <p class="text-slate-300 text-sm lg:text-base leading-relaxed font-normal italic border-l-2 border-indigo-400 pl-4">
                <?php echo htmlspecialchars($s_subtitle); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- História & Trajetória -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12 items-stretch">
        <!-- Card Trajetória -->
        <div class="lg:col-span-7 bg-white p-8 lg:p-10 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-industry"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">Trajetória & Experiência Prática</span>
                </div>

                <h2 class="text-2xl font-black text-slate-900 mb-4 leading-snug">
                    <?php echo htmlspecialchars($s_traj_title); ?>
                </h2>

                <?php if (!empty($s_traj_p1)): ?>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    <?php echo $s_traj_p1; ?>
                </p>
                <?php endif; ?>

                <?php if (!empty($s_traj_p2)): ?>
                <p class="text-sm text-slate-600 leading-relaxed">
                    <?php echo $s_traj_p2; ?>
                </p>
                <?php endif; ?>
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex items-center gap-2 text-xs font-mono font-bold text-slate-500">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span><?php echo htmlspecialchars($s_stat1); ?></span>
                </div>
                <div class="flex items-center gap-2 text-xs font-mono font-bold text-slate-500">
                    <i class="fas fa-check-circle text-indigo-500"></i>
                    <span><?php echo htmlspecialchars($s_stat2); ?></span>
                </div>
            </div>
        </div>

        <!-- Card Missão & Propósito -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-600 to-purple-700 p-8 lg:p-10 rounded-3xl text-white shadow-xl flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white text-xl mb-6">
                    <i class="fas fa-bullseye"></i>
                </div>

                <h3 class="text-xl font-black mb-4 leading-snug">
                    Missão & Propósito
                </h3>

                <p class="text-xs lg:text-sm text-indigo-100 leading-relaxed mb-6">
                    <?php echo $s_mission; ?>
                </p>
            </div>

            <div class="space-y-3 pt-6 border-t border-white/10">
                <div class="flex items-center gap-3 text-xs font-bold text-white">
                    <i class="fas fa-bolt text-amber-300"></i>
                    <span>Decisões Orientadas a Dados (Data-Driven)</span>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold text-white">
                    <i class="fas fa-cogs text-purple-200"></i>
                    <span>Eliminação de Gargalos Operacionais</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Foco de Atuação (3 Pilares Técnicos) -->
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-black text-slate-900 flex items-center">
                <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-3 rounded-full"></span> Meu Foco de Atuação
            </h3>
            <span class="text-xs font-mono font-bold text-slate-400 uppercase tracking-widest">Especialidades & Ecossistema</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pilar 1: Análise de Dados e BI -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm card-hover flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-2">Análise de Dados & BI</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Transformação de dados brutos em insights estratégicos utilizando <strong>Python</strong>, <strong>SQL</strong> e dashboards dinâmicos (<strong>Power BI</strong> e <strong>Grafana</strong>).
                    </p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-4 border-t border-slate-50">
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">Python</span>
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">SQL</span>
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">Power BI</span>
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">Grafana</span>
                </div>
            </div>

            <!-- Pilar 2: Automação e RPA -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm card-hover flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-2">Automação & RPA</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Criação de fluxos inteligentes com <strong>Power Automate</strong>, <strong>Power Apps</strong> e scripts avançados para eliminar gargalos manuais e acelerar decisões.
                    </p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-4 border-t border-slate-50">
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">Power Automate</span>
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">Power Apps</span>
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">RPA</span>
                </div>
            </div>

            <!-- Pilar 3: Inovação e Cibersegurança -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm card-hover flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-2">Inovação & Cibersegurança</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Integração de Inteligência Artificial e alinhamento com frameworks de proteção de dados no desenvolvimento de soluções corporativas seguras.
                    </p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-4 border-t border-slate-50">
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">Inteligência Artificial</span>
                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md">LGPD / InfoSec</span>
                </div>
            </div>
        </div>
    </div>
</section>

