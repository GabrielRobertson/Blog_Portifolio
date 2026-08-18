<?php
require_once '../config/db.php';

// Processamento de Ações do Administrador
$msg_notification = '';
$msg_type = 'success';

// 1. Excluir Mensagem
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: messages.php?msg=deleted");
    exit;
}

// 2. Alterar Status da Mensagem
if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $msg_id = intval($_GET['id']);
    $new_status = $_GET['status'];
    $allowed_statuses = ['unread', 'read', 'replied', 'archived'];
    if (in_array($new_status, $allowed_statuses)) {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $msg_id]);
        header("Location: messages.php?msg=status_updated");
        exit;
    }
}

// 3. Marcar Todas como Lidas
if (isset($_GET['action']) && $_GET['action'] === 'mark_all_read') {
    $pdo->exec("UPDATE contact_messages SET status = 'read' WHERE status = 'unread' OR status IS NULL");
    header("Location: messages.php?msg=all_read");
    exit;
}

// 4. Salvar Anotações Internas do Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_notes') {
    $msg_id = intval($_POST['msg_id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');
    if ($msg_id > 0) {
        $stmt = $pdo->prepare("UPDATE contact_messages SET notes = ? WHERE id = ?");
        $stmt->execute([$notes, $msg_id]);
        header("Location: messages.php?msg=notes_saved&view_id=" . $msg_id);
        exit;
    }
}

include 'includes/header.php';

// Filtros e Busca
$filter_status = $_GET['status'] ?? 'all';
$search_query = trim($_GET['q'] ?? '');

// Montar SQL Dinâmica
$sql = "SELECT * FROM contact_messages WHERE 1=1";
$params = [];

if ($filter_status !== 'all') {
    if ($filter_status === 'unread') {
        $sql .= " AND (status = 'unread' OR status IS NULL)";
    } else {
        $sql .= " AND status = ?";
        $params[] = $filter_status;
    }
}

if (!empty($search_query)) {
    $sql .= " AND (sender_name LIKE ? OR sender_email LIKE ? OR phone LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $term = "%{$search_query}%";
    $params = array_merge($params, [$term, $term, $term, $term, $term]);
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

// Métricas de Contagem Globais
$total_all = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$total_unread = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread' OR status IS NULL")->fetchColumn();
$total_read = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'read'")->fetchColumn();
$total_replied = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'replied'")->fetchColumn();
$total_archived = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'archived'")->fetchColumn();

// Se um ID específico foi solicitado para visualizar, marcar como lida e buscar dados
$active_view_msg = null;
$view_id = intval($_GET['view_id'] ?? $_GET['id'] ?? 0);
if ($view_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$view_id]);
    $active_view_msg = $stmt->fetch();
    
    // Auto marcar como lida se estivesse como unread
    if ($active_view_msg && ($active_view_msg['status'] === 'unread' || empty($active_view_msg['status']))) {
        $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$view_id]);
        $active_view_msg['status'] = 'read';
    }
}

// Função Auxiliar para formatar WhatsApp
function formatWhatsAppUrl($phone, $name, $subject) {
    if (empty($phone)) return null;
    $clean = preg_replace('/[^0-9]/', '', $phone);
    if (empty($clean)) return null;
    // Se não tiver DDD/País, adicione o prefixo do Brasil (55)
    if (strlen($clean) === 10 || strlen($clean) === 11) {
        $clean = '55' . $clean;
    }
    $text = rawurlencode("Olá " . $name . ", vi sua mensagem enviada pelo meu site sobre: \"" . $subject . "\". Como posso te ajudar?");
    return "https://wa.me/" . $clean . "?text=" . $text;
}

function formatMailtoUrl($email, $name, $subject) {
    $sub = rawurlencode("Re: " . $subject);
    $body = rawurlencode("Olá " . $name . ",\n\nObrigado pelo contato através do meu portfólio!\n\nEm resposta à sua mensagem:\n\n---\n\nAtenciosamente,\nGabriel Robertson");
    return "mailto:" . htmlspecialchars($email) . "?subject=" . $sub . "&body=" . $body;
}
?>

