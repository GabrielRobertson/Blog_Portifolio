<?php
require_once __DIR__ . '/db.php';

try {
    // 1. Adicionar coluna description se não existir
    try {
        $pdo->exec("ALTER TABLE skills ADD COLUMN description TEXT NULL");
    } catch (PDOException $e) {
        // Coluna já existe
    }

    // 2. Limpar skills antigas para substituir pelas consolidadas do usuário
    $pdo->exec("DELETE FROM skills");

    // 3. Inserir as novas Hard Skills organizadas por categorias
    $skills = [
        // Linguagens e Bancos de Dados
        [
            'name' => 'Python',
            'category' => 'Linguagens e Bancos de Dados',
            'description' => 'Automação de scripts, manipulação de dados, tratamento de erros (exceções) e desenvolvimento de lógicas complexas.',
            'icon_class' => 'fab fa-python',
            'color_class' => 'blue-500',
            'order_index' => 1
        ],
        [
            'name' => 'SQL',
            'category' => 'Linguagens e Bancos de Dados',
            'description' => 'Consultas estruturadas, extração e modelagem de banco de dados.',
            'icon_class' => 'fas fa-database',
            'color_class' => 'emerald-500',
            'order_index' => 2
        ],

        // Análise de Dados e BI
        [
            'name' => 'Power BI',
            'category' => 'Análise de Dados e BI',
            'description' => 'Construção de dashboards dinâmicos e inteligência de negócios.',
            'icon_class' => 'fas fa-chart-line',
            'color_class' => 'amber-500',
            'order_index' => 1
        ],
        [
            'name' => 'Grafana',
            'category' => 'Análise de Dados e BI',
            'description' => 'Monitoramento de dados e visualização métrica.',
            'icon_class' => 'fas fa-chart-area',
            'color_class' => 'orange-500',
            'order_index' => 2
        ],

        // Automação e RPA
        [
            'name' => 'Power Automate',
            'category' => 'Automação e RPA',
            'description' => 'Criação de fluxos de trabalho inteligentes e automação de processos.',
            'icon_class' => 'fas fa-cogs',
            'color_class' => 'purple-600',
            'order_index' => 1
        ],
        [
            'name' => 'Power Apps',
            'category' => 'Automação e RPA',
            'description' => 'Desenvolvimento de soluções e aplicativos internos para eliminar rotinas manuais.',
            'icon_class' => 'fas fa-cubes',
            'color_class' => 'indigo-600',
            'order_index' => 2
        ],

        // Cibersegurança e Proteção de Dados
        [
            'name' => 'Segurança Digital Corporativa',
            'category' => 'Cibersegurança e Proteção de Dados',
            'description' => 'Integração e aplicação de melhores práticas de cibersegurança no desenvolvimento de soluções.',
            'icon_class' => 'fas fa-shield-alt',
            'color_class' => 'emerald-600',
            'order_index' => 1
        ],
        [
            'name' => 'Frameworks e Ferramentas de Defesa',
            'category' => 'Cibersegurança e Proteção de Dados',
            'description' => 'Conhecimento estruturado no NIST Framework e plataformas de proteção avançada como SentinelOne.',
            'icon_class' => 'fas fa-user-shield',
            'color_class' => 'cyan-600',
            'order_index' => 2
        ],

        // Engenharia de Processos e Metodologias
        [
            'name' => 'Análise de Eficiência Operacional',
            'category' => 'Engenharia de Processos e Metodologias',
            'description' => 'Mapeamento de processos, identificação de gargalos e aplicação de melhoria contínua em linhas de produção.',
            'icon_class' => 'fas fa-industry',
            'color_class' => 'slate-700',
            'order_index' => 1
        ],
        [
            'name' => 'Desenvolvimento Full Stack',
            'category' => 'Engenharia de Processos e Metodologias',
            'description' => 'Capacidade de criar e gerenciar aplicações de ponta a ponta (front-end e back-end).',
            'icon_class' => 'fas fa-laptop-code',
            'color_class' => 'indigo-500',
            'order_index' => 2
        ],
        [
            'name' => 'Integração de IA',
            'category' => 'Engenharia de Processos e Metodologias',
            'description' => 'Aplicação de conceitos de Inteligência Artificial para modernização de ambientes industriais tradicionais.',
            'icon_class' => 'fas fa-brain',
            'color_class' => 'purple-500',
            'order_index' => 3
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO skills (name, category, description, icon_class, color_class, order_index) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($skills as $s) {
        $stmt->execute([$s['name'], $s['category'], $s['description'], $s['icon_class'], $s['color_class'], $s['order_index']]);
    }

    echo "Sucesso! 11 Hard Skills consolidadas foram cadastradas no banco de dados.\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
