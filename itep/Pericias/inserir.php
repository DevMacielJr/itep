<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['ACAO'] == "incluir" || $_POST['ACAO'] == "inserir") {
                echo "Incluir ";
            }
            if ($_POST['ACAO'] == "change" || $_POST['ACAO'] == "alterar")  {
                echo "Alterar ";
            }
            if ($_POST['ACAO'] == "excluir") {
                echo "Excluir ";
            }
        }
        else {
            echo "Incluir ";
        }
        ?>Perícia
    </title>
</head>
<body>
    <h1>
<?php
// Conectar ao banco de dados
include 'db_connection.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

// Variável para armazenar os dados da perícia
$pericia = [];
$where='';
// Pegar esta informação do login do Usuário
$uid=17081034810;

// Consultar valores para os menus drop-down
$prioridades = $conn->query("SELECT ID, Tipo FROM Prioridade")->fetchAll(PDO::FETCH_ASSOC);
$statuses = $conn->query("SELECT ID, Status FROM Status")->fetchAll(PDO::FETCH_ASSOC);
$peritos = $conn->query("SELECT ID, Nome FROM Pessoa")->fetchAll(PDO::FETCH_ASSOC);
$tipos_vest = $conn->query("SELECT ID, Tipo FROM Vestigio")->fetchAll(PDO::FETCH_ASSOC);
$genericos = $conn->query("SELECT ID, Tipo FROM Generico")->fetchAll(PDO::FETCH_ASSOC);
$especificos = $conn->query("SELECT ID, Tipo FROM Especifico")->fetchAll(PDO::FETCH_ASSOC);
$resultados = $conn->query("SELECT ID, Resultado FROM Resultado")->fetchAll(PDO::FETCH_ASSOC);


// Inserir dados no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "111 222 333 ID = $_POST['id']";
    $id = $_POST['id'];
    $id_atual = $_POST['id'];
