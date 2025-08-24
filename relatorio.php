<?php 
require 'conexao.php'; // arquivo de conexão

// Contadores gerais
$totalViagens = $pdo->query("SELECT COUNT(*) FROM viagens")->fetchColumn();
$viagensAtivas = $pdo->query("SELECT COUNT(*) FROM viagens WHERE fechada = FALSE")->fetchColumn();
$viagensFechadas = $pdo->query("SELECT COUNT(*) FROM viagens WHERE fechada = TRUE")->fetchColumn();
$totalPassageiros = $pdo->query("SELECT SUM(confirmados) FROM viagens")->fetchColumn();
$totalDesistencias = $pdo->query("SELECT SUM(desistencias) FROM viagens")->fetchColumn();

// Viagens por mês
$viagensPorMes = $pdo->query("
    SELECT MONTH(STR_TO_DATE(data_viagem, '%d/%m/%Y')) as mes, COUNT(*) as total
    FROM viagens
    GROUP BY mes
    ORDER BY mes
")->fetchAll(PDO::FETCH_ASSOC);

// Desistências por mês
$desistenciasPorMes = $pdo->query("
    SELECT MONTH(STR_TO_DATE(data_viagem, '%d/%m/%Y')) as mes, SUM(desistencias) as total
    FROM viagens
    GROUP BY mes
    ORDER BY mes
")->fetchAll(PDO::FETCH_ASSOC);

// Ranking destinos
$rankingDestinos = $pdo->query("
    SELECT destino, COUNT(*) as total
    FROM viagens
    GROUP BY destino
    ORDER BY total DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Média de passageiros por viagem
$mediaPassageiros = $pdo->query("
    SELECT destino, AVG(confirmados) as media
    FROM viagens
    GROUP BY destino
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Relatórios - Grace Turismo</title>
<link rel="icon" type="image/png" href="images/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="./styles/style.css?v=123">
<style>
/* Ícones flutuantes no canto superior direito */
.print-icons {
  position: fixed;
  top: 20px;
  right: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  z-index: 9999;
}

.btn-icon {
  width: 50px;
  display: flex;
  align-items: center;
  gap: 8px;
  background: #0D1B2A;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 10px 12px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: transform 0.2s, background 0.2s;
}

.btn-icon i {
  font-size: 1.4rem;
}

.btn-icon:hover {
  transform: scale(1.05);
  background: #06122a;
}

/* Ajuste para impressão */
@media print {
  body * { visibility: hidden; }
  .print-target, .print-target * { visibility: visible; }
  .print-target { position: absolute; top: 0; left: 0; width: 100%; }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="images/logo.png" alt="">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="cadastrar_viagem.php">Cadastrar Viagens</a></li>
        <li class="nav-item"><a class="nav-link" href="viagens_ativas.php">Viagens Ativas</a></li>
        <li class="nav-item"><a class="nav-link" href="viagens_fechadas.php">Viagens Fechadas</a></li>
        <li class="nav-item"><a class="nav-link active" href="relatorio.php">Relatório</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Ícones de impressão -->
<div class="print-icons">
  <button class="btn-icon" onclick="printTabela()"><i class="fa-solid fa-print"></i></button>
  <button class="btn-icon" onclick="printGraficos()"><i class="fa-solid fa-chart-line"></i></button>
</div>

<main class="container my-5">

  <h1 class="text-center mb-5">Relatórios</h1>

  <!-- Resumo Geral -->
  <div class="row text-center mb-4">
    <div class="col-md-2">
      <div class="card p-3">
        <h5>Total de Viagens</h5>
        <p class="fs-4"><?= $totalViagens ?></p>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card p-3">
        <h5>Viagens Abertas</h5>
        <p class="fs-4"><?= $viagensAtivas ?></p>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card p-3">
        <h5>Viagens Fechadas</h5>
        <p class="fs-4"><?= $viagensFechadas ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h5>Total Passageiros</h5>
        <p class="fs-4"><?= $totalPassageiros ?: 0 ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h5>Total Desistências</h5>
        <p class="fs-4"><?= $totalDesistencias ?: 0 ?></p>
      </div>
    </div>
  </div>

  <!-- Tabela Viagens Ativas -->
  <div class="row mb-5 print-target" id="tabelaViagensAtivas">
    <div class="col-12">
      <div class="card p-3">
        <h5 class="text-center">Viagens Ativas</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Destino</th>
              <th>Data</th>
              <th>Confirmados</th>
              <th>Desistências</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $ativas = $pdo->query("SELECT * FROM viagens WHERE fechada = FALSE")->fetchAll(PDO::FETCH_ASSOC);
            foreach($ativas as $v){
              echo "<tr>
                      <td>{$v['destino']}</td>
                      <td>{$v['data_viagem']}</td>
                      <td>{$v['confirmados']}</td>
                      <td>{$v['desistencias']}</td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Gráficos -->
  <div class="row mb-5 print-target" id="graficosContainer">
    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Viagens por Mês</h5>
        <canvas id="viagensMes"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Desistências por Mês</h5>
        <canvas id="desistenciasMes"></canvas>
      </div>
    </div>
    <div class="col-md-6 mt-4">
      <div class="card p-3">
        <h5 class="text-center">Ranking de Destinos</h5>
        <canvas id="destinosRanking"></canvas>
      </div>
    </div>
    <div class="col-md-6 mt-4">
      <div class="card p-3">
        <h5 class="text-center">Média de Passageiros por Viagem</h5>
        <canvas id="mediaPassageiros"></canvas>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
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

<script>
const meses = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];

const viagensPorMes = <?= json_encode($viagensPorMes) ?>;
const desistenciasPorMes = <?= json_encode($desistenciasPorMes) ?>;
const rankingDestinos = <?= json_encode($rankingDestinos) ?>;
const mediaPassageiros = <?= json_encode($mediaPassageiros) ?>;

// Gráficos
new Chart(document.getElementById('viagensMes'), {
  type: 'bar',
  data: {
    labels: viagensPorMes.map(v => meses[v.mes - 1]),
    datasets: [{ label: 'Viagens', data: viagensPorMes.map(v => v.total), backgroundColor: 'rgba(54, 162, 235, 0.7)' }]
  }
});

new Chart(document.getElementById('desistenciasMes'), {
  type: 'line',
  data: {
    labels: desistenciasPorMes.map(v => meses[v.mes - 1]),
    datasets: [{
      label: 'Desistências',
      data: desistenciasPorMes.map(v => v.total),
      borderColor: 'rgba(255, 99, 132, 1)',
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      fill: true,
      tension: 0.3
    }]
  }
});

new Chart(document.getElementById('destinosRanking'), {
  type: 'bar',
  data: {
    labels: rankingDestinos.map(v => v.destino),
    datasets: [{ label: 'Número de Viagens', data: rankingDestinos.map(v => v.total), backgroundColor: 'rgba(75, 192, 192, 0.7)' }]
  }
});

new Chart(document.getElementById('mediaPassageiros'), {
  type: 'bar',
  data: {
    labels: mediaPassageiros.map(v => v.destino),
    datasets: [{
      label: 'Média de Passageiros',
      data: mediaPassageiros.map(v => parseFloat(v.media)),
      backgroundColor: 'rgba(255, 206, 86, 0.7)'
    }]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    scales: { x: { beginAtZero: true } }
  }
});

// Impressão
function printTabela(){
  document.getElementById('tabelaViagensAtivas').classList.add('print-target');
  document.getElementById('graficosContainer').classList.remove('print-target');
  window.print();
}

function printGraficos(){
  document.getElementById('tabelaViagensAtivas').classList.remove('print-target');
  document.getElementById('graficosContainer').classList.add('print-target');
  window.print();
}
</script>

</body>
</html>
