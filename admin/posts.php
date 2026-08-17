<?php 
require_once '../config/db.php';

// Ação de Excluir Post (Processado antes do envio de cabeçalhos)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: posts.php?msg=deleted");
    exit;
}

include 'includes/header.php'; 
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Gerenciar Laboratório & Hobbies</h2>
        <p class="text-xs text-slate-500">Cadastre e edite artigos, tutoriais, experimentos e projetos não profissionais.</p>
    </div>
    <a href="edit_post.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-3 rounded-xl transition shadow-md flex items-center gap-2 text-xs">
        <i class="fas fa-plus text-xs"></i> <span>Novo Post</span>
    </a>
</div>

<!-- Mensagens de Sucesso -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] == 'deleted'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Publicação excluída com sucesso! Voltando para a listagem...</span>
        </div>
        <a href="posts.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php elseif ($_GET['msg'] == 'saved'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Publicação cadastrada com sucesso!</span>
        </div>
        <a href="posts.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php elseif ($_GET['msg'] == 'updated'): ?>
    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 text-purple-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Publicação atualizada com sucesso!</span>
        </div>
        <a href="posts.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php endif; ?>
<?php endif; ?>


<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="p-4">Título</th>
                    <th class="p-4">Categoria</th>
                    <th class="p-4">Resumo</th>
                    <th class="p-4">Data</th>
                    <th class="p-4 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                <?php
                $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
                $posts = $stmt->fetchAll();
                if (!empty($posts)):
                    foreach ($posts as $post):
                ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-bold text-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                <i class="fas fa-flask"></i>
                            </div>
                            <span><?php echo htmlspecialchars($post['title']); ?></span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            <?php echo htmlspecialchars($post['category']); ?>
                        </span>
                    </td>
                    <td class="p-4 text-slate-500 max-w-xs truncate">
                        <?php echo htmlspecialchars($post['summary'] ?? ''); ?>
                    </td>
                    <td class="p-4 text-slate-400 font-mono">
                        <?php echo date("d/m/Y", strtotime($post['created_at'])); ?>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white transition" title="Editar">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <a href="posts.php?action=delete&id=<?php echo $post['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este post?')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition" title="Excluir">
                            <i class="fas fa-trash text-xs"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endforeach;
                else:
                ?>
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400 italic">
                        Nenhuma publicação cadastrada no momento. Clique em "Novo Post" para criar.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