<!-- Header Superior -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fas fa-envelope text-rose-600"></i> Gerenciamento de Mensagens
        </h2>
        <p class="text-xs text-slate-500">Gerencie as mensagens recebidas no formulário de contato e entre em contato diretamente com os visitantes.</p>
    </div>
    <div class="flex items-center gap-2">
        <?php if ($total_unread > 0): ?>
            <a href="messages.php?action=mark_all_read" onclick="return confirm('Deseja marcar todas as mensagens não lidas como lidas?')" class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
                <i class="fas fa-check-double text-xs"></i> <span>Marcar Todas como Lidas</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Alertas de Sucesso -->
<?php if (isset($_GET['msg'])): ?>
    <div class="mb-6 p-4 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm animate-fade-in <?php 
        echo $_GET['msg'] === 'deleted' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-emerald-50 border border-emerald-200 text-emerald-700'; 
    ?>">
        <div class="flex items-center gap-2">
            <i class="fas <?php echo $_GET['msg'] === 'deleted' ? 'fa-trash-alt' : 'fa-check-circle'; ?> text-base"></i>
            <span>
                <?php 
                    switch($_GET['msg']) {
                        case 'deleted': echo 'Mensagem excluída com sucesso!'; break;
                        case 'status_updated': echo 'Status da mensagem atualizado!'; break;
                        case 'all_read': echo 'Todas as mensagens foram marcadas como lidas!'; break;
                        case 'notes_saved': echo 'Anotações internas salvas com sucesso!'; break;
                        default: echo 'Ação concluída com sucesso!'; break;
                    }
                ?>
            </span>
        </div>
        <a href="messages.php" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></a>
    </div>
<?php endif; ?>

<!-- Grid de Métricas / Contadores -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <a href="messages.php?status=all" class="p-4 rounded-2xl border transition shadow-sm flex items-center justify-between <?php echo $filter_status === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'; ?>">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider block opacity-80">Todas</span>
            <span class="text-2xl font-black font-mono"><?php echo $total_all; ?></span>
        </div>
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm <?php echo $filter_status === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'; ?>">
            <i class="fas fa-inbox"></i>
        </div>
    </a>

    <a href="messages.php?status=unread" class="p-4 rounded-2xl border transition shadow-sm flex items-center justify-between <?php echo $filter_status === 'unread' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'; ?>">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider block opacity-80">Não Lidas</span>
            <span class="text-2xl font-black font-mono"><?php echo $total_unread; ?></span>
        </div>
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm <?php echo $filter_status === 'unread' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-600'; ?>">
            <i class="fas fa-envelope text-xs"></i>
        </div>
    </a>

    <a href="messages.php?status=replied" class="p-4 rounded-2xl border transition shadow-sm flex items-center justify-between <?php echo $filter_status === 'replied' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'; ?>">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider block opacity-80">Respondidas</span>
            <span class="text-2xl font-black font-mono"><?php echo $total_replied; ?></span>
        </div>
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm <?php echo $filter_status === 'replied' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600'; ?>">
            <i class="fas fa-reply"></i>
        </div>
    </a>

    <a href="messages.php?status=archived" class="p-4 rounded-2xl border transition shadow-sm flex items-center justify-between <?php echo $filter_status === 'archived' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'; ?>">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider block opacity-80">Arquivadas</span>
            <span class="text-2xl font-black font-mono"><?php echo $total_archived; ?></span>
        </div>
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm <?php echo $filter_status === 'archived' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-600'; ?>">
            <i class="fas fa-archive"></i>
        </div>
    </a>
</div>

