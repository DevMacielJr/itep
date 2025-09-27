<?php
// Conectar ao banco de dados
include 'db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}

// Definir a coluna e a direção da ordenação (padrão: ID ascendente)
$colunaOrdenacao = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'PRIORIDADE,RECEB_ITEP';
$direcaoOrdenacao = isset($_GET['direction']) && $_GET['direction'] == 'desc' ? 'DESC' : 'ASC';

// Filtros
$filtros = [];
//if (!empty($_GET['SEI'])) {
//    $filtros[] = "p.SEI LIKE '%" . $_GET['SEI'] . "%'";
//}
//if (!empty($_GET['OFICIO'])) {
//    $filtros[] = "p.OFICIO LIKE '%" . $_GET['OFICIO'] . "%'";
//}
//if (!empty($_GET['data_inicio']) && !empty($_GET['data_fim'])) {
//    $filtros[] = "p.DATA_INICIO BETWEEN '" . $_GET['data_inicio'] . "' AND '" . $_GET['data_fim'] . "'";
//} elseif (!empty($_GET['data_inicio'])) {
//    $filtros[] = "p.DATA_INICIO >= '" . $_GET['data_inicio'] . "'";
//} elseif (!empty($_GET['data_fim'])) {
//    $filtros[] = "p.DATA_INICIO <= '" . $_GET['data_fim'] . "'";
//}
// só funciona se for para mostrar todos
if (!isset($_GET['pendente'])){
        $filtros [] = "p.STATUS < 2";
}
if (!isset($_GET['ativo'])){
    $filtros[] = "p.ATIVO = 1";
}
// Montar a cláusula WHERE se houver filtros
$where = !empty($filtros) ? 'WHERE ' . implode(' AND ', $filtros) : '';
//$where = "WHERE p.STATUS < 2 AND p.ATIVO = 1";

// Controle de Paginação
$limite = isset($_GET['limite']) && is_numeric($_GET['limite']) ? (int)$_GET['limite'] : 100;
$paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaAtual - 1) * $limite;

// Contagem total para paginação
$totalSQL = "
    SELECT COUNT(*) FROM Pericias p
    LEFT JOIN Prioridade pri ON p.PRIORIDADE = pri.ID
    LEFT JOIN Status s ON p.STATUS = s.ID
    LEFT JOIN Pessoa per ON p.PERITO = per.ID
    LEFT JOIN Vestigio vest ON p.TIPO_VEST = vest.ID
    LEFT JOIN Generico gen ON p.GENERICO = gen.ID
    LEFT JOIN Especifico esp ON p.ESPECIFICO = esp.ID
    LEFT JOIN Resultado res ON p.RESULTADO = res.ID
    $where
";
$totalStmt = $pdo->prepare($totalSQL);
$totalStmt->execute();
$totalResultados = $totalStmt->fetchColumn();

