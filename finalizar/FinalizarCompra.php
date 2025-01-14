<?php
include '../conexao.php';
include '../session_check.php';
require('../fpdf/fpdf.php');
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para finalizar a compra.');
}

$user_id = $_SESSION['user_id'];

// Obtém os itens do carrinho
$query = "
    SELECT p.Nome, p.Preco, p.Imagem, c.quantidade
    FROM carrinho c
    INNER JOIN produtos p ON c.id_Produto = p.id
    WHERE c.id_Cliente = ?
";
$stmt_carrinho = $conexao->prepare($query);
$stmt_carrinho->bind_param("i", $user_id);
$stmt_carrinho->execute();
$result = $stmt_carrinho->get_result();

$total_geral = 0;
$itens_carrinho = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['total'] = $row['Preco'] * $row['quantidade']; // Calcula o total por produto
        $total_geral += $row['total']; // Incrementa o total geral
        $itens_carrinho[] = $row;
    }
} else {
    die('Seu carrinho está vazio. Adicione itens antes de finalizar a compra.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telemovel = $_POST['telemovel'];
    $morada = $_POST['morada'];
    $codigo_postal = $_POST['codigo_postal'];
    $cidade = $_POST['cidade'];
    $id_pagamento = $_POST['id_pagamento'];
    $id_encomenda = rand(100000, 999999); // Gera um número aleatório de 6 dígitos

    // Inserir a encomenda no banco de dados
    $query_insert_encomenda = "
        INSERT INTO encomendas (id, id_pagamento, nome_completo, email, telemovel, morada, codigo_postal, cidade, total, id_cliente, data_compra)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ";
    $stmt = $conexao->prepare($query_insert_encomenda);
    $stmt->bind_param("iissssssdi", $id_encomenda, $id_pagamento, $nome, $email, $telemovel, $morada, $codigo_postal, $cidade, $total_geral, $user_id);
    if (!$stmt->execute()) {
        die("❌ Erro ao inserir encomenda: " . $stmt->error);
    }

     // Associar os itens do carrinho à encomenda
     $query_itens_encomenda = "
     INSERT INTO itens_encomenda (id_encomenda, id_produto, quantidade, preco_unitario)
     SELECT ?, c.id_Produto, c.quantidade, p.Preco
     FROM carrinho c
     INNER JOIN produtos p ON c.id_Produto = p.id
     WHERE c.id_Cliente = ?
 ";
 $stmt_itens = $conexao->prepare($query_itens_encomenda);
 $stmt_itens->bind_param("ii", $id_encomenda, $user_id);

 if (!$stmt_itens->execute()) {
     die("❌ Erro ao inserir itens da encomenda: " . $stmt_itens->error);
 }

    // Gerar a fatura (PDF)
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->Image('../logo/Logo.png', 10, 10, 40); // Caminho da imagem
    $pdf->Ln(30);

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Fatura de Compra', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Definir a fonte para o corpo do texto
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'ID da Encomenda: ' . $id_encomenda, 0, 1);
    $pdf->Cell(0, 10, 'Nome: ' . $nome, 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $email, 0, 1);
    $pdf->Cell(0, 10, 'Telefone: ' . $telemovel, 0, 1);
    $pdf->Cell(0, 10, 'Morada: ' . $morada, 0, 1);
    $pdf->Cell(0, 10, 'Código Postal: ' . $codigo_postal, 0, 1);
    $pdf->Cell(0, 10, 'Cidade: ' . $cidade, 0, 1);

    // Obtendo a data da compra
    $query_data_encomenda = "SELECT data_compra FROM encomendas WHERE id = ?";
    $stmt_data = $conexao->prepare($query_data_encomenda);
    $stmt_data->bind_param("i", $id_encomenda);
    $stmt_data->execute();
    $result_data = $stmt_data->get_result();
    $data_compra = $result_data->fetch_assoc()['data_compra'];

    $data_compra_formatada = date('d/m/Y H:i:s', strtotime($data_compra));
    $pdf->Cell(0, 10, 'Data da Compra: ' . $data_compra_formatada, 0, 1);
    $pdf->Ln(10);

    // Detalhes dos produtos
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Itens da Encomenda:', 0, 1);
    $pdf->Ln(5);

    // Cabeçalho para a lista de produtos
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(80, 10, 'Produto', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Quantidade', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Preço Unitário', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Total', 1, 1, 'C');
    $pdf->SetFont('Arial', '', 12);

    // Iterar sobre os itens e adicionar no PDF
    foreach ($itens_carrinho as $item) {
        $pdf->Cell(80, 10, $item['Nome'], 1);
        $pdf->Cell(30, 10, $item['quantidade'], 1, 0, 'C');
        $pdf->Cell(40, 10, number_format($item['Preco'], 2, ',', '.') . '€', 1, 0, 'C');
        $pdf->Cell(40, 10, number_format($item['total'], 2, ',', '.') . '€', 1, 1, 'C');
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 10, 'Total Geral:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($total_geral, 2, ',', '.') . '€', 0, 1, 'C');

    $fatura_nome = 'fatura_' . $id_encomenda . '.pdf';
    $pdf->Output('F', '../faturas/' . $fatura_nome);

    // Atualizar banco com o nome do arquivo
    $query_atualizar_fatura = "
        UPDATE encomendas
        SET fatura = ?
        WHERE id = ?
    ";
    $stmt_atualizar_fatura = $conexao->prepare($query_atualizar_fatura);
    $stmt_atualizar_fatura->bind_param("si", $fatura_nome, $id_encomenda);
    $stmt_atualizar_fatura->execute();

    $mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gonvi3908@gmail.com'; // Substitua pelo seu email
    $mail->Password = 'xftf jhrw pvpl ndpz'; // Substitua pela senha de aplicativo
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('gonvi3908@gmail.com', 'GONVI');
    $mail->addAddress($email, $nome); // Email e nome do cliente
    $mail->Subject = 'Fatura de Compra';
    $mail->Body = "Olá $nome,\n\nObrigado pela sua compra! Anexamos sua fatura em PDF.";
    $mail->addAttachment("../faturas/$fatura_nome");

    $mail->send();
    echo '✅ Encomenda realizada com sucesso e fatura enviada por e-mail!';
} catch (Exception $e) {
    echo "❌ Erro ao enviar o e-mail: {$mail->ErrorInfo}";
}

    // Limpar o carrinho
    $query_limpar_carrinho = "DELETE FROM carrinho WHERE id_Cliente = ?";
    $stmt_limpar = $conexao->prepare($query_limpar_carrinho);
    $stmt_limpar->bind_param("i", $user_id);
    $stmt_limpar->execute();

    header("Refresh:2; url=../paginaInicial/PaginaInicial.php");
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="finalizarCompra.css">
    <link rel="stylesheet" href="../paginaInicial/PaginaInicial.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <!-- Conteúdo principal -->
    <div class="checkout-container">

        <h2>Finalizar Compra</h2>
        
        <!-- Dados do cliente -->
        <section class="checkout-section">
            <h3>Dados do Cliente</h3>
            <form action="FinalizarCompra.php" method="POST">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Ex: joao@email.com" required>

                <label for="telemovel">Telemóvel:</label>
                <input type="tel" id="telemovel" name="telemovel" placeholder="Ex: 912345678" required>

                <label for="morada">Morada:</label>
                <input type="text" id="morada" name="morada" placeholder="Ex: Rua das Flores, 123" required>

                <label for="codigo-postal">Código Postal:</label>
                <input type="text" id="codigo-postal" name="codigo_postal" placeholder="Ex: 1000-001" required>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" placeholder="Ex: Lisboa" required>

                <!-- Formas de pagamento -->
                <section class="checkout-section">
                    <h3>Forma de Pagamento</h3>
                    <div>
                        <input type="radio" id="mbway" name="id_pagamento" value="1" required>
                        <label for="mbway">MB Way</label>
                    </div>
                    <div>
                        <input type="radio" id="cartao" name="id_pagamento" value="2">
                        <label for="cartao">Cartão de Crédito/Débito</label>
                    </div>
                    <div>
                        <input type="radio" id="transferencia" name="id_pagamento" value="3">
                        <label for="transferencia">Transferência Bancária</label>
                    </div>
                </section>

                <!-- Botão de finalizar compra -->
                <button type="submit" class="finalizar-btn">Confirmar Compra</button>
            </form>
        </section>

        <!-- Resumo do pedido -->
        <section class="checkout-section">
            <h3>Resumo do Pedido</h3>
            <div class="order-summary">
                <?php foreach ($itens_carrinho as $item): ?>
                    <div class="order-item">
                        <img src="../produtos/<?php echo htmlspecialchars($item['Imagem']); ?>" alt="<?php echo htmlspecialchars($item['Nome']); ?>" style="width: 50px;">
                        <p><?php echo htmlspecialchars($item['Nome']); ?></p>
                        <span><?php echo number_format($item['Preco'], 2, ',', '.'); ?>€ x <?php echo $item['quantidade']; ?></span>
                        <p>Total: <?php echo number_format($item['total'], 2, ',', '.'); ?>€</p>
                    </div>
                <?php endforeach; ?>
                <div class="order-total">
                    <p>Total Geral:</p>
                    <span><?php echo number_format($total_geral, 2, ',', '.'); ?>€</span>
                </div>
            </div>
        </section>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
