<?php
require 'conexao.php';

$sql = "SELECT * FROM viagens WHERE fechada = TRUE ORDER BY data_viagem ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$viagens = $stmt->fetchAll(PDO::FETCH_ASSOC);


$viagens_por_mes = [];

foreach ($viagens as $viagem) {
  preg_match('/(\d{2})\/(\d{2})$/', $viagem['data_viagem'], $matches);
  $mes_num = $matches[2] ?? '00';

  $meses = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
  ];
  $nome_mes = $meses[$mes_num] ?? 'Indefinido';

  $viagens_por_mes[$mes_num]['nome'] = $nome_mes;
  $viagens_por_mes[$mes_num]['viagens'][] = $viagem;
}

ksort($viagens_por_mes);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Viagens Fechadas - Grace Turismo</title>
    <link rel="icon" type="image" href="/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./styles/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="images/logo.png" alt="">
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="cadastrar_viagem.php">Cadastrar Viagens</a></li>
        <li class="nav-item"><a class="nav-link" href="viagens_ativas.php">Viagens Ativas</a></li>
        <li class="nav-item"><a class="nav-link active" href="viagens_fechadas.php">Viagens Fechadas</a></li>
        <li class="nav-item"><a class="nav-link" href="relatorio.php">Relatório</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-5">
  <h1 class="text-center mb-4">Viagens Fechadas</h1>

  <?php if (!empty($viagens_por_mes)): ?>
      <?php foreach ($viagens_por_mes as $mes => $grupo): ?>
          <h2 class="mt-4 mb-3 text-primary texto_mes"><?= htmlspecialchars($grupo['nome']) ?></h2>
          <div class="row">
              <?php foreach ($grupo['viagens'] as $viagem): ?>
                  <div class="col-md-4 mb-4">
                      <div class="card shadow-sm border-primary border-2">
                          <div class="card-body text-center">
                              <h2 class="card-title mb-3"><?= htmlspecialchars($viagem['destino']) ?></h2>
                              <h5 class="card-subtitle mb-2 text-muted">📅 <?= htmlspecialchars($viagem['data_viagem']) ?></h5>

                              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#infoViagem<?= $viagem['id'] ?>">
                                  <i class="fas fa-info-circle"></i> Info
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Modal -->
                  <div class="modal fade" id="infoViagem<?= $viagem['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Informações - <?= htmlspecialchars($viagem['destino']) ?></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <p><strong>Data:</strong> <?= htmlspecialchars($viagem['data_viagem']) ?></p>
                          <p><strong>Passageiros Confirmados:</strong> <?= htmlspecialchars($viagem['confirmados'] ?? 0) ?></p>
                          <p><strong>Desistências:</strong> <?= htmlspecialchars($viagem['desistencias'] ?? 0) ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php endforeach; ?>
          </div>
      <?php endforeach; ?>
  <?php else: ?>
      <p class="text-center">Nenhuma viagem fechada no momento.</p>
  <?php endif; ?>
</main>

<footer class="footer-custom text-light py-3 mt-auto">
  <div class="container d-flex justify-content-between align-items-center flex-wrap">
    <div class="footer-info-top">
      <img class="logo-rodape" src="images/logo.png" alt="logo">
      <span>© 2025 Grace Turismo - Site desenvolvido por Manuella De Fátima Kuiawa</span>
    </div>
    <div>
      <a href="https://www.instagram.com/graceturismo/" target="_blank" class="text-light me-3"><i class="fab fa-instagram"></i></a>
      <a href="https://www.facebook.com/graceturismo/" target="_blank" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
      <a href="https://graceturismo.com.br/" target="_blank" class="text-light"><i class="fas fa-globe"></i></a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
