<?php 
require_once '../config/db.php';
include 'includes/header.php'; 

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'list';

// Salvar / Atualizar
if (isset($_POST['save'])) {
    $company = $_POST['company'];
    $role = $_POST['role'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
    $order_index = $_POST['order_index'] ?? 0;
    
    // Upload Logo
    $logo_url = $_POST['current_logo'] ?? null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../assets/img/logos/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $file_name = "logo_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_url = "assets/img/logos/" . $file_name;
        }
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE experiences SET company=?, role=?, description=?, start_date=?, end_date=?, parent_id=?, order_index=?, logo_url=? WHERE id=?");
        $stmt->execute([$company, $role, $description, $start_date, $end_date, $parent_id, $order_index, $logo_url, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO experiences (company, role, description, start_date, end_date, parent_id, order_index, logo_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$company, $role, $description, $start_date, $end_date, $parent_id, $order_index, $logo_url]);
    }
    header("Location: experience.php");
    exit;
}

// Deletar
if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM experiences WHERE id = ? OR parent_id = ?");
    $stmt->execute([$id, $id]);
    header("Location: experience.php");
    exit;
}

// Buscar dados para edição
$exp = ['company'=>'','role'=>'','description'=>'','start_date'=>'','end_date'=>'','parent_id'=>'','order_index'=>0, 'logo_url' => ''];
if ($id && $action == 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = ?");
    $stmt->execute([$id]);
    $exp = $stmt->fetch() ?: $exp;
}
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Jornada Profissional</h2>
    <?php if ($action == 'list'): ?>
        <a href="experience.php?action=add" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-plus mr-2"></i> Nova Experiência</a>
    <?php endif; ?>
</div>

<?php if ($action == 'list'): ?>
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 font-semibold text-gray-600">Ordem</th>
                    <th class="p-4 font-semibold text-gray-600">Empresa / Cargo</th>
                    <th class="p-4 font-semibold text-gray-600">Período</th>
                    <th class="p-4 font-semibold text-gray-600 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM experiences ORDER BY parent_id ASC, order_index ASC, id DESC");
                while ($row = $stmt->fetch()):
                    $is_child = !empty($row['parent_id']);
                ?>
                <tr class="border-b hover:bg-gray-50 <?php echo $is_child ? 'bg-indigo-50/30' : ''; ?>">
                    <td class="p-4 text-sm font-mono text-gray-400"><?php echo $row['order_index']; ?></td>
                    <td class="p-4">
                        <div class="flex items-center space-x-4 <?php echo $is_child ? 'ml-6 border-l-2 border-indigo-200 pl-4' : 'font-bold'; ?>">
                            <?php if ($row['logo_url']): ?>
                                <img src="../<?php echo $row['logo_url']; ?>" class="w-8 h-8 rounded object-contain">
                            <?php endif; ?>
                            <div>
                                <?php echo htmlspecialchars($row['company']); ?> <br>
                                <span class="text-sm text-gray-500 font-normal"><?php echo htmlspecialchars($row['role']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-500"><?php echo htmlspecialchars($row['start_date']); ?> - <?php echo htmlspecialchars($row['end_date']); ?></td>
                    <td class="p-4 text-right space-x-2">
                        <a href="experience.php?action=edit&id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a>
                        <a href="experience.php?action=delete&id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Excluir esta experiência?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <!-- Formulário Add/Edit -->
    <div class="bg-white rounded-xl shadow-md p-6 max-w-3xl mx-auto">
        <h3 class="text-xl font-bold mb-6"><?php echo $id ? 'Editar Experiência' : 'Nova Experiência'; ?></h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($exp['logo_url']); ?>">
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Logotipo da Empresa</label>
                <div class="flex items-center space-x-4">
                    <?php if ($exp['logo_url']): ?>
                        <img src="../<?php echo htmlspecialchars($exp['logo_url']); ?>" class="w-16 h-16 rounded border object-contain">
                    <?php endif; ?>
                    <input type="file" name="logo" class="w-full p-2 border rounded-lg text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Empresa</label>
                    <input type="text" name="company" value="<?php echo htmlspecialchars($exp['company']); ?>" class="w-full p-3 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Cargo</label>
                    <input type="text" name="role" value="<?php echo htmlspecialchars($exp['role']); ?>" class="w-full p-3 border rounded-lg" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Data Início</label>
                    <input type="text" name="start_date" value="<?php echo htmlspecialchars($exp['start_date']); ?>" placeholder="Ex: Jan/2021" class="w-full p-3 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Data Fim</label>
                    <input type="text" name="end_date" value="<?php echo htmlspecialchars($exp['end_date']); ?>" placeholder="Ex: Atualmente" class="w-full p-3 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Ordem (Prioridade)</label>
                    <input type="number" name="order_index" value="<?php echo htmlspecialchars($exp['order_index']); ?>" class="w-full p-3 border rounded-lg" placeholder="Ex: 1">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Vincular como Evolução de:</label>
                <select name="parent_id" class="w-full p-3 border rounded-lg">
                    <option value="">-- Nenhuma (Experiência Principal) --</option>
                    <?php
                    $parents = $pdo->query("SELECT id, company, role FROM experiences WHERE parent_id IS NULL ORDER BY company ASC");
                    while($p = $parents->fetch()){
                        if ($p['id'] == $id) continue;
                        $selected = ($exp['parent_id'] == $p['id']) ? 'selected' : '';
                        echo "<option value='{$p['id']}' $selected>{$p['company']} - {$p['role']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Descrição das Atividades</label>
                <textarea name="description" rows="5" class="w-full p-3 border rounded-lg"><?php echo htmlspecialchars($exp['description']); ?></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="experience.php" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" name="save" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">Salvar Experiência</button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
