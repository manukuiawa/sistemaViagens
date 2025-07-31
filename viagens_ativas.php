<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Viagens Ativas - Grace Turismo</title>
  <link rel="icon" type="image" href="/images/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="images/logo.png" alt="">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="cadastrar_viagens.php">Cadastrar Viagens</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Viagens Ativas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Viagens Fechadas</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-5">
  <h1 class="text-center mb-4 sticky-title">Viagens Ativas</h1>

  <div class="row">
    <?php
      require 'conexao.php';

      $sql = "SELECT * FROM viagens";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $viagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($viagens as $viagem):
    ?>
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm border-primary border-1">
        <div class="card-body text-center">
          <h2 class="card-title mb-3"><?php echo htmlspecialchars($viagem['destino']); ?></h2>
          <h5 class="card-subtitle mb-2 text-muted">📅 <?php echo htmlspecialchars($viagem['data_viagem']); ?></h5>
          <p class="card-text mb-4">Passageiros Confirmados: <strong><?php echo $viagem['confirmados']; ?></strong></p>

          <!-- Botões lado a lado -->
          <div class="d-flex justify-content-center gap-2 mb-3">
            <!-- Botão Entrou -->
            <form action="atualizar_confirmados.php" method="POST">
              <input type="hidden" name="id_viagem" value="<?php echo $viagem['id']; ?>">
              <button type="submit" name="acao" value="incrementar" class="btn btn-azul-claro btn-sm w-100">
                <i class="fas fa-user-plus me-1"></i>Entrou
              </button>
            </form>

            <!-- Botão Saiu -->
            <form action="atualizar_confirmados.php" method="POST">
              <input type="hidden" name="id_viagem" value="<?php echo $viagem['id']; ?>">
              <button type="submit" name="acao" value="decrementar" class="btn btn-azul-medio btn-sm w-100">
                <i class="fas fa-user-minus me-1"></i>Saiu
              </button>
            </form>
          </div>

          <!-- Botão Fechar Viagem -->
          <form action="fechar_viagem.php" method="POST">
            <input type="hidden" name="id_viagem" value="<?php echo $viagem['id']; ?>">
            <input type="hidden" name="confirmados" value="<?php echo $viagem['confirmados']; ?>">
            <button type="submit" class="btn btn-azul-escuro btn-sm w-100 border-azul-escuro">
              <i class="fas fa-lock me-1"></i>Fechar Viagem
            </button>
          </form>

        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</main>


<footer class="footer-custom text-light py-3 mt-auto">
  <div class="container d-flex justify-content-between align-items-center flex-wrap">
    <div class="footer-info">
      <div class="footer-info-top">
        <img class="logo-rodape" src="images/logo.png" alt="logo">
        <span>© 2025 Grace Turismo - Site desenvolvido por Manuella De Fátima Kuiawa</span>
      </div>
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