// Consulta SQL com limites para a paginação
$sql = "
    SELECT
        p.ID,
        p.SEI,
        pri.Tipo AS Prioridade,
        s.Status AS Status,
        per.Nome AS Perito,
        p.OFICIO,
        p.ORIGEM,
        p.RECEB_ITEP,
        p.RECEB_SETOR,
        p.QTD_VEST,
        p.OBSERVACAO,
        vest.Tipo AS TipoVestigio,
        p.QTD_CELULAR,
        p.QTD_TABLET,
        p.QTD_SIMCARD,
        p.QTD_CART_MEM,
        p.QTD_PEN_DRIVE,
        p.QTD_DESKTOP,
        p.QTD_NOTEBOOK,
        p.QTD_HD,
        p.QTD_VIDEO,
        p.QTD_AUDIO,
        p.QTD_FOTO,
        p.QTD_DVR,
        p.QTD_SMARTWATCH,
        gen.Tipo AS Generico,
        esp.Tipo AS Especifico,
        p.DATA_COBRANCA,
        p.DIAS_COBRANCA,
        p.COBRANCA_EXPIRA,
        p.VIA_COBRANCA,
        res.Resultado AS Resultado,
        p.LAUDO,
        p.DATA_INICIO,
        p.DATA_FIM,
        p.DIAS_FIM_SETOR,
        p.DIAS_FIM_PERITO,
        p.MES_RECEBIMENTO,
        p.ANO_RECEBIMENTO,
        p.MES_ATRIB,
        p.ANO_ATRIB,
        p.MES_CONCLUSAO,
        p.ANO_CONCLUSAO,
        p.TEMPO_RECEBIMENTO,
        p.DIAS_CHEGADA_SETOR,
        p.ID,
        p.UID,
        p.DATA_ALTERACAO,
        p.ATIVO,
        p.ID_ORIGINAL
    FROM Pericias p
    LEFT JOIN Prioridade pri ON p.PRIORIDADE = pri.ID
    LEFT JOIN Status s ON p.STATUS = s.ID
    LEFT JOIN Pessoa per ON p.PERITO = per.ID
    LEFT JOIN Vestigio vest ON p.TIPO_VEST = vest.ID
    LEFT JOIN Generico gen ON p.GENERICO = gen.ID
    LEFT JOIN Especifico esp ON p.ESPECIFICO = esp.ID
    LEFT JOIN Resultado res ON p.RESULTADO = res.ID
    $where
    ORDER BY p.$colunaOrdenacao $direcaoOrdenacao
    LIMIT $inicio, $limite
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pericias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculando o total de páginas
$totalPaginas = ceil($totalResultados / $limite);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Perícias</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .filters input {
            width: 100%;
        }
        .pagination {
            margin: 20px 0;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h1>Consulta de Perícias</h1>

<!--
    // Filtros
    <form method="GET" class="filters">
        <label for="SEI">SEI:</label>
        <input type="text" name="SEI" value="<?= htmlspecialchars($_GET['SEI'] ?? '') ?>">

        <label for="OFICIO">Ofício:</label>
        <input type="text" name="OFICIO" value="<?= htmlspecialchars($_GET['OFICIO'] ?? '') ?>">

        <label for="SEI">Perito:</label>
        <input type="text" name="Perito" value="<?= htmlspecialchars($_GET['Perito'] ?? '') ?>">

        <label for="OFICIO">Tipo de Vestigio:</label>
        <input type="text" name="Vestigio" value="<?= htmlspecialchars($_GET['Vestigio'] ?? '') ?>">

        <label for="data_inicio">Data Início:</label>
        <input type="date" name="data_inicio" value="<?= htmlspecialchars($_GET['data_inicio'] ?? '') ?>">

        <label for="data_fim">Data Fim:</label>
        <input type="date" name="data_fim" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">

        <button type="submit">Filtrar</button>
    </form>
-->

     <form action="inserir.php" method="post">
     <button type="submit" name="ACAO" value="incluir">Incluir Nova Perícia</button>
     </form>



<!-- Paginação -->
<div class="pagination">
    <form method="GET">
    <?php
        $inicio1=$inicio+1;
        $final = $inicio + $limite;
        if ($final > $totalResultados) {
            $final = $totalResutados;
        }
        echo "Mostrando de $inicio1 a $final de $totalResultados resultados - "
        ?>
        <label for="limite">Mostrar:</label>
        <select name="limite" onchange="this.form.submit()">
            <option value="10" <?= $limite == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $limite == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $limite == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limite == 100 ? 'selected' : '' ?>>100</option>
            <option value="999999" <?= $limite == 999999 ? 'selected' : '' ?>>Todos</option>
        </select>
        <?php echo " por página" ?>
        </form>
    <p>
    <form method="GET" style="display: inline;">
        <label for="pagina">Ir para a Página:</label>
        <input type="number" name="pagina" min="1" max="<?= $totalPaginas ?>" value="<?= $paginaAtual ?>" style="width: 50px;">
        <input type="hidden" name="limite" value="<?= $limite ?>">
        <input type="hidden" name="sort_by" value="<?= $colunaOrdenacao ?>">
        <input type="hidden" name="direction" value="<?= $direcaoOrdenacao ?>">
        <?php echo "de $totalPaginas" ?>
        <button type="submit">Ir</button>
    </form>
    <?php if ($paginaAtual > 1): ?>
        <a href="?pagina=<?= $paginaAtual - 1 ?>&limite=<?= $limite ?>&sort_by=<?= $colunaOrdenacao ?>&direction=<?= $direcaoOrdenacao ?>">&#10094; Anterior</a>
    <?php endif; ?>


    <?php if ($paginaAtual < $totalPaginas): ?>
        <a href="?pagina=<?= $paginaAtual + 1 ?>&limite=<?= $limite ?>&sort_by=<?= $colunaOrdenacao ?>&direction=<?= $direcaoOrdenacao ?>">Próxima &#10095;</a>
    <?php endif; ?>
</div>
    <!-- Tabela de Dados -->
    <table>
        <thead>
            <tr>
             <!--   <th><a href="?sort_by=ID&direction=<?= $direcaoOrdenacao == 'ASC' ? 'desc' : 'asc' ?>">ID</a></th> -->
             <th><a href="?sort_by=ID&direction=<?= $direcaoOrdenacao == 'ASC' ? 'desc' : 'asc' ?>">ID</a></th>
             <!--      <th><a href="?sort_by=SEI&direction=<?= $direcaoOrdenacao == 'ASC' ? 'desc' : 'asc' ?>">Nº SEI</a></th> -->
                <th>Nº SEI</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Perito Designado</th>
             <!--      <th><a href="?sort_by=OFICIO&direction=<?= $direcaoOrdenacao == 'ASC' ? 'desc' : 'asc' ?>">Ofício</a></th> -->
                <th>Ofício</th>
                <th>Origem</th>
                <th>Recebido ITEP</th>
                <th>Recebido Setor</th>
                <th>Qtd Vest</th>
                <th>Observação</th>
                <th>Tipo Vestígio</th>
                <th>Qtd Celular</th>
                <th>Qtd Tablet</th>
                <th>Qtd SIM Card</th>
                <th>Qtd Cartão Memória</th>
                <th>Qtd Pen Drive</th>
                <th>Qtd Desktop</th>
                <th>Qtd Notebook</th>
                <th>Qtd HD</th>
                <th>Qtd Vídeo</th>
                <th>Qtd Áudio</th>
                <th>Qtd Foto</th>
                <th>Qtd DVR</th>
                <th>Qtd Smartwatch</th>
                <th>Tipo Genérico</th>
                <th>Tipo Específico</th>
                <th>Data Cobrança</th>
                <th>Dias Cobrança</th>
                <th>Expira Cobrança</th>
                <th>Via Cobrança</th>
                <th>Resultado</th>
                <th>Laudo</th>
                <th>Data Início</th>
                <th>Data Fim</th>
<!--            Campos de controle
                <th>Dias Fim Setor</th>
                <th>Dias Fim Perito</th>
<th>Mês Recebimento</th>
                <th>Ano Recebimento</th>
                <th>Mês Atribuição</th>
                <th>Ano Atribuição</th>
                <th>Mês Conclusão</th>
                <th>Ano Conclusão</th>
                <th>Tempo Recebimento</th>
                <th>Dias Chegada Setor</th>
                <th>ID</th>
                <th>Responsável pela Alteração</th>
                <th>Data da Alteração</th> -->
                <th>ATIVO</th>
                <th>ID_ORIGINAL</th>
                <th colspan=2 align="center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pericias as $pericia): ?>
                <tr>
                    <td><?= htmlspecialchars($pericia['ID']) ?></td>
                    <td><?= htmlspecialchars($pericia['SEI']) ?></td>
                    <td><?= htmlspecialchars($pericia['Prioridade']) ?></td>
                    <td><?= htmlspecialchars($pericia['Status']) ?></td>
                    <td><?= htmlspecialchars($pericia['Perito']) ?></td>
                    <td><?= htmlspecialchars($pericia['OFICIO']) ?></td>
                    <td><?= htmlspecialchars($pericia['ORIGEM']) ?></td>
                    <td><?= htmlspecialchars($pericia['RECEB_ITEP']) ?></td>
                    <td><?= htmlspecialchars($pericia['RECEB_SETOR']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_VEST']) ?></td>
                    <td><?= htmlspecialchars($pericia['OBSERVACAO']) ?></td>
                    <td><?= htmlspecialchars($pericia['TipoVestigio']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_CELULAR']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_TABLET']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_SIMCARD']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_CART_MEM']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_PEN_DRIVE']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_DESKTOP']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_NOTEBOOK']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_HD']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_VIDEO']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_AUDIO']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_FOTO']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_DVR']) ?></td>
                    <td><?= htmlspecialchars($pericia['QTD_SMARTWATCH']) ?></td>
                    <td><?= htmlspecialchars($pericia['Generico']) ?></td>
                    <td><?= htmlspecialchars($pericia['Especifico']) ?></td>
                    <td><?= htmlspecialchars($pericia['DATA_COBRANCA']) ?></td>
                    <td><?= htmlspecialchars($pericia['DIAS_COBRANCA']) ?></td>
                    <td><?= htmlspecialchars($pericia['COBRANCA_EXPIRA']) ?></td>
                    <td><?= htmlspecialchars($pericia['VIA_COBRANCA']) ?></td>
                    <td><?= htmlspecialchars($pericia['Resultado']) ?></td>
                    <td><?= htmlspecialchars($pericia['LAUDO']) ?></td>
                    <td><?= htmlspecialchars($pericia['DATA_INICIO']) ?></td>
                    <td><?= htmlspecialchars($pericia['DATA_FIM']) ?></td>
