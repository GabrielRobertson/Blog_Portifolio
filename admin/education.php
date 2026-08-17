<?php 
require_once '../config/db.php';
include 'includes/header.php'; 

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'list';

if (isset($_POST['save'])) {
    $institution = $_POST['institution'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $order_index = $_POST['order_index'] ?? 0;
    
    // Upload Logo
    $logo_url = $_POST['current_logo'] ?? null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../assets/img/logos/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $file_name = "edu_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_url = "assets/img/logos/" . $file_name;
        }
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE education SET institution=?, course=?, year=?, order_index=?, logo_url=? WHERE id=?");
        $stmt->execute([$institution, $course, $year, $order_index, $logo_url, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO education (institution, course, year, order_index, logo_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$institution, $course, $year, $order_index, $logo_url]);
    }
    header("Location: education.php");
    exit;
}

if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM education WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: education.php");
    exit;
}

$edu = ['institution'=>'','course'=>'','year'=>'','order_index'=>0, 'logo_url'=>''];
if ($id && $action == 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM education WHERE id = ?");
    $stmt->execute([$id]);
    $edu = $stmt->fetch() ?: $edu;
}
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Formação & Cursos</h2>
    <?php if ($action == 'list'): ?>
        <a href="education.php?action=add" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-plus mr-2"></i> Nova Formação</a>
    <?php endif; ?>
</div>

<?php if ($action == 'list'): ?>
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 font-semibold text-gray-600">Ordem</th>
                    <th class="p-4 font-semibold text-gray-600">Curso / Instituição</th>
                    <th class="p-4 font-semibold text-gray-600">Ano / Período</th>
                    <th class="p-4 font-semibold text-gray-600 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM education ORDER BY order_index ASC, id DESC");
                while ($row = $stmt->fetch()):
                ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 text-sm font-mono text-gray-400"><?php echo $row['order_index']; ?></td>
                    <td class="p-4">
                        <div class="flex items-center space-x-4">
                            <?php if ($row['logo_url']): ?>
                                <img src="../<?php echo $row['logo_url']; ?>" class="w-8 h-8 rounded object-contain">
                            <?php endif; ?>
                            <div>
                                <span class="font-bold"><?php echo htmlspecialchars($row['course']); ?></span> <br>
                                <span class="text-sm text-gray-500"><?php echo htmlspecialchars($row['institution']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-500"><?php echo htmlspecialchars($row['year']); ?></td>
                    <td class="p-4 text-right space-x-2">
                        <a href="education.php?action=edit&id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a>
                        <a href="education.php?action=delete&id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Excluir esta formação?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto">
        <h3 class="text-xl font-bold mb-6"><?php echo $id ? 'Editar Formação' : 'Nova Formação'; ?></h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($edu['logo_url']); ?>">

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Logotipo da Instituição</label>
                <div class="flex items-center space-x-4">
                    <?php if ($edu['logo_url']): ?>
                        <img src="../<?php echo htmlspecialchars($edu['logo_url']); ?>" class="w-16 h-16 rounded border object-contain">
                    <?php endif; ?>
                    <input type="file" name="logo" class="w-full p-2 border rounded-lg text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Curso / Certificação</label>
                <input type="text" name="course" value="<?php echo htmlspecialchars($edu['course']); ?>" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Instituição</label>
                <input type="text" name="institution" value="<?php echo htmlspecialchars($edu['institution']); ?>" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Ano ou Período</label>
                    <input type="text" name="year" value="<?php echo htmlspecialchars($edu['year']); ?>" placeholder="Ex: 2024 ou 2020 - 2024" class="w-full p-3 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Ordem (Prioridade)</label>
                    <input type="number" name="order_index" value="<?php echo htmlspecialchars($edu['order_index']); ?>" class="w-full p-3 border rounded-lg" placeholder="Ex: 1">
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="education.php" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" name="save" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">Salvar Formação</button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
