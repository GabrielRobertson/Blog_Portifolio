<?php 
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
$project = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
}

if (isset($_POST['save'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $progress = intval($_POST['progress']);
    $status = $_POST['status'];
    $tech_stack = trim($_POST['tech_stack']);
    $project_url = trim($_POST['project_url']);

    if ($id && $project) {
        // Atualização de projeto existente
        $stmt = $pdo->prepare("UPDATE projects SET name=?, description=?, progress=?, status=?, tech_stack=?, project_url=? WHERE id=?");
        $stmt->execute([$name, $description, $progress, $status, $tech_stack, $project_url, $id]);
        header("Location: projects.php?msg=updated");
    } else {
        // Inserção de novo projeto
        $stmt = $pdo->prepare("INSERT INTO projects (name, description, progress, status, tech_stack, project_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $progress, $status, $tech_stack, $project_url]);
        header("Location: projects.php?msg=saved");
    }
    exit;
}

include 'includes/header.php'; 
?>


<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
            <i class="fas fa-folder-plus"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800"><?php echo $id ? 'Editar Projeto' : 'Novo Projeto de Portfólio'; ?></h2>
            <p class="text-xs text-slate-500">Preencha os detalhes do projeto que será exibido no portfólio.</p>
        </div>
    </div>
    
    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nome do Projeto *</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($project['name'] ?? ''); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required placeholder="Ex: Sistema de Gestão Industrial">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Descrição do Projeto</label>
            <textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none" placeholder="Descreva os principais objetivos, desafios resolvidos e funcionalidades do projeto..."><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Progresso (%) *</label>
                <input type="number" name="progress" min="0" max="100" value="<?php echo intval($project['progress'] ?? 100); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="Ativo" <?php echo ($project['status'] ?? '') == 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                    <option value="Concluído" <?php echo ($project['status'] ?? '') == 'Concluído' ? 'selected' : ''; ?>>Concluído</option>
                    <option value="Pausado" <?php echo ($project['status'] ?? '') == 'Pausado' ? 'selected' : ''; ?>>Pausado</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tecnologias Utilizadas</label>
                <input type="text" name="tech_stack" value="<?php echo htmlspecialchars($project['tech_stack'] ?? ''); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" placeholder="Ex: PHP, Python, Power BI, SQL">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Link do Projeto / GitHub</label>
                <input type="url" name="project_url" value="<?php echo htmlspecialchars($project['project_url'] ?? ''); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" placeholder="https://github.com/usuario/projeto">
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="projects.php" class="px-6 py-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Cancelar</a>
            <button type="submit" name="save" class="px-6 py-3 bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-indigo-700 transition shadow-md">
                Salvar Projeto
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
