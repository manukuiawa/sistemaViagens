<?php
require 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_viagem'])) {
    $id_viagem = (int) $_POST['id_viagem'];

    try {
        // Incrementa 1 na desistência
        $update = $pdo->prepare("UPDATE viagens SET desistencias = desistencias + 1 WHERE id = ?");
        $success = $update->execute([$id_viagem]);

        if ($success) {
            // Pega o novo total
            $stmt = $pdo->prepare("SELECT desistencias FROM viagens WHERE id = ?");
            $stmt->execute([$id_viagem]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'desistencias' => (int) $dados['desistencias']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Falha ao atualizar desistências']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida']);
}
