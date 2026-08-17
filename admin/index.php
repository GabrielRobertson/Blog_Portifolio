<?php 
require_once '../config/db.php';
include 'includes/header.php'; 

// Contagens para os cards estatísticos
$total_projects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$total_skills = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$total_education = $pdo->query("SELECT COUNT(*) FROM education")->fetchColumn();
$total_experiences = $pdo->query("SELECT COUNT(*) FROM experiences WHERE parent_id IS NULL")->fetchColumn();
$total_posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
?>

<!-- Header de Boas-vindas e Ações Rápidas -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Visão Geral do Painel ADM</h2>
        <p class="text-xs text-slate-500">Gerencie todos os conteúdos do seu site e portfólio profissional em um só lugar.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <a href="edit_project.php" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> <span>Novo Projeto</span>
        </a>
        <a href="edit_post.php" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fas fa-flask text-xs"></i> <span>Novo Post Laboratório</span>
        </a>
    </div>
</div>

<!-- Grid de Métricas / Estatísticas -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Portfólio</span>
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>
        <span class="text-2xl font-black text-slate-800 font-mono"><?php echo $total_projects; ?></span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Skills</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fas fa-code"></i>
            </div>
        </div>
        <span class="text-2xl font-black text-slate-800 font-mono"><?php echo $total_skills; ?></span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Formações</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        <span class="text-2xl font-black text-slate-800 font-mono"><?php echo $total_education; ?></span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jornada</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
        <span class="text-2xl font-black text-slate-800 font-mono"><?php echo $total_experiences; ?></span>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Laboratório</span>
            <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                <i class="fas fa-flask"></i>
            </div>
        </div>
        <span class="text-2xl font-black text-slate-800 font-mono"><?php echo $total_posts; ?></span>
    </div>
</div>

<!-- Seções de Gerenciamento Rápido -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Projetos Recentes do Portfólio -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-folder-open text-indigo-600"></i> Projetos de Portfólio
            </h3>
            <a href="projects.php" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Ver Todos <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            <?php
            $stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC LIMIT 5");
            $projects = $stmt->fetchAll();
            if (!empty($projects)):
                foreach ($projects as $proj):
            ?>
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                <div>
                    <span class="font-bold text-slate-800 block mb-0.5"><?php echo htmlspecialchars($proj['name']); ?></span>
                    <span class="text-[10px] text-slate-400 font-mono"><?php echo htmlspecialchars($proj['tech_stack'] ?? 'Full Stack'); ?></span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="font-mono font-bold text-indigo-600"><?php echo intval($proj['progress']); ?>%</span>
                    <a href="edit_project.php?id=<?php echo $proj['id']; ?>" class="text-slate-400 hover:text-indigo-600"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            <?php 
                endforeach;
            else:
            ?>
                <p class="p-6 text-center text-slate-400 italic">Nenhum projeto cadastrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Posts Recentes do Laboratório -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-flask text-purple-600"></i> Publicações no Laboratório
            </h3>
            <a href="posts.php" class="text-xs font-bold text-purple-600 hover:text-purple-800">Ver Todos <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            <?php
            $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
            $posts = $stmt->fetchAll();
            if (!empty($posts)):
                foreach ($posts as $post):
            ?>
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                <div>
                    <span class="font-bold text-slate-800 block mb-0.5"><?php echo htmlspecialchars($post['title']); ?></span>
                    <span class="text-[10px] text-slate-400 font-mono"><?php echo date("d/m/Y", strtotime($post['created_at'])); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-md"><?php echo htmlspecialchars($post['category']); ?></span>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="text-slate-400 hover:text-purple-600"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            <?php 
                endforeach;
            else:
            ?>
                <p class="p-6 text-center text-slate-400 italic">Nenhuma publicação encontrada.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