<!-- Filtros e Barra de Pesquisa -->
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <!-- Abas Rápidas -->
    <div class="flex flex-wrap items-center gap-1 text-xs font-semibold w-full md:w-auto">
        <a href="messages.php?status=all<?php echo !empty($search_query) ? '&q='.urlencode($search_query) : ''; ?>" class="px-3 py-1.5 rounded-lg transition <?php echo $filter_status === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
            Todas (<?php echo $total_all; ?>)
        </a>
        <a href="messages.php?status=unread<?php echo !empty($search_query) ? '&q='.urlencode($search_query) : ''; ?>" class="px-3 py-1.5 rounded-lg transition <?php echo $filter_status === 'unread' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
            Não Lidas (<?php echo $total_unread; ?>)
        </a>
        <a href="messages.php?status=read<?php echo !empty($search_query) ? '&q='.urlencode($search_query) : ''; ?>" class="px-3 py-1.5 rounded-lg transition <?php echo $filter_status === 'read' ? 'bg-slate-800 text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
            Lidas (<?php echo $total_read; ?>)
        </a>
        <a href="messages.php?status=replied<?php echo !empty($search_query) ? '&q='.urlencode($search_query) : ''; ?>" class="px-3 py-1.5 rounded-lg transition <?php echo $filter_status === 'replied' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
            Respondidas (<?php echo $total_replied; ?>)
        </a>
        <a href="messages.php?status=archived<?php echo !empty($search_query) ? '&q='.urlencode($search_query) : ''; ?>" class="px-3 py-1.5 rounded-lg transition <?php echo $filter_status === 'archived' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
            Arquivadas (<?php echo $total_archived; ?>)
        </a>
    </div>

    <!-- Campo de Busca -->
    <form method="GET" action="messages.php" class="w-full md:w-72 flex items-center gap-2">
        <?php if ($filter_status !== 'all'): ?>
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($filter_status); ?>">
        <?php endif; ?>
        <div class="relative w-full">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Buscar mensagem ou remetente..." class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
            <?php if (!empty($search_query)): ?>
                <a href="messages.php?status=<?php echo $filter_status; ?>" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                    <i class="fas fa-times-circle"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Layout Principal: Tabela de Mensagens & Drawer/Modal de Leitura se selecionado -->
