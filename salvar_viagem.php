<?php 
  require 'conexao.php'; 

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $destino = $_POST['destino']; 
    $data_viagem = $_POST['data_viagem'];

    try {
      $sql = "INSERT INTO viagens (destino, data_viagem) VALUES (?, ?)"; 
      $stmt = $pdo->prepare($sql); 
      $stmt->execute([$destino, $data_viagem]);

      header("Location: cadastrar_viagem.php?status=sucesso"); 
      exit;
    } catch (PDOException $e) {
      echo "Erro ao salvar: " . $e->getMessage(); 
  }
}
?>
