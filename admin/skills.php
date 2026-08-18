<?php 
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'list';

// Salvar / Atualizar
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $icon_class = $_POST['icon_class'] ?: 'fas fa-star';
    $color_class = $_POST['color_class'] ?: 'slate-500';
    $order_index = $_POST['order_index'] ?? 0;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE skills SET name=?, category=?, icon_class=?, color_class=?, order_index=? WHERE id=?");
        $stmt->execute([$name, $category, $icon_class, $color_class, $order_index, $id]);
        header("Location: skills.php?msg=updated");
    } else {
        $stmt = $pdo->prepare("INSERT INTO skills (name, category, icon_class, color_class, order_index) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $icon_class, $color_class, $order_index]);
        header("Location: skills.php?msg=saved");
    }
    exit;
}

// Deletar
if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: skills.php?msg=deleted");
    exit;
}

$exp = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    $exp = $stmt->fetch();
}

include 'includes/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Hard Skills</h2>
    <?php if ($action == 'list'): ?>
        <a href="skills.php?action=new" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i> Nova Skill
        </a>
    <?php endif; ?>
</div>

<!-- Mensagens de Sucesso -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] == 'deleted'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Skill excluída com sucesso!</span>
        </div>
        <a href="skills.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php elseif ($_GET['msg'] == 'saved'): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Nova Skill cadastrada com sucesso!</span>
        </div>
        <a href="skills.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php elseif ($_GET['msg'] == 'updated'): ?>
    <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span>Skill atualizada com sucesso!</span>
        </div>
        <a href="skills.php" class="text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-times"></i></a>
    </div>
    <?php endif; ?>
<?php endif; ?>


