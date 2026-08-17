<?php 
require_once '../config/db.php';

// Ação de Excluir Projeto (Processado antes do envio de cabeçalhos)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: projects.php?msg=deleted");
    exit;
}

include 'includes/header.php'; 
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Gerenciar Portfólio</h2>
        <p class="text-xs text-slate-500">Cadastre e edite os projetos profissionais exibidos na página inicial.</p>
    </div>
    <a href="edit_project.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-3 rounded-xl transition shadow-md flex items-center gap-2 text-xs">
        <i class="fas fa-plus text-xs"></i> <span>Novo Projeto</span>
    </a>
</div>

<!-- Mensagens de Sucesso -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] == 'deleted'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Projeto excluído com sucesso! Voltando para a listagem...</span>
        </div>
        <a href="projects.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php elseif ($_GET['msg'] == 'saved'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Projeto cadastrado com sucesso!</span>
        </div>
        <a href="projects.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php elseif ($_GET['msg'] == 'updated'): ?>
    <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Projeto atualizado com sucesso!</span>
        </div>
        <a href="projects.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php endif; ?>
<?php endif; ?>


<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="p-4">Projeto</th>
                    <th class="p-4">Descrição</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Progresso</th>
                    <th class="p-4">Tecnologias</th>
                    <th class="p-4 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                <?php
                $stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
                $projects = $stmt->fetchAll();
                if (!empty($projects)):
                    foreach ($projects as $proj):
                ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-bold text-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                <i class="fas fa-folder"></i>
                            </div>
                            <span><?php echo htmlspecialchars($proj['name']); ?></span>
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 max-w-xs truncate">
                        <?php echo htmlspecialchars($proj['description'] ?? 'Sem descrição'); ?>
                    </td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo ($proj['status'] ?? '') == 'Ativo' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-600'; ?>">
                            <?php echo htmlspecialchars($proj['status'] ?? 'Ativo'); ?>
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-slate-700"><?php echo intval($proj['progress'] ?? 0); ?>%</span>
                            <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: <?php echo intval($proj['progress'] ?? 0); ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-slate-500 font-mono">
                        <?php echo htmlspecialchars($proj['tech_stack'] ?? 'Full Stack'); ?>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="edit_project.php?id=<?php echo $proj['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition" title="Editar">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <a href="projects.php?action=delete&id=<?php echo $proj['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este projeto?')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition" title="Excluir">
                            <i class="fas fa-trash text-xs"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endforeach;
                else:
                ?>
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400 italic">
                        Nenhum projeto cadastrado no momento. Clique em "Novo Projeto" para começar.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
