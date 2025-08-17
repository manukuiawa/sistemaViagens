<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_viagem'])) {
    $id = (int) $_POST['id_viagem'];

    // Busca a viagem no banco
    $stmt = $pdo->prepare("SELECT confirmados, desistencias FROM viagens WHERE id = ?");
    $stmt->execute([$id]);
    $viagem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($viagem) {
        $desistencias = (int) $viagem['desistencias'];

        // Marca como fechada e atualiza desistencias (confirmados fica intacto)
        $update = $pdo->prepare("
            UPDATE viagens 
            SET fechada = 1, 
                desistencias = ? 
            WHERE id = ?
        ");
        $update->execute([$desistencias, $id]);
    }
}

// Redireciona pra lista de viagens fechadas
header('Location: viagens_fechadas.php');
exit;
?>
