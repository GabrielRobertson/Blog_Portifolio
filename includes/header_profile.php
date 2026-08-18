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
$freelance_on      = ($settings['freelance_available']   ?? '1') === '1';
?>
<section id="sobre-mim" class="min-h-screen py-6 sm:py-12 px-2 sm:px-4 md:px-0 flex flex-col justify-center">
    <!-- Hero / Headline Principal -->
    <div class="mb-8 sm:mb-12 py-8 sm:py-12 px-5 sm:px-8 lg:px-12 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl text-white shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-4xl space-y-4 sm:space-y-6">
            <?php if ($freelance_on): ?>
            <!-- Badge Disponibilidade Freelance -->
            <div class="inline-flex items-center gap-2 bg-emerald-500/20 backdrop-blur-md px-4 py-2 rounded-full border border-emerald-400/40 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-black uppercase tracking-wider text-emerald-300">🟢 Disponível para Projetos &amp; Freelance</span>
            </div>
            <?php endif; ?>
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-300"><?php echo htmlspecialchars($s_badge); ?></span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-5xl font-black leading-tight text-white">
                "<?php echo htmlspecialchars($s_headline); ?>"
            </h1>

            <?php if (!empty($s_subtitle)): ?>
            <p class="text-slate-300 text-sm sm:text-base lg:text-lg leading-relaxed font-normal italic border-l-2 border-indigo-400 pl-4">
                <?php echo htmlspecialchars($s_subtitle); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- História & Trajetória -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8 sm:mb-12 items-stretch">
        <!-- Card Trajetória -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 lg:p-10 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                        <i class="fas fa-industry"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-widest text-slate-400">Trajetória & Experiência Prática</span>
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-4 leading-snug">
                    <?php echo htmlspecialchars($s_traj_title); ?>
                </h2>

                <?php if (!empty($s_traj_p1)): ?>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-4">
                    <?php echo $s_traj_p1; ?>
                </p>
                <?php endif; ?>

                <?php if (!empty($s_traj_p2)): ?>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                    <?php echo $s_traj_p2; ?>
                </p>
                <?php endif; ?>
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex items-center gap-2 text-xs sm:text-sm font-mono font-bold text-slate-600">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span><?php echo htmlspecialchars($s_stat1); ?></span>
                </div>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-mono font-bold text-slate-600">
                    <i class="fas fa-check-circle text-indigo-500"></i>
                    <span><?php echo htmlspecialchars($s_stat2); ?></span>
                </div>
            </div>
        </div>

        <!-- Card Missão & Propósito -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-600 to-purple-700 p-6 sm:p-8 lg:p-10 rounded-3xl text-white shadow-xl flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white text-xl mb-6">
                    <i class="fas fa-bullseye"></i>
                </div>

                <h3 class="text-xl sm:text-2xl font-black mb-4 leading-snug">
                    Missão & Propósito
                </h3>

                <p class="text-xs sm:text-sm lg:text-base text-indigo-100 leading-relaxed mb-6">
                    <?php echo $s_mission; ?>
                </p>
            </div>

            <div class="space-y-3 pt-6 border-t border-white/10">
                <div class="flex items-center gap-3 text-xs sm:text-sm font-bold text-white">
                    <i class="fas fa-bolt text-amber-300"></i>
                    <span>Decisões Orientadas a Dados (Data-Driven)</span>
                </div>
                <div class="flex items-center gap-3 text-xs sm:text-sm font-bold text-white">
                    <i class="fas fa-cogs text-purple-200"></i>
                    <span>Eliminação de Gargalos Operacionais</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Foco de Atuação (3 Pilares Técnicos) -->
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center">
                <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-3 rounded-full"></span> Meu Foco de Atuação
            </h3>
            <span class="text-xs sm:text-sm font-mono font-bold text-slate-400 uppercase tracking-widest">Especialidades & Ecossistema</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pilar 1: Análise de Dados e BI -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm card-hover flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-800 mb-2">Análise de Dados & BI</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        Transformação de dados brutos em insights estratégicos utilizando <strong>Python</strong>, <strong>SQL</strong> e dashboards dinâmicos (<strong>Power BI</strong> e <strong>Grafana</strong>).
                    </p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-4 border-t border-slate-50">
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">Python</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">SQL</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">Power BI</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">Grafana</span>
                </div>
            </div>

            <!-- Pilar 2: Automação e RPA -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm card-hover flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-800 mb-2">Automação & RPA</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        Criação de fluxos inteligentes com <strong>Power Automate</strong>, <strong>Power Apps</strong> e scripts avançados para eliminar gargalos manuais e acelerar decisões.
                    </p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-4 border-t border-slate-50">
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">Power Automate</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">Power Apps</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">RPA</span>
                </div>
            </div>

            <!-- Pilar 3: Inovação e Cibersegurança -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm card-hover flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="text-base sm:text-lg font-bold text-slate-800 mb-2">Inovação & Cibersegurança</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        Integração de Inteligência Artificial e alinhamento com frameworks de proteção de dados no desenvolvimento de soluções corporativas seguras.
                    </p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-4 border-t border-slate-50">
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">Inteligência Artificial</span>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md">LGPD / InfoSec</span>
                </div>
            </div>
        </div>
    </div>

    <?php if ($freelance_on): ?>
    <!-- ===== SEÇÃO: SERVIÇOS FREELANCE & SOLUÇÕES SOB MEDIDA ===== -->
    <div class="mt-10 sm:mt-14">
        <!-- Cabeçalho da Seção -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black uppercase tracking-widest px-4 py-2 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Freelance &amp; Contratações
            </div>
            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3">
                Serviços Freelance &amp; <span class="text-indigo-600">Soluções Sob Medida</span>
            </h3>
            <p class="text-sm sm:text-base text-slate-500 max-w-2xl mx-auto">
                Transformando desafios operacionais e analíticos em soluções práticas e orientadas a resultado. Atuação sob contrato, consultoria ou projeto.
            </p>
        </div>

        <!-- Grid de 4 Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Card 1: Análise de Dados & BI -->
            <div class="group relative bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 right-0 w-40 h-40 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center text-2xl shadow-md">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-500 block">Pilar 01</span>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Análise de Dados &amp; Dashboards de BI</h4>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                        Desenvolvimento de dashboards interativos no <strong>Power BI</strong> ou <strong>Grafana</strong>, modelagem de dados (<strong>SQL, Python</strong>) e criação de visões estratégicas para tomadas de decisão rápidas e <em>data-driven</em>.
                    </p>
                    <div class="flex items-start gap-2 text-xs text-blue-700 bg-blue-50 rounded-xl p-3 border border-blue-100">
                        <i class="fas fa-bullseye mt-0.5 shrink-0"></i>
                        <span><strong>Para quem:</strong> Empresas ou gestores com dados espalhados (Excel, ERPs) que precisam centralizá-los em relatórios automáticos.</span>
                    </div>
                </div>
                <div class="relative z-10 flex flex-wrap gap-1.5 mt-5 pt-4 border-t border-slate-100">
                    <span class="text-xs font-mono font-bold bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md">Power BI</span>
                    <span class="text-xs font-mono font-bold bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md">Grafana</span>
                    <span class="text-xs font-mono font-bold bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md">SQL</span>
                    <span class="text-xs font-mono font-bold bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md">Python</span>
                </div>
            </div>

            <!-- Card 2: Automação & RPA -->
            <div class="group relative bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 right-0 w-40 h-40 bg-purple-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 text-white flex items-center justify-center text-2xl shadow-md">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-purple-500 block">Pilar 02</span>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Automação de Processos &amp; RPA</h4>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                        Criação de fluxos automatizados com <strong>Power Automate</strong>, <strong>Power Apps</strong> e scripts em <strong>Python</strong> para eliminar tarefas manuais repetitivas, integrações de sistemas e envio automático de relatórios.
                    </p>
                    <div class="flex items-start gap-2 text-xs text-purple-700 bg-purple-50 rounded-xl p-3 border border-purple-100">
                        <i class="fas fa-bullseye mt-0.5 shrink-0"></i>
                        <span><strong>Para quem:</strong> Negócios que perdem tempo com rotinas manuais, preenchimento repetitivo de planilhas ou processos burocráticos.</span>
                    </div>
                </div>
                <div class="relative z-10 flex flex-wrap gap-1.5 mt-5 pt-4 border-t border-slate-100">
                    <span class="text-xs font-mono font-bold bg-purple-100 text-purple-700 px-2.5 py-1 rounded-md">Power Automate</span>
                    <span class="text-xs font-mono font-bold bg-purple-100 text-purple-700 px-2.5 py-1 rounded-md">Power Apps</span>
                    <span class="text-xs font-mono font-bold bg-purple-100 text-purple-700 px-2.5 py-1 rounded-md">Python</span>
                    <span class="text-xs font-mono font-bold bg-purple-100 text-purple-700 px-2.5 py-1 rounded-md">RPA</span>
                </div>
            </div>

            <!-- Card 3: Desenvolvimento Web -->
            <div class="group relative bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center text-2xl shadow-md">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 block">Pilar 03</span>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Desenvolvimento Web &amp; Aplicações</h4>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                        Construção de sistemas web, landing pages, sites institucionais e plataformas modulares (<strong>PHP, HTML, CSS, JavaScript</strong>) com foco em arquitetura limpa, performance e SEO.
                    </p>
                    <div class="flex items-start gap-2 text-xs text-emerald-700 bg-emerald-50 rounded-xl p-3 border border-emerald-100">
                        <i class="fas fa-bullseye mt-0.5 shrink-0"></i>
                        <span><strong>Para quem:</strong> Profissionais, pequenas e médias empresas que precisam de presença digital sólida ou sistemas internos sob medida.</span>
                    </div>
                </div>
                <div class="relative z-10 flex flex-wrap gap-1.5 mt-5 pt-4 border-t border-slate-100">
                    <span class="text-xs font-mono font-bold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md">PHP</span>
                    <span class="text-xs font-mono font-bold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md">HTML/CSS</span>
                    <span class="text-xs font-mono font-bold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md">JavaScript</span>
                    <span class="text-xs font-mono font-bold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md">SEO</span>
                </div>
            </div>


        </div>
    </div>
    <?php endif; ?>

</section>

