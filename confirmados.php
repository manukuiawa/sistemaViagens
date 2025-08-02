<?php
    require 'conexao.php'; 

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_viagem = $_POST['id_viagem'];
        $acao = $_POST['acao'];

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT confirmados FROM viagens WHERE id = ? FOR UPDATE");
        $stmt->execute([$id_viagem]);
        $viagem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($viagem) {
            $confirmados = (int) $viagem["confirmados"];

            if ($acao === 'incrementar') {
                $confirmados++;
            } elseif ($acao === 'decrementar' && $confirmados > 0) {
                $confirmados--;
            }

            $stmt = $pdo->prepare("UPDATE viagens SET confirmados = ? WHERE id = ?");
            $stmt->execute([$confirmados, $id_viagem]);
        }

        $pdo->commit();

    }

        header("Location: viagens_ativas.php");
        exit;

?>