//    echo "111 222 333 ID = $id_atual";
    // Verificar se um ID foi fornecido para edição
    if (!empty($_POST['id'])) {
//        echo "ID ATUAL = $id_atual";

        // Carregar os dados existentes do banco de dados
        echo "SELECT * FROM Pericias WHERE ID = ?";
        $stmt = $conn->prepare("SELECT * FROM Pericias WHERE ID = ?");
        echo "ID ATUAL = $id_atual";
        $stmt->execute([$id_atual]);
        $pericia = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_original = $pericia['ID_ORIGINAL'];
        echo "ID ORIGINAL = $id_original";
//        $especifico1 = $pericia['ESPECIFICO'];
//        echo "ESPECIFICO1 = $especifico1";
//        $generico1 = $pericia['GENERICO'];
//        echo "GENERICO1 = $generico1";
//        $tipo_vest1 = $pericia['TIPO_VEST'];
//        echo "TIPO_VEST = $tipo_vest1";
//        $resultado1 = $pericia['RESULTADO'];
//        echo "RESULTADO1 = $resultado1";
    }

    if ($_POST['ACAO'] == "inserir" || $_POST['ACAO'] == "alterar" ) {
        $prioridade = $_POST['prioridade'];
        $status = $_POST['status'];
        $perito = $_POST['perito'];
        $perito = empty($perito) ? NULL : $perito;
        $sei = $_POST['sei'];
        $sei = empty($sei) ? NULL : $sei;
        $oficio = $_POST['oficio'];
        $oficio = empty($oficio) ? NULL : $oficio;
        $origem = $_POST['origem'];
        $origem = empty($origem) ? NULL : $origem;
        $tipo_vest1 = $_POST['tipo_vest'];
        $tipo_vest1 = empty($tipo_vest1) ? NULL : $tipo_vest1;
        echo "TIPO_VEST1 = $tipo_vest1";
        $generico1 = $_POST['generico'];
        $generico1 = empty($generico1) ? NULL : $generico1;
        $especifico1 = $_POST['especifico'];
        $especifico1 = empty($especifico1) ? NULL : $especifico1;
        echo "ESPECIFICO1 = $especifico1";
        echo "GENERICO1 = $generico1";
        $resultado1 = $_POST['resultado'];
        $resultado1 = empty($resultado1) ? NULL : $resultado1;
        echo "RESULTADO1 = $resultado1";
        $receb_itep = $_POST['receb_itep'];
        $receb_itep = empty($receb_itep) ? NULL : $receb_itep;
        $receb_setor = $_POST['receb_setor'];
        $receb_setor = empty($receb_setor) ? NULL : $receb_setor;
        $qtd_vest = $_POST['qtd_vest'];
        $qtd_vest = empty($qtd_vest) ? NULL : $qtd_vest;
        $observacao = $_POST['observacao'];
        $observacao = empty($observacao) ? NULL : $observacao;
        $qtd_celular = $_POST['qtd_celular'];
        $qtd_celular = empty($qtd_celular) ? NULL : $qtd_celular;
        $qtd_tablet = $_POST['qtd_tablet'];
        $qtd_tablet = empty($qtd_tablet) ? NULL : $qtd_tablet;
        $qtd_simcard = $_POST['qtd_simcard'];
        $qtd_simcard = empty($qtd_simcard) ? NULL : $qtd_simcard;
        $qtd_cart_mem = $_POST['qtd_cart_mem'];
        $qtd_cart_mem = empty($qtd_cart_mem) ? NULL : $qtd_cart_mem;
        $qtd_pen_drive = $_POST['qtd_pen_drive'];
        $qtd_pen_drive = empty($qtd_pen_drive) ? NULL : $qtd_pen_drive;
        $qtd_desktop = $_POST['qtd_desktop'];
        $qtd_desktop = empty($qtd_desktop) ? NULL : $qtd_desktop;
        $qtd_notebook = $_POST['qtd_notebook'];
        $qtd_notebook = empty($qtd_notebook) ? NULL : $qtd_notebook;
        $qtd_hd = $_POST['qtd_hd'];
        $qtd_hd = empty($qtd_hd) ? NULL : $qtd_hd;
        $qtd_video = $_POST['qtd_video'];
        $qtd_video = empty($qtd_video) ? NULL : $qtd_video;
        $qtd_audio = $_POST['qtd_audio'];
        $qtd_audio = empty($qtd_audio) ? NULL : $qtd_audio;
        $qtd_foto = $_POST['qtd_foto'];
        $qtd_foto = empty($qtd_foto) ? NULL : $qtd_foto;
        $qtd_dvr = $_POST['qtd_dvr'];
        $qtd_dvr = empty($qtd_dvr) ? NULL : $qtd_dvr;
        $qtd_smartwatch = $_POST['qtd_smartwatch'];
        $qtd_smartwatch = empty($qtd_smartwatch) ? NULL : $qtd_smartwatch;
        $data_cobranca = $_POST['data_cobranca'];
        $data_cobranca = empty($data_cobranca) ? NULL : $data_cobranca;
        $dias_cobranca = $_POST['dias_cobranca'];
        $dias_cobranca = empty($dias_cobranca) ? NULL : $dias_cobranca;
        $cobranca_expira = $_POST['cobranca_expira'];
        $cobranca_expira = empty($cobranca_expira) ? NULL : $cobranca_expira;
        $via_cobranca = $_POST['via_cobranca'];
        $via_cobranca = empty($via_cobranca) ? NULL : $via_cobranca;
        $laudo = $_POST['laudo'];
        $laudo = empty($laudo) ? NULL : $laudo;
        $data_inicio = $_POST['data_inicio'];
        $data_inicio = empty($data_inicio) ? NULL : $data_inicio;
        $data_fim = $_POST['data_fim'];
        $data_fim = empty($data_fim) ? NULL : $data_fim;
    }
    if ($_POST['ACAO'] == "excluir") {
        echo "Excluir Perícia";
        echo "\n\t</h1>";

        // Etapa 1 - duplicar a linha
        echo "
        INSERT INTO Pericias (
            PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO,     RESULTADO,
            RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD,     QTD_CART_MEM,
            QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR,
            QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO,     DATA_INICIO,
            DATA_FIM
        )
        SELECT
            PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO, RESULTADO,
            RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD,     QTD_CART_MEM,
            QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR,
            QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO,     DATA_INICIO,
            DATA_FIM
        FROM Pericias
        WHERE ID = ?
        ";
    $stmt = $conn->prepare("
        INSERT INTO Pericias (
            PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO,     RESULTADO,
            RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD,     QTD_CART_MEM,
            QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR,
            QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO,     DATA_INICIO,
            DATA_FIM
        )
        SELECT
            PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO, RESULTADO,
            RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD,     QTD_CART_MEM,
            QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR,
            QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO,     DATA_INICIO,
            DATA_FIM
        FROM Pericias
        WHERE ID = ?
        ");
        if ( $stmt->execute([$id] ) )  {
            // Etapa 2 - desativar o novo registro
            $id_atual = $conn->lastInsertId(); // Obtém o último ID inserido
            echo "UPDATE Pericias SET ID_ORIGINAL = ?, ATIVO = 0, UID = ? WHERE ID = ?";
            $updateStmt = $conn->prepare("UPDATE Pericias SET ID_ORIGINAL = ?, ATIVO = 0, UID = ? WHERE ID = ?");
            $updateSuccess = $updateStmt->execute([$id_original, $uid, $id_atual]);
            if ($updateSuccess) {
                // Etapa 3 - desativar o registro alterado
                echo "Dados de exclusão duplicados com sucesso!";
                // Atualiza o campo ATIVO do registro alterado para ser "0" - Desativado
                echo "UPDATE Pericias SET ATIVO = 0 WHERE ID = ?";
                $updateStmt = $conn->prepare("UPDATE Pericias SET ATIVO = 0 WHERE ID = ?");
                $updateSuccess = $updateStmt->execute([$id_atual]);
                if ($updateSuccess){
                    echo "Dados excluidos com sucesso!";
                } else {
                    echo "Erro ao excluir o registo $id_original - etapa 3.";
                }
            }
            else {
                echo "Erro ao excluir o registo $id_original - etapa 2.";
            }
        }
        else {
                echo "Erro ao excluir o registo $id_original - etapa 1.";
        }
    echo "<form action=\"pericias.php\" method=\"post\">";
    echo "<button type=\"submit\">Voltar</button>";

    echo "\n</body>";
    echo "\n</html>";
    exit();
    }
    else if ($_POST['ACAO'] == "alterar") {
        echo "Alterar Perícia";
        echo "\n\t</h1>";

        // Atualizar uma perícia existente significa desativar a original, e criar uma nova, igual, com os campos alterados
        echo "INSERT INTO Pericias (PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO, RESULTADO, RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD, QTD_CART_MEM, QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR, QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO, DATA_INICIO, DATA_FIM, UID, ID_ORIGINAL, ATIVO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        echo "$prioridade, $status, $perito, $sei, $oficio, $origem, $tipo_vest1, $generico1, $especifico1, $resultado1, $receb_itep, $receb_setor, $qtd_vest, $observacao, $qtd_celular, $qtd_tablet, $qtd_simcard, $qtd_cart_mem, $qtd_pen_drive, $qtd_desktop, $qtd_notebook, $qtd_hd, $qtd_video, $qtd_audio, $qtd_foto, $qtd_dvr, $qtd_smartwatch, $data_cobranca, $dias_cobranca, $cobranca_expira, $via_cobranca, $laudo, $data_inicio, $data_fim, $uid, $id_original";

        $stmt = $conn->prepare("INSERT INTO Pericias (PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO, RESULTADO, RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD, QTD_CART_MEM, QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR, QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO, DATA_INICIO, DATA_FIM, UID, ID_ORIGINAL, ATIVO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $success = $stmt->execute([$prioridade, $status, $perito, $sei, $oficio, $origem, $tipo_vest1, $generico1, $especifico1, $resultado1, $receb_itep, $receb_setor, $qtd_vest, $observacao, $qtd_celular, $qtd_tablet, $qtd_simcard, $qtd_cart_mem, $qtd_pen_drive, $qtd_desktop, $qtd_notebook, $qtd_hd, $qtd_video, $qtd_audio, $qtd_foto, $qtd_dvr, $qtd_smartwatch , $data_cobranca, $dias_cobranca, $cobranca_expira, $via_cobranca, $laudo, $data_inicio, $data_fim, $uid, $id_original]);
        if ($success) {
            // Atualiza o campo ATIVO do registro alterado para ser "0" - Desativado
            $id_atual = $conn->lastInsertId(); // Obtém o último ID inserido
            echo "UPDATE Pericias SET ATIVO = 0 WHERE ID = ?";
            $updateStmt = $conn->prepare("UPDATE Pericias SET ATIVO = 0 WHERE ID = ?");
            $updateSuccess = $updateStmt->execute([$id]);
            echo $updateSuccess ? "Dados atualizados com sucesso!" : "Erro ao atualizar o campo ATIVO do registo de ID $id.";
        } else {
            echo "Erro ao alterar os dados.";
        }
    } else if ($_POST['ACAO'] == "inserir") {
        echo "Inserir Perícia";
        echo "\n\t</h1>";
        // Inserir nova perícia
        $stmt = $conn->prepare("INSERT INTO Pericias (PRIORIDADE, STATUS, PERITO, SEI, OFICIO, ORIGEM, TIPO_VEST, GENERICO, ESPECIFICO, RESULTADO, RECEB_ITEP, RECEB_SETOR, QTD_VEST, OBSERVACAO, QTD_CELULAR, QTD_TABLET, QTD_SIMCARD, QTD_CART_MEM, QTD_PEN_DRIVE, QTD_DESKTOP, QTD_NOTEBOOK, QTD_HD, QTD_VIDEO, QTD_AUDIO, QTD_FOTO, QTD_DVR, QTD_SMARTWATCH, DATA_COBRANCA, DIAS_COBRANCA, COBRANCA_EXPIRA, VIA_COBRANCA, LAUDO, DATA_INICIO, DATA_FIM, UID, ATIVO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $success = $stmt->execute([$prioridade, $status, $perito, $sei, $oficio, $origem, $tipo_vest1, $generico1, $especifico1, $resultado1, $receb_itep, $receb_setor, $qtd_vest, $observacao, $qtd_celular, $qtd_tablet, $qtd_simcard, $qtd_cart_mem, $qtd_pen_drive, $qtd_desktop, $qtd_notebook, $qtd_hd, $qtd_video, $qtd_audio, $qtd_foto, $qtd_dvr, $qtd_smartwatch, $data_cobranca, $dias_cobranca, $cobranca_expira, $via_cobranca, $laudo, $data_inicio, $data_fim, $uid]);
        if ($success) {
            // Atualiza o campo ID_ORIGINAL para ser igual ao ID inserido
            $lastId = $conn->lastInsertId(); // Obtém o último ID inserido
            $updateStmt = $conn->prepare("UPDATE Pericias SET ID_ORIGINAL = ? WHERE ID = ?");
            $updateSuccess = $updateStmt->execute([$id_original, $lastId]);
            echo $updateSuccess ? "Dados inseridos e atualizados com sucesso!" : "Erro ao atualizar o campo ID_ORIGINAL.";
        } else {
            echo "Erro ao inserir os dados.";
        }
    }
}
?>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['ACAO'] == "incluir") {
                echo "Inserir Perícia";
            }
            if ($_POST['ACAO'] == "mudar")  {
                echo "Alterar ";
            }
        }
        else {
            echo "Incluir Perícia";
        }
        ?>
    </h1>

