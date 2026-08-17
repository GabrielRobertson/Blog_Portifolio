<?php 
require_once '../config/db.php';
include 'includes/header.php'; 

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'list';

// Salvar / Atualizar
if (isset($_POST['save'])) {
    $platform = $_POST['platform'];
    $url = $_POST['url'];
    $icon_class = $_POST['icon_class'] ?: 'fas fa-link';
    $order_index = $_POST['order_index'] ?? 0;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE social_links SET platform=?, url=?, icon_class=?, order_index=? WHERE id=?");
        $stmt->execute([$platform, $url, $icon_class, $order_index, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO social_links (platform, url, icon_class, order_index) VALUES (?, ?, ?, ?)");
        $stmt->execute([$platform, $url, $icon_class, $order_index]);
    }
    header("Location: social.php");
    exit;
}

// Deletar
if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM social_links WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: social.php");
    exit;
}

$link_data = null;
if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM social_links WHERE id = ?");
    $stmt->execute([$id]);
    $link_data = $stmt->fetch();
}
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Redes Sociais</h2>
    <?php if ($action == 'list'): ?>
        <a href="social.php?action=new" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i> Adicionar Rede
        </a>
    <?php endif; ?>
</div>

<?php if ($action == 'list'): ?>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rede</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordem</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                $stmt = $pdo->query("SELECT * FROM social_links ORDER BY order_index ASC");
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td class="px-6 py-4 font-bold text-gray-800">
                        <?php echo htmlspecialchars($row['platform']); ?>
                    </td>
                    <td class="px-6 py-4 text-gray-600 truncate max-w-xs">
                        <a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" class="text-indigo-600 hover:underline">
                            <?php echo htmlspecialchars($row['url']); ?>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600">
                            <i class="<?php echo htmlspecialchars($row['icon_class']); ?> text-lg"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php echo htmlspecialchars($row['order_index']); ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="social.php?action=edit&id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i> Editar</a>
                        <a href="social.php?action=delete&id=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja remover esta rede?');"><i class="fas fa-trash"></i> Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <h3 class="text-lg font-bold mb-4"><?php echo $id ? 'Editar' : 'Nova'; ?> Rede Social</h3>
        
        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Nome da Rede (Ex: LinkedIn, GitHub, Instagram)</label>
                <input type="text" name="platform" id="platform_name" value="<?php echo htmlspecialchars($link_data['platform'] ?? ''); ?>" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">URL (Link do seu perfil)</label>
                <input type="url" name="url" value="<?php echo htmlspecialchars($link_data['url'] ?? ''); ?>" class="w-full p-3 border rounded-lg" placeholder="https://..." required>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg mb-6">
                <p class="text-sm text-indigo-800 mb-3"><i class="fas fa-magic mr-2"></i><strong>Auto-Match:</strong> O ícone preenche sozinho com base no nome!</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-indigo-900 mb-2">Classe do Ícone (FontAwesome)</label>
                        <input type="text" name="icon_class" id="icon_class" value="<?php echo htmlspecialchars($link_data['icon_class'] ?? ''); ?>" class="w-full p-3 border border-indigo-200 rounded-lg bg-white" placeholder="ex: fab fa-github">
                    </div>
                    <div class="flex items-center justify-center pt-6">
                        <span class="text-sm font-bold text-gray-600 mr-4">Preview:</span>
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center justify-center text-slate-600 hover:bg-indigo-600 hover:text-white transition-all">
                            <i id="preview_icon" class="<?php echo htmlspecialchars($link_data['icon_class'] ?? 'fas fa-link'); ?> text-xl transition-all"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Ordem de Exibição</label>
                <input type="number" name="order_index" value="<?php echo htmlspecialchars($link_data['order_index'] ?? '0'); ?>" class="w-full p-3 border rounded-lg w-1/3">
            </div>

            <div class="flex justify-end space-x-4">
                <a href="social.php" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" name="save" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">Salvar Rede</button>
            </div>
        </form>
    </div>

    <!-- Script de Auto-Match -->
    <script>
        const dictionary = {
            'github': 'fab fa-github',
            'linkedin': 'fab fa-linkedin',
            'twitter': 'fab fa-twitter',
            'x': 'fab fa-x-twitter',
            'instagram': 'fab fa-instagram',
            'facebook': 'fab fa-facebook',
            'youtube': 'fab fa-youtube',
            'twitch': 'fab fa-twitch',
            'discord': 'fab fa-discord',
            'whatsapp': 'fab fa-whatsapp',
            'telegram': 'fab fa-telegram',
            'medium': 'fab fa-medium',
            'dev.to': 'fab fa-dev',
            'dribbble': 'fab fa-dribbble',
            'behance': 'fab fa-behance',
            'stackoverflow': 'fab fa-stack-overflow',
            'gitlab': 'fab fa-gitlab',
            'bitbucket': 'fab fa-bitbucket',
            'email': 'fas fa-envelope',
            'site': 'fas fa-globe'
        };

        const nameInput = document.getElementById('platform_name');
        const iconInput = document.getElementById('icon_class');
        const previewIcon = document.getElementById('preview_icon');

        function updatePreview() {
            previewIcon.className = `${iconInput.value} text-xl transition-all`;
        }

        nameInput.addEventListener('input', function() {
            const val = this.value.toLowerCase().trim();
            // Verifica se o valor digitado corresponde exatamente a uma chave, ou se contém o nome da plataforma
            for (const key in dictionary) {
                if (val.includes(key)) {
                    iconInput.value = dictionary[key];
                    updatePreview();
                    return;
                }
            }
        });

        iconInput.addEventListener('input', updatePreview);
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
