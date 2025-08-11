<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_viagem'])) {
    $id = (int) $_POST['id_viagem'];

    // Busca a viagem no banco
    $stmt = $pdo->prepare("SELECT confirmados, desistencias FROM viagens WHERE id = ?");
    $stmt->execute([$id]);
    $viagem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($viagem) {
        $confirmados = (int) $viagem['confirmados'];
        $desistencias = (int) $viagem['desistencias'];

        // Calcula passageiros finais (não deixa negativo)
        $passageiros_fechamento = max(0, $confirmados - $desistencias);

        // Marca como fechada e salva os dados finais
        $update = $pdo->prepare("
            UPDATE viagens 
            SET fechada = 1, 
                passageiros_fechamento = ?, 
                desistencias = ? 
            WHERE id = ?
        ");
        $update->execute([$passageiros_fechamento, $desistencias, $id]);
    }
}

// Redireciona pra lista de fechadas
header('Location: viagens_fechadas.php');
exit;
