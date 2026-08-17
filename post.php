<?php 
require_once 'config/db.php'; 

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ?");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: index.php");
    exit;
}

$pageTitle = $post['title'];
include 'includes/head.php'; 
include 'includes/sidebar.php'; 
?>

<div id="main-content" class="flex-grow p-8 flex flex-col">
    <header class="bg-card rounded-2xl p-6 mb-6 shadow-md border border-border-light">
        <a href="index.php" class="text-primary-purple hover:underline mb-4 inline-block"><i class="fas fa-arrow-left mr-2"></i> Voltar ao Blog</a>
        <h1 class="text-3xl font-bold text-text-dark mb-2"><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="flex items-center text-sm text-text-light space-x-4">
            <span class="bg-green-accent text-text-dark px-2 py-1 rounded-md font-semibold"><?php echo htmlspecialchars($post['category']); ?></span>
            <span class="font-mono"><?php echo date("d/m/Y", strtotime($post['created_at'])); ?></span>
        </div>
    </header>

    <article class="bg-card rounded-2xl p-8 mb-6 shadow-md border border-border-light prose max-w-none">
        <?php if ($post['image_url']): ?>
            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-64 object-cover rounded-xl mb-8">
        <?php endif; ?>
        
        <div class="text-text-light leading-relaxed">
            <?php echo nl2br(htmlspecialchars($post['content'] ?: $post['summary'])); ?>
        </div>
    </article>
    
<?php include 'includes/footer.php'; ?>