<div class="grid grid-cols-1 <?php echo $active_view_msg ? 'lg:grid-cols-3' : ''; ?> gap-8">
    
    <!-- Tabela / Lista de Mensagens (Ocupa 2 colunas se houver mensagem aberta) -->
    <div class="<?php echo $active_view_msg ? 'lg:col-span-2' : 'col-span-1'; ?> bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <span class="text-xs font-bold text-slate-700 flex items-center gap-2">
                <i class="fas fa-list text-slate-400"></i> Listagem de Mensagens (<?php echo count($messages); ?>)
            </span>
            <?php if (!empty($search_query)): ?>
                <span class="text-[10px] text-slate-500 bg-slate-200 px-2 py-0.5 rounded-md font-mono">
                    Busca: "<?php echo htmlspecialchars($search_query); ?>"
                </span>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="p-3">Status</th>
                        <th class="p-3">Remetente</th>
                        <th class="p-3">Assunto</th>
                        <th class="p-3">Data</th>
                        <th class="p-3 text-right">Ações de Contato</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $msg): 
                            $is_active = $active_view_msg && $active_view_msg['id'] == $msg['id'];
                            $is_unread = ($msg['status'] ?? 'unread') === 'unread';
                            $wa_url = formatWhatsAppUrl($msg['phone'] ?? '', $msg['sender_name'], $msg['subject']);
                            $mail_url = formatMailtoUrl($msg['sender_email'], $msg['sender_name'], $msg['subject']);
                        ?>
                        <tr class="transition-colors <?php echo $is_active ? 'bg-indigo-50/60' : ($is_unread ? 'bg-rose-50/30 font-semibold' : 'hover:bg-slate-50'); ?>">
                            <!-- Status Badge -->
                            <td class="p-3 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1.5 <?php 
                                    switch($msg['status'] ?? 'unread') {
                                        case 'replied': echo 'bg-emerald-100 text-emerald-700 border border-emerald-200'; break;
                                        case 'read': echo 'bg-slate-100 text-slate-600 border border-slate-200'; break;
                                        case 'archived': echo 'bg-amber-100 text-amber-700 border border-amber-200'; break;
                                        default: echo 'bg-rose-500 text-white font-black animate-pulse shadow-sm'; break;
                                    }
                                ?>">
                                    <i class="fas <?php 
                                        switch($msg['status'] ?? 'unread') {
                                            case 'replied': echo 'fa-check'; break;
                                            case 'read': echo 'fa-envelope-open'; break;
                                            case 'archived': echo 'fa-archive'; break;
                                            default: echo 'fa-envelope'; break;
                                        }
                                    ?> text-[9px]"></i>
                                    <span><?php 
                                        switch($msg['status'] ?? 'unread') {
                                            case 'replied': echo 'Respondida'; break;
                                            case 'read': echo 'Lida'; break;
                                            case 'archived': echo 'Arquivada'; break;
                                            default: echo 'Não Lida'; break;
                                        }
                                    ?></span>
                                </span>
                            </td>

                            <!-- Remetente -->
                            <td class="p-3">
                                <div>
                                    <span class="font-bold text-slate-800 block truncate max-w-[160px]" title="<?php echo htmlspecialchars($msg['sender_name']); ?>">
                                        <?php echo htmlspecialchars($msg['sender_name']); ?>
                                    </span>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-[160px]" title="<?php echo htmlspecialchars($msg['sender_email']); ?>">
                                        <?php echo htmlspecialchars($msg['sender_email']); ?>
                                    </span>
                                    <?php if (!empty($msg['phone'])): ?>
                                        <span class="text-[10px] text-emerald-600 font-mono flex items-center gap-1 mt-0.5">
                                            <i class="fab fa-whatsapp text-[10px]"></i> <?php echo htmlspecialchars($msg['phone']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Assunto & Preview -->
                            <td class="p-3">
                                <div>
                                    <a href="messages.php?view_id=<?php echo $msg['id']; ?><?php echo !empty($filter_status) ? '&status='.$filter_status : ''; ?>" class="font-bold text-slate-800 hover:text-indigo-600 transition block truncate max-w-[200px]" title="<?php echo htmlspecialchars($msg['subject']); ?>">
                                        <?php echo htmlspecialchars($msg['subject']); ?>
                                    </a>
                                    <p class="text-[11px] text-slate-500 truncate max-w-[200px]">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </p>
                                </div>
                            </td>

                            <!-- Data -->
                            <td class="p-3 text-[10px] font-mono text-slate-400 whitespace-nowrap">
                                <?php echo date("d/m/Y", strtotime($msg['created_at'])); ?><br>
                                <span class="text-slate-300"><?php echo date("H:i", strtotime($msg['created_at'])); ?></span>
                            </td>

                            <!-- Ações Rápidas de Contato -->
                            <td class="p-3 text-right whitespace-nowrap space-x-1">
                                <!-- Botão de Abrir / Ler -->
                                <a href="messages.php?view_id=<?php echo $msg['id']; ?><?php echo !empty($filter_status) ? '&status='.$filter_status : ''; ?>" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition" title="Ler Mensagem Completa">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                <!-- Entrar em Contato: E-mail -->
                                <a href="<?php echo $mail_url; ?>" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Responder por E-mail">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                </a>

                                <!-- Entrar em Contato: WhatsApp -->
                                <?php if ($wa_url): ?>
                                    <a href="<?php echo $wa_url; ?>" target="_blank" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition" title="Iniciar Conversa no WhatsApp">
                                        <i class="fab fa-whatsapp text-xs"></i>
                                    </a>
                                <?php endif; ?>

                                <!-- Excluir -->
                                <a href="messages.php?action=delete&id=<?php echo $msg['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta mensagem?')" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition" title="Excluir Mensagem">
                                    <i class="fas fa-trash text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 italic">
                                <i class="fas fa-inbox text-3xl block mb-2 opacity-40"></i>
                                Nenhuma mensagem encontrada com os filtros selecionados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAINEL LATERAL: Detalhes da Mensagem Selecionada & Ações de Contato -->
    <?php if ($active_view_msg): 
        $v_msg = $active_view_msg;
        $v_wa_url = formatWhatsAppUrl($v_msg['phone'] ?? '', $v_msg['sender_name'], $v_msg['subject']);
        $v_mail_url = formatMailtoUrl($v_msg['sender_email'], $v_msg['sender_name'], $v_msg['subject']);
    ?>
    <div class="col-span-1 bg-white rounded-2xl shadow-md border border-indigo-100 overflow-hidden flex flex-col">
        <!-- Header do Card de Detalhes -->
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-indigo-400"></i>
                <h3 class="text-xs font-bold">Detalhes da Mensagem #<?php echo $v_msg['id']; ?></h3>
            </div>
            <a href="messages.php?status=<?php echo $filter_status; ?>" class="text-slate-400 hover:text-white text-xs" title="Fechar Detalhes">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <div class="p-5 flex-grow space-y-5 text-xs">
            <!-- Dados do Remetente -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Remetente</span>
                        <h4 class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($v_msg['sender_name']); ?></h4>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400 bg-white px-2 py-1 rounded-md border border-slate-200">
                        <?php echo date("d/m/Y H:i", strtotime($v_msg['created_at'])); ?>
                    </span>
                </div>

                <div class="pt-2 border-t border-slate-200/60 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">E-mail:</span>
                        <a href="mailto:<?php echo htmlspecialchars($v_msg['sender_email']); ?>" class="font-mono font-bold text-indigo-600 hover:underline">
                            <?php echo htmlspecialchars($v_msg['sender_email']); ?>
                        </a>
                    </div>
                    <?php if (!empty($v_msg['phone'])): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Telefone/WhatsApp:</span>
                        <span class="font-mono font-bold text-emerald-600">
                            <?php echo htmlspecialchars($v_msg['phone']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Endereço IP:</span>
                        <span class="font-mono text-slate-400"><?php echo htmlspecialchars($v_msg['ip_address'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- BOTÕES PRINCIPAIS DE AÇÃO: ENTRAR EM CONTATO -->
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Entrar em Contato Agora</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <a href="<?php echo $v_mail_url; ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2 text-xs">
                        <i class="fas fa-paper-plane"></i> <span>Via E-mail</span>
                    </a>

                    <?php if ($v_wa_url): ?>
                        <a href="<?php echo $v_wa_url; ?>" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-3 rounded-xl transition shadow-sm flex items-center justify-center gap-2 text-xs">
                            <i class="fab fa-whatsapp text-sm"></i> <span>Via WhatsApp</span>
                        </a>
                    <?php else: ?>
                        <button onclick="alert('O remetente não informou o número de telefone no formulário.')" class="bg-slate-100 text-slate-400 font-bold py-2.5 px-3 rounded-xl cursor-not-allowed flex items-center justify-center gap-2 text-xs">
                            <i class="fab fa-whatsapp text-sm"></i> <span>Sem Telefone</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Assunto e Conteúdo da Mensagem -->
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Assunto</span>
                <p class="font-bold text-slate-800 text-sm mb-3 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                    <?php echo htmlspecialchars($v_msg['subject']); ?>
                </p>

                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Conteúdo da Mensagem</span>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-slate-700 leading-relaxed whitespace-pre-wrap font-sans text-xs max-h-60 overflow-y-auto">
                    <?php echo htmlspecialchars($v_msg['message']); ?>
                </div>
            </div>

            <!-- Alterar Status da Mensagem -->
            <div class="pt-2 border-t border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Alterar Status</span>
                <div class="flex flex-wrap gap-1.5">
                    <a href="messages.php?action=update_status&id=<?php echo $v_msg['id']; ?>&status=unread" class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition border <?php echo ($v_msg['status'] ?? '') === 'unread' ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-rose-50 hover:text-rose-600'; ?>">
                        Não Lida
                    </a>
                    <a href="messages.php?action=update_status&id=<?php echo $v_msg['id']; ?>&status=read" class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition border <?php echo ($v_msg['status'] ?? '') === 'read' ? 'bg-slate-800 text-white border-slate-800' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-200'; ?>">
                        Lida
                    </a>
                    <a href="messages.php?action=update_status&id=<?php echo $v_msg['id']; ?>&status=replied" class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition border <?php echo ($v_msg['status'] ?? '') === 'replied' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-emerald-50 hover:text-emerald-600'; ?>">
                        Respondida
                    </a>
                    <a href="messages.php?action=update_status&id=<?php echo $v_msg['id']; ?>&status=archived" class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition border <?php echo ($v_msg['status'] ?? '') === 'archived' ? 'bg-amber-600 text-white border-amber-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-amber-50 hover:text-amber-600'; ?>">
                        Arquivar
                    </a>
                </div>
            </div>

            <!-- Form de Anotações Internas -->
            <form method="POST" action="messages.php" class="pt-2 border-t border-slate-100 space-y-2">
                <input type="hidden" name="action" value="save_notes">
                <input type="hidden" name="msg_id" value="<?php echo $v_msg['id']; ?>">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Anotações Internas (Privado)</label>
                <textarea name="notes" rows="2" placeholder="Ex: Respondido via WhatsApp em 18/08..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-none"><?php echo htmlspecialchars($v_msg['notes'] ?? ''); ?></textarea>
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded-xl text-xs transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> <span>Salvar Anotação</span>
                </button>
            </form>

            <!-- Excluir Mensagem -->
            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <a href="messages.php?action=delete&id=<?php echo $v_msg['id']; ?>" onclick="return confirm('Deseja realmente excluir esta mensagem permanentemente?')" class="text-red-500 hover:text-red-700 font-bold text-[11px] flex items-center gap-1">
                    <i class="fas fa-trash-alt"></i> <span>Excluir Mensagem</span>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>
