<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/db.php';

// Garantir que a tabela de histórico de mensagens existe no banco de dados com estrutura completa
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `sender_name` VARCHAR(255) NOT NULL,
        `sender_email` VARCHAR(255) NOT NULL,
        `phone` VARCHAR(45) NULL,
        `subject` VARCHAR(255) NOT NULL,
        `message` TEXT NOT NULL,
        `status` VARCHAR(20) DEFAULT 'unread',
        `notes` TEXT NULL,
        `ip_address` VARCHAR(45) NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Adicionar colunas se não existirem (fallback dinâmico)
    $cols = [
        'phone' => "ALTER TABLE contact_messages ADD COLUMN phone VARCHAR(45) NULL",
        'status' => "ALTER TABLE contact_messages ADD COLUMN status VARCHAR(20) DEFAULT 'unread'",
        'notes' => "ALTER TABLE contact_messages ADD COLUMN notes TEXT NULL"
    ];
    foreach ($cols as $colSql) {
        try { $pdo->exec($colSql); } catch (Exception $ex) {}
    }
} catch (Exception $e) {
    // Silencioso se a tabela já existir ou sem permissão de DDL
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
    exit;
}

// Honeypot Anti-Spam (se preenchido, é um bot spammer)
if (!empty($_POST['website_hp'])) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
    exit;
}

// Coleta e sanitização de dados
$name    = trim(strip_tags($_POST['name'] ?? ''));
$email   = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$phone   = trim(strip_tags($_POST['phone'] ?? ''));
$subject = trim(strip_tags($_POST['subject'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Validações
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios do formulário.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, insira um e-mail válido.']);
    exit;
}

// Prevenir Header Injection
if (preg_match("/[\r\n]/", $name) || preg_match("/[\r\n]/", $email) || preg_match("/[\r\n]/", $subject)) {
    echo json_encode(['success' => false, 'message' => 'Entrada inválida detectada.']);
    exit;
}

$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido';

// Salvar no Banco de Dados para gerenciamento no Painel ADM
try {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (sender_name, sender_email, phone, subject, message, status, ip_address) VALUES (?, ?, ?, ?, ?, 'unread', ?)");
    $stmt->execute([$name, $email, $phone, $subject, $message, $ip_address]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Obrigado! Sua mensagem foi enviada com sucesso. Entrarei em contato em breve.'
    ]);
} catch (Exception $e) {
    error_log("Erro ao salvar mensagem no BD: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ocorreu um erro ao salvar sua mensagem. Por favor, tente novamente.'
    ]);
}