<?php   /*               Campos de controle
                    <td><?= htmlspecialchars($pericia['DIAS_FIM_SETOR']) ?></td>
                    <td><?= htmlspecialchars($pericia['DIAS_FIM_PERITO']) ?></td>
                    <td><?= htmlspecialchars($pericia['MES_RECEBIMENTO']) ?></td>
                    <td><?= htmlspecialchars($pericia['ANO_RECEBIMENTO']) ?></td>
                    <td><?= htmlspecialchars($pericia['MES_ATRIB']) ?></td>
                    <td><?= htmlspecialchars($pericia['ANO_ATRIB']) ?></td>
                    <td><?= htmlspecialchars($pericia['MES_CONCLUSAO']) ?></td>
                    <td><?= htmlspecialchars($pericia['ANO_CONCLUSAO']) ?></td>
                    <td><?= htmlspecialchars($pericia['TEMPO_RECEBIMENTO']) ?></td>
                    <td><?= htmlspecialchars($pericia['DIAS_CHEGADA_SETOR']) ?></td>
                    <td><?= htmlspecialchars($pericia['ID']) ?></td>
                    <td><?= htmlspecialchars($pericia['UID']) ?></td>
                    <td><?= htmlspecialchars($pericia['DATA_ALTERACAO']) ?></td>
*/?>
                    <td><?= htmlspecialchars($pericia['ATIVO']) ?></td>
                    <td><?= htmlspecialchars($pericia['ID_ORIGINAL']) ?></td>

                    <td>
                        <form action="inserir.php" method="post">
                            <input type="hidden" name="id" value="<?= $pericia['ID'] ?>">
                            <button type="submit" name="ACAO" value="mudar">Alterar</button>
                    </td>
                    <td>
                            <button type="submit" name="ACAO" value="excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Seleção de Limite de Linhas -->
    <p/>

