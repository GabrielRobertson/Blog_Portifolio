<?php
// Buscar e-mail e dados de contato das configurações se existirem
$contact_email = $global_settings['contact_email'] ?? 'gabriel@exemplo.com';
$contact_location = $global_settings['contact_location'] ?? 'Brasil';
$contact_linkedin = $global_settings['linkedin_url'] ?? 'https://www.linkedin.com/in/gabrielrobertson-s/';
$contact_github_prof = $global_settings['github_prof_url'] ?? $global_settings['github_url'] ?? 'https://github.com/Gabrielrsc';
?>
<section id="contatos" class="min-h-screen flex flex-col justify-center py-16 px-4 md:px-0">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-4">
        <h2 class="text-3xl font-black text-slate-900 flex items-center">
            <span class="w-6 h-1 bg-<?php echo $theme_color; ?>-600 mr-4 rounded-full"></span> Vamos <span class="text-<?php echo $theme_color; ?>-600 ml-2">Conversar</span>
        </h2>
        <div class="h-px bg-slate-200 flex-grow mx-8 hidden md:block"></div>
        <span class="text-xs font-mono font-bold text-slate-400 uppercase tracking-widest">Canais & Contato</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cards de Canais Rápidos -->
        <div class="space-y-4 lg:col-span-1">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">E-mail Profissional</span>
                    <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="text-sm font-bold text-slate-800 hover:text-<?php echo $theme_color; ?>-600 transition-colors">
                        <?php echo htmlspecialchars($contact_email); ?>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fab fa-linkedin"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Rede Profissional</span>
                    <a href="<?php echo htmlspecialchars($contact_linkedin); ?>" target="_blank" class="text-sm font-bold text-slate-800 hover:text-blue-600 transition-colors flex items-center gap-1">
                        <span>Perfil no LinkedIn</span>
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fab fa-github"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Repositório Principal</span>
                    <a href="<?php echo htmlspecialchars($contact_github_prof); ?>" target="_blank" class="text-sm font-bold text-slate-800 hover:text-<?php echo $theme_color; ?>-600 transition-colors flex items-center gap-1">
                        <span>GitHub Profissional</span>
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 card-hover">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Localização</span>
                    <span class="text-sm font-bold text-slate-800">
                        <?php echo htmlspecialchars($contact_location); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulário de Mensagem -->
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-paper-plane text-<?php echo $theme_color; ?>-600 text-sm"></i> Envie uma mensagem
            </h3>
            
            <form action="#" method="POST" onsubmit="alert('Obrigado pela mensagem! Entrarei em contato em breve.'); return false;" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Seu Nome</label>
                        <input type="text" required placeholder="Ex: Maria Silva" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-<?php echo $theme_color; ?>-500/20 focus:border-<?php echo $theme_color; ?>-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Seu E-mail</label>
                        <input type="email" required placeholder="exemplo@email.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-<?php echo $theme_color; ?>-500/20 focus:border-<?php echo $theme_color; ?>-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Assunto</label>
                    <input type="text" required placeholder="Assunto da mensagem" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-<?php echo $theme_color; ?>-500/20 focus:border-<?php echo $theme_color; ?>-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Mensagem</label>
                    <textarea rows="4" required placeholder="Escreva sua mensagem aqui..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-<?php echo $theme_color; ?>-500/20 focus:border-<?php echo $theme_color; ?>-500 transition-all resize-none"></textarea>
                </div>

                <button type="submit" class="w-full sm:w-auto bg-<?php echo $theme_color; ?>-600 hover:bg-<?php echo $theme_color; ?>-700 text-white font-bold py-4 px-8 rounded-xl text-xs uppercase tracking-widest transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane text-xs"></i> Enviar Mensagem
                </button>
            </form>
        </div>
    </div>
</section>