<?php if ($action == 'list'): ?>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skill</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordem</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                $stmt = $pdo->query("SELECT * FROM skills ORDER BY category ASC, order_index ASC");
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td class="px-6 py-4 font-bold text-gray-800">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php echo htmlspecialchars($row['category']); ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center bg-gray-50">
                            <i class="<?php echo htmlspecialchars($row['icon_class']); ?> text-<?php echo htmlspecialchars($row['color_class']); ?> text-lg"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php echo htmlspecialchars($row['order_index']); ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="skills.php?action=edit&id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i> Editar</a>
                        <a href="skills.php?action=delete&id=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i> Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <h3 class="text-lg font-bold mb-4"><?php echo $id ? 'Editar' : 'Nova'; ?> Skill</h3>
        
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Nome da Tecnologia</label>
                    <input type="text" name="name" id="skill_name" value="<?php echo htmlspecialchars($exp['name'] ?? ''); ?>" class="w-full p-3 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Categoria</label>
                    <select name="category" class="w-full p-3 border rounded-lg" required>
                        <option value="">Selecione...</option>
                        <option value="Programação" <?php echo (($exp['category'] ?? '') == 'Programação') ? 'selected' : ''; ?>>Programação</option>
                        <option value="Banco de Dados" <?php echo (($exp['category'] ?? '') == 'Banco de Dados') ? 'selected' : ''; ?>>Banco de Dados</option>
                        <option value="Business Intelligence" <?php echo (($exp['category'] ?? '') == 'Business Intelligence') ? 'selected' : ''; ?>>Business Intelligence</option>
                        <option value="Aplicações & Cloud" <?php echo (($exp['category'] ?? '') == 'Aplicações & Cloud') ? 'selected' : ''; ?>>Aplicações & Cloud</option>
                        <option value="Desenvolvimento Web & Software" <?php echo (($exp['category'] ?? '') == 'Desenvolvimento Web & Software') ? 'selected' : ''; ?>>Desenvolvimento Web & Software</option>
                        <option value="Outros" <?php echo (($exp['category'] ?? '') == 'Outros') ? 'selected' : ''; ?>>Outros</option>
                    </select>
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg mb-6">
                <p class="text-sm text-indigo-800 mb-3"><i class="fas fa-magic mr-2"></i><strong>Auto-Match:</strong> Digite o nome da tecnologia e tentaremos encontrar o ícone e a cor automaticamente!</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-indigo-900 mb-2">Classe do Ícone (FontAwesome)</label>
                        <input type="text" name="icon_class" id="icon_class" value="<?php echo htmlspecialchars($exp['icon_class'] ?? ''); ?>" class="w-full p-3 border border-indigo-200 rounded-lg bg-white" placeholder="ex: fab fa-php">
                        <a href="https://fontawesome.com/v6/search?o=r&m=free" target="_blank" class="text-xs text-indigo-500 mt-1 inline-block hover:underline">Procurar ícones...</a>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-indigo-900 mb-2">Cor (Tailwind Class)</label>
                        <input type="text" name="color_class" id="color_class" value="<?php echo htmlspecialchars($exp['color_class'] ?? ''); ?>" class="w-full p-3 border border-indigo-200 rounded-lg bg-white" placeholder="ex: indigo-500">
                    </div>
                </div>
                
                <div class="mt-4 flex items-center">
                    <span class="text-sm font-bold text-gray-600 mr-4">Preview:</span>
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center justify-center">
                        <i id="preview_icon" class="<?php echo htmlspecialchars($exp['icon_class'] ?? 'fas fa-star'); ?> text-<?php echo htmlspecialchars($exp['color_class'] ?? 'slate-500'); ?> text-xl transition-all"></i>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Descrição</label>
                <input type="text" name="description" id="skill_description" value="<?php echo htmlspecialchars($exp['description'] ?? ''); ?>" class="w-full p-3 border border-gray-200 rounded-lg bg-white" placeholder="ex: Desenvolvedor Web">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Ordem de Exibição</label>
                <input type="number" name="order_index" value="<?php echo htmlspecialchars($exp['order_index'] ?? '0'); ?>" class="w-full p-3 border rounded-lg w-1/3">
            </div>

            <div class="flex justify-end space-x-4">
                <a href="skills.php" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" name="save" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">Salvar Skill</button>
            </div>
        </form>
    </div>

    <!-- Script de Auto-Match -->
    <script>
        const dictionary = {
            'php': { icon: 'fab fa-php', color: 'indigo-500' },
            'js': { icon: 'fab fa-js', color: 'amber-500' },
            'javascript': { icon: 'fab fa-js', color: 'amber-500' },
            'python': { icon: 'fab fa-python', color: 'blue-500' },
            'java': { icon: 'fab fa-java', color: 'red-500' },
            'html': { icon: 'fab fa-html5', color: 'orange-500' },
            'css': { icon: 'fab fa-css3-alt', color: 'blue-400' },
            'tailwind': { icon: 'fab fa-css3-alt', color: 'cyan-400' },
            'react': { icon: 'fab fa-react', color: 'cyan-500' },
            'vue': { icon: 'fab fa-vuejs', color: 'emerald-500' },
            'angular': { icon: 'fab fa-angular', color: 'red-600' },
            'node': { icon: 'fab fa-node-js', color: 'green-600' },
            'mysql': { icon: 'fas fa-database', color: 'emerald-500' },
            'sql server': { icon: 'fas fa-server', color: 'slate-500' },
            'postgres': { icon: 'fas fa-database', color: 'blue-500' },
            'postgresql': { icon: 'fas fa-database', color: 'blue-500' },
            'docker': { icon: 'fab fa-docker', color: 'blue-500' },
            'git': { icon: 'fab fa-git-alt', color: 'orange-500' },
            'github': { icon: 'fab fa-github', color: 'slate-800' },
            'aws': { icon: 'fab fa-aws', color: 'orange-400' },
            'linux': { icon: 'fab fa-linux', color: 'slate-800' },
            'power bi': { icon: 'fas fa-chart-bar', color: 'amber-500' },
            'excel': { icon: 'fas fa-file-excel', color: 'emerald-600' },
            'figma': { icon: 'fab fa-figma', color: 'pink-500' },
            'wordpress': { icon: 'fab fa-wordpress', color: 'blue-600' },
            'laravel': { icon: 'fab fa-laravel', color: 'red-500' }
        };

        const nameInput = document.getElementById('skill_name');
        const iconInput = document.getElementById('icon_class');
        const colorInput = document.getElementById('color_class');
        const previewIcon = document.getElementById('preview_icon');

        function updatePreview() {
            previewIcon.className = `${iconInput.value} text-${colorInput.value} text-xl transition-all`;
        }

        nameInput.addEventListener('input', function() {
            const val = this.value.toLowerCase().trim();
            if (dictionary[val]) {
                iconInput.value = dictionary[val].icon;
                colorInput.value = dictionary[val].color;
                updatePreview();
            }
        });

        iconInput.addEventListener('input', updatePreview);
        colorInput.addEventListener('input', updatePreview);
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
