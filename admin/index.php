<?php 
require_once '../config/db.php';
include 'includes/header.php'; 

// Contagens para os cards estatísticos
$total_projects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$total_skills = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$total_education = $pdo->query("SELECT COUNT(*) FROM education")->fetchColumn();
$total_experiences = $pdo->query("SELECT COUNT(*) FROM experiences WHERE parent_id IS NULL")->fetchColumn();
$total_posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();

// Métricas de mensagens
$total_messages = 0;
$unread_messages = 0;
try {
    $total_messages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
    $unread_messages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread' OR status IS NULL")->fetchColumn();
} catch (Exception $e) {}
?>

<!-- Header de Boas-vindas e Ações Rápidas -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Visão Geral do Painel ADM</h2>
        <p class="text-xs text-slate-500">Gerencie todos os conteúdos do seu site e mensagens de contato em um só lugar.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <a href="messages.php" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fas fa-envelope text-xs"></i> <span>Ver Mensagens <?php if ($unread_messages > 0): ?>(<?php echo $unread_messages; ?>)<?php endif; ?></span>
        </a>
        <a href="edit_project.php" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> <span>Novo Projeto</span>
        </a>
        <a href="edit_post.php" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fas fa-flask text-xs"></i> <span>Novo Post Laboratório</span>
        </a>
    </div>
</div>

<!-- Grid de Métricas / Estatísticas -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
        <div class="flex justify-between items-center mb-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mensagens</span>
            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-2xl font-black text-slate-800 font-mono"><?php echo $total_messages; ?></span>
            <?php if ($unread_messages > 0): ?>
                <span class="text-[10px] font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-full"><?php echo $unread_messages; ?> novas</span>
            <?php endif; ?>
        </div>
    </div>

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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Últimas Mensagens de Contato (Destaque em 1 Coluna em TELAS GRANDES) -->
    <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-envelope text-rose-600"></i> Últimas Mensagens
            </h3>
            <a href="messages.php" class="text-xs font-bold text-rose-600 hover:text-rose-800">Ver Todas <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="divide-y divide-slate-100 text-xs flex-grow">
            <?php
            $recent_msgs = [];
            try {
                $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
                $recent_msgs = $stmt->fetchAll();
            } catch (Exception $e) {}

            if (!empty($recent_msgs)):
                foreach ($recent_msgs as $msg):
                    $is_unread = ($msg['status'] ?? 'unread') == 'unread';
            ?>
            <div class="p-4 hover:bg-slate-50 transition flex flex-col justify-between gap-2 <?php echo $is_unread ? 'bg-rose-50/40 border-l-4 border-l-rose-500' : ''; ?>">
                <div class="flex justify-between items-start">
                    <div class="min-w-0 pr-2">
                        <span class="font-bold text-slate-800 truncate block"><?php echo htmlspecialchars($msg['sender_name']); ?></span>
                        <span class="text-[10px] text-slate-400 truncate block"><?php echo htmlspecialchars($msg['subject']); ?></span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-mono flex-shrink-0"><?php echo date("d/m H:i", strtotime($msg['created_at'])); ?></span>
                </div>
                <div class="flex justify-between items-center pt-1 border-t border-slate-100/60">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php 
                        switch($msg['status'] ?? 'unread') {
                            case 'replied': echo 'bg-emerald-100 text-emerald-700'; break;
                            case 'read': echo 'bg-slate-100 text-slate-600'; break;
                            case 'archived': echo 'bg-amber-100 text-amber-700'; break;
                            default: echo 'bg-rose-100 text-rose-700 font-black'; break;
                        }
                    ?>">
                        <?php 
                            switch($msg['status'] ?? 'unread') {
                                case 'replied': echo 'Respondida'; break;
                                case 'read': echo 'Lida'; break;
                                case 'archived': echo 'Arquivada'; break;
                                default: echo 'Nova / Não Lida'; break;
                            }
                        ?>
                    </span>
                    <a href="messages.php?id=<?php echo $msg['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold text-[11px] flex items-center gap-1">
                        <span>Ver & Responder</span> <i class="fas fa-chevron-right text-[9px]"></i>
                    </a>
                </div>
            </div>
            <?php 
                endforeach;
            else:
            ?>
                <p class="p-6 text-center text-slate-400 italic">Nenhuma mensagem recebida ainda.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Projetos Recentes do Portfólio -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-folder-open text-indigo-600"></i> Projetos de Portfólio
            </h3>
            <a href="projects.php" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Ver Todos <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="divide-y divide-slate-100 text-xs flex-grow">
            <?php
            $stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC LIMIT 5");
            $projects = $stmt->fetchAll();
            if (!empty($projects)):
                foreach ($projects as $proj):
            ?>
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                <div class="min-w-0 pr-2">
                    <span class="font-bold text-slate-800 block truncate mb-0.5"><?php echo htmlspecialchars($proj['name']); ?></span>
                    <span class="text-[10px] text-slate-400 font-mono truncate block"><?php echo htmlspecialchars($proj['tech_stack'] ?? 'Full Stack'); ?></span>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-flask text-purple-600"></i> Publicações Laboratório
            </h3>
            <a href="posts.php" class="text-xs font-bold text-purple-600 hover:text-purple-800">Ver Todos <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="divide-y divide-slate-100 text-xs flex-grow">
            <?php
            $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
            $posts = $stmt->fetchAll();
            if (!empty($posts)):
                foreach ($posts as $post):
            ?>
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                <div class="min-w-0 pr-2">
                    <span class="font-bold text-slate-800 block truncate mb-0.5"><?php echo htmlspecialchars($post['title']); ?></span>
                    <span class="text-[10px] text-slate-400 font-mono"><?php echo date("d/m/Y", strtotime($post['created_at'])); ?></span>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-md truncate max-w-[80px]"><?php echo htmlspecialchars($post['category']); ?></span>
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

