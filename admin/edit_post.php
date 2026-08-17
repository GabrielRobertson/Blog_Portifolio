<?php 
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
$post = [
    'title' => '',
    'slug' => '',
    'category' => 'Tutorial',
    'summary' => '',
    'content' => '',
    'image_url' => 'assets/img/post-placeholder.png'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch() ?: $post;
}

if (isset($_POST['save'])) {
    $title = $_POST['title'];
    $slug = $_POST['slug'] ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category = $_POST['category'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];
    $image_url = $_POST['image_url'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE posts SET title=?, slug=?, category=?, summary=?, content=?, image_url=? WHERE id=?");
        $stmt->execute([$title, $slug, $category, $summary, $content, $image_url, $id]);
        header("Location: posts.php?msg=updated");
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, slug, category, summary, content, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $summary, $content, $image_url]);
        header("Location: posts.php?msg=saved");
    }
    exit;
}

include 'includes/header.php'; 
?>


<div class="bg-white rounded-xl shadow-md p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6"><?php echo $id ? 'Editar Post' : 'Novo Post'; ?></h2>
    
    <form method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Título</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" class="w-full p-3 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">URL Amigável (Slug)</label>
                <input type="text" name="slug" value="<?php echo htmlspecialchars($post['slug']); ?>" placeholder="deixe vazio para gerar automaticamente" class="w-full p-3 border rounded-lg">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Categoria</label>
                <select name="category" class="w-full p-3 border rounded-lg">
                    <option value="Tutorial" <?php echo $post['category'] == 'Tutorial' ? 'selected' : ''; ?>>Tutorial</option>
                    <option value="Projeto" <?php echo $post['category'] == 'Projeto' ? 'selected' : ''; ?>>Projeto</option>
                    <option value="Dica" <?php echo $post['category'] == 'Dica' ? 'selected' : ''; ?>>Dica</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">URL da Imagem</label>
                <input type="text" name="image_url" value="<?php echo htmlspecialchars($post['image_url']); ?>" class="w-full p-3 border rounded-lg">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">Resumo (Summary)</label>
            <textarea name="summary" rows="3" class="w-full p-3 border rounded-lg"><?php echo htmlspecialchars($post['summary']); ?></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">Conteúdo Completo</label>
            <textarea name="content" rows="10" class="w-full p-3 border rounded-lg"><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="index.php" class="px-6 py-3 border rounded-lg hover:bg-gray-50 transition">Cancelar</a>
            <button type="submit" name="save" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">Salvar Alterações</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