<form method="POST" action="inserir.php">
    <!-- Campo oculto para armazenar o ID da perícia -->
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
<!--    <input type="hidden" name="id_original" value="<?php echo isset($pericia['ID_ORIGINAL']) ?
    $pericia['ID_ORIGINAL'] : ''; ?>">
    <input type="hidden" name="id_atual" value="<?php echo isset($id) ?
    $id : ''; ?>"> -->

    <table border=0 bordercolor=black>
        <tr>
            <td><label for="prioridade">Prioridade:</label></td>
            <td>
                <select name="prioridade" id="prioridade" required>
                    <?php foreach ($prioridades as $prioridade): ?>
                        <option value="<?php echo $prioridade['ID']; ?>" <?php echo isset($pericia['PRIORIDADE']) && $pericia['PRIORIDADE'] == $prioridade['ID'] ? ' selected' : ''; ?>><?php echo $prioridade['Tipo']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="status">Status:</label></td>
            <td>
                <select name="status" id="status" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo $status['ID']; ?>" <?php echo isset($pericia['STATUS']) && $pericia['STATUS'] == $status['ID'] ? ' selected' : ''; ?>><?php echo $status['Status']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="perito">Perito:</label></td>
            <td>
                <select name="perito" id="perito">
                    <option value="">Selecione o perito</option>
                        <?php foreach ($peritos as $perito): ?>
                            <option value="<?php echo $perito['ID']; ?>" <?php echo isset($pericia['PERITO']) && $pericia['PERITO'] == $perito['ID'] ? 'selected' : ''; ?>><?= $perito['Nome'] ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="sei">SEI:</label>
            </td>
            <td>
                <input type="text" name="sei" id="sei" value="<?php echo isset($pericia['SEI']) ? $pericia['SEI'] : ''; ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="oficio">Ofício:</label>
            </td>
            <td>
                <input type="text" name="oficio" id="oficio" value="<?php echo isset($pericia['OFICIO']) ? $pericia['OFICIO'] : ''; ?>">
            </td>
        </tr>
        <tr>
            <td><label for="origem">Origem:</label></td>
            <td><textarea name="origem" id="origem"><?php echo isset($pericia['ORIGEM']) ? $pericia['ORIGEM'] : ''; ?></textarea></td>
        </tr>
        <tr>
            <td><label for="receb_itep">DATA DE RECEBIMENTO NO ITEP:</label></td>
            <td><input type="date" name="receb_itep" id="receb_itep" value="<?php echo isset($pericia['RECEB_ITEP']) ? $pericia['RECEB_ITEP'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="receb_setor">DATA DE RECEBIMENTO NO SETOR:</label></td>
             <td><input type="date" name="receb_setor" id="receb_setor" value="<?php echo isset($pericia['RECEB_SETOR']) ? $pericia['RECEB_SETOR'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_vest">QUANTIDADE DE VESTÍGIOS TOTAL:</label></td>
            <td><input type="text" size=3 name="qtd_vest" id="qtd_vest" value="<?php echo isset($pericia['QTD_VEST']) ? $pericia['QTD_VEST'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="tipo_vest">Tipo de Vestígio:</label></td>
            <td>
                <select name="tipo_vest" id="tipo_vest" required>
                    <?php foreach ($tipos_vest as $tipo_vest): ?>
                        <option value="<?= $tipo_vest['ID'] ?>"<?php if (empty($tipo_vest1)) {
                                if (trim($tipo_vest['ID']) === 0) { echo ' selected'; };
                            }
                            else {
                                if (trim($tipo_vest['ID']) === trim($tipo_vest1)) {
                                        echo ' selected';
                                }
                            }
                            ?>><?= $tipo_vest['Tipo'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="qtd_celular">Quantidade de CELULAR:</label></td>
            <td><input type="text" size=3 name="qtd_celular" id="qtd_celular" value="<?php echo isset($pericia['QTD_CELULAR']) ? $pericia['QTD_CELULAR'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_tablet">Quantidade de TABLET:</label></td>
            <td><input type="text" size=3 name="qtd_tablet" id="qtd_tablet" value="<?php echo isset($pericia['QTD_TABLET']) ? $pericia['QTD_TABLET'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_simcard">Quantidade de SIMCARD:</label></td>
            <td><input type="text" size=3 name="qtd_simcard" id="qtd_simcard" value="<?php echo isset($pericia['QTD_SIMCARD']) ? $pericia['QTD_SIMCARD'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_cart_mem">Quantidade de CARTÕES DE MEMÓRIA:</label></td>
            <td><input type="text" size=3 name="qtd_cart_mem" id="qtd_cart_mem" value="<?php echo isset($pericia['QTD_CART_MEM']) ? $pericia['QTD_CART_MEM'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_pen_drive">Quantidade de PEN DRIVE:</label></td>
            <td><input type="text" size=3 name="qtd_pen_drive" id="qtd_pen_drive" value="<?php echo isset($pericia['QTD_PEN_DRIVE']) ? $pericia['QTD_PEN_DRIVE'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_desktop">Quantidade de DESKTOP:</label></td>
            <td><input type="text" size=3 name="qtd_desktop" id="qtd_desktop" value="<?php echo isset($pericia['QTD_DESKTOP']) ? $pericia['QTD_DESKTOP'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_notebook">Quantidade de NOTEBOOK:</label></td>
            <td><input type="text" size=3 name="qtd_notebook" id="qtd_notebook" value="<?php echo isset($pericia['QTD_NOTEBOOK']) ? $pericia['QTD_NOTEBOOK'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_hd">Quantidade de HD:</label></td>
            <td><input type="text" size=3 name="qtd_hd" id="qtd_hd" value="<?php echo isset($pericia['QTD_HD']) ? $pericia['QTD_HD'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_video">Quantidade de VIDEO:</label></td>
            <td><input type="text" size=3 name="qtd_video" id="qtd_video" value="<?php echo isset($pericia['QTD_VIDEO']) ? $pericia['QTD_VIDEO'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_audio">Quantidade de AUDIO:</label></td>
            <td><input type="text" size=3 name="qtd_audio" id="qtd_audio" value="<?php echo isset($pericia['QTD_AUDIO']) ? $pericia['QTD_AUDIO'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_foto">Quantidade de FOTO:</label></td>
            <td><input type="text" size=3 name="qtd_foto" id="qtd_foto" value="<?php echo isset($pericia['QTD_FOTO']) ? $pericia['QTD_FOTO'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_dvr">Quantidade de DVR:</label></td>
            <td><input type="text" size=3 name="qtd_dvr" id="qtd_dvr" value="<?php echo isset($pericia['QTD_DRV']) ? $pericia['QTD_DVR'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="qtd_smartwatch">Quantidade de Smartwatch:</label></td>
            <td><input type="text" size=3 name="qtd_smartwatch" id="qtd_smartwatch" value="<?php echo isset($pericia['QTD_SMARTWATCH']) ? $pericia['QTD_SMARTWATCH'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="generico">Tipo de Exame Genérico:</label></td>
            <td>
                <select name="generico" id="generico" required>
                    <?php
                    foreach ($genericos as $generico): ?>
                    <option value="<?= $generico['ID'] ?>"<?php if (empty($generico1)) {
                            if (trim($generico['ID']) === 2) { echo 'selected'; }
                        }
                        else {
                            if (trim($generico['ID']) === trim($generico1)){
                                echo ' selected';
                            }
                        }
                        ?>><?= $generico1 ?> <?= $generico['ID'] ?><?= $generico['Tipo'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="especifico">Tipo de Exame Específico:</label></td>
            <td>
                <select name="especifico" id="especifico" required>
                    <?php foreach ($especificos as $especifico): ?>
                    <option value="<?= $especifico['ID'] ?>"<?php if (empty($especifico1)) {
                            if (trim($especifico['ID']) === 1) { echo 'selected'; };
                        }
                        else {
                            if (trim($especifico['ID']) === trim($especifico1)) {
                                    echo 'selected';
                            }
                        }
                        ?>><?= $especifico['Tipo'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
<!-- //// A PARTIR DAQUI  ///////// -->
        <tr>
            <td><label for="data_cobranca">DATA DA COBRANÇA:</label></td>
            <td><input type="date" name="data_cobranca" id="data_cobranca" value="<?php echo isset($pericia['DATA_COBRANCA']) ? $pericia['DATA_COBRANCA'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="dias_cobranca">Quantidade de Dias de Cobrança:</label></td>
            <td><input type="text" size=3 name="dias_cobranca" id="dias_cobranca" value="<?php echo isset($pericia['DIAS_COBRANCA']) ? $pericia['DIAS_COBRANCA'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="cobranca_expira">Cobrança expira em:</label></td>
            <td><input type="date" name="cobranca_expira" id="cobranca_expira" value="<?php echo isset($pericia['COBRANCA_EXPIRA']) ? $pericia['COBRANCA_EXPIRA'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="via_cobranca">Via de Cobrança:</label></td>
            <td><input type="text" name="via_cobranca" id="via_cobranca" value="<?php echo isset($pericia['VIA_COBRANCA']) ? $pericia['VIA_COBRANCA'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="resultado">Resultado da Extração:</label></td>
            <td>
                <select name="resultado" id="resultado">
                    <option value="">Selecione</option> <!-- Opção vazia -->
                    <?php
                        foreach ($resultados as $resultado): ?>
                            <option value="<?= $resultado['ID'] ?>"<?php if (!empty($resultado1)) {
                                    if (trim($resultado['ID']) === trim($resultado1)) {
                                        echo 'selected';
                                    }
                                }
                                ?>><?= $resultado['Resultado'] ?>
                            </option>
                    <?php endforeach; ?>
            </td>
        <tr>
            <td><label for="laudo">Nº do Laudo:</label></td>
            <td><input type="text" name="laudo" id="laudo" value="<?php echo isset($pericia['LAUDO']) ? $pericia['LAUDO'] : ''; ?>"></textarea></td>
        </tr>
        <tr>
            <td><label for="data_inicio">Data de Início do Exame:</label></td>
            <td><input type="date" name="data_inicio" id="data_inicio" value="<?php echo isset($pericia['DATA_INICIO']) ? $pericia['DATA_INICIO'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="data_fim">Data de Finalização do Exame:</label></td>
            <td><input type="date" name="data_fim" id="data_fim" value="<?php echo isset($pericia['DATA_FIM']) ? $pericia['DATA_FIM'] : ''; ?>"></td>
        </tr>
        <tr>
            <td><label for="observacao">OBSERVAÇÃO:</label></td>
            <td><textarea name="observacao" id="observacao"><?php echo isset($pericia['OBSERVACAO']) ? $pericia['OBSERVACAO'] : ''; ?></textarea></td>
        </tr>
         <tr>
            <td colspan="2"><button type="submit" value="<?php
            if ($_POST['ACAO'] == 'incluir' || $_POST['ACAO'] == 'inserir') {
                echo 'inserir';
            }
            if ($_POST['ACAO'] == 'alterar' || $_POST['ACAO'] == 'mudar') {
                echo 'alterar';
            }
             ?>" name="ACAO" >Salvar</button></td>
        </tr>
    </table>
</form>

</body>
</html>