<!-- Paginação -->
<div class="pagination">
    <form method="GET">
    <?php
        $inicio1=$inicio+1;
        $final = $inicio1 + $limite;
        if ($final > $totalResultados) {
            $final = $totalResultados;
        }
        echo "Mostrando de $inicio1 a $final de $totalResultados resultados - "
        ?>
        <label for="limite">Mostrar:</label>
        <select name="limite" onchange="this.form.submit()">
            <option value="10" <?= $limite == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $limite == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $limite == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limite == 100 ? 'selected' : '' ?>>100</option>
            <option value="999999" <?= $limite == 999999 ? 'selected' : '' ?>>Todos</option>
        </select>
        <?php echo " por página" ?>
        </form>
    <p>
    <form method="GET" style="display: inline;">
        <label for="pagina">Ir para a Página:</label>
        <input type="number" name="pagina" min="1" max="<?= $totalPaginas ?>" value="<?= $paginaAtual ?>" style="width: 50px;">
        <input type="hidden" name="limite" value="<?= $limite ?>">
        <input type="hidden" name="sort_by" value="<?= $colunaOrdenacao ?>">
        <input type="hidden" name="direction" value="<?= $direcaoOrdenacao ?>">
        <?php echo "de $totalPaginas" ?>
        <button type="submit">Ir</button>
    </form>
    <?php if ($paginaAtual > 1): ?>
        <a href="?pagina=<?= $paginaAtual - 1 ?>&limite=<?= $limite ?>&sort_by=<?= $colunaOrdenacao ?>&direction=<?= $direcaoOrdenacao ?>">&#10094; Anterior</a>
    <?php endif; ?>
    <?php if ($paginaAtual < $totalPaginas): ?>
        <a href="?pagina=<?= $paginaAtual + 1 ?>&limite=<?= $limite ?>&sort_by=<?= $colunaOrdenacao ?>&direction=<?= $direcaoOrdenacao ?>">Próxima &#10095;</a>
    <?php endif; ?>
</div>
</body>
</html>
