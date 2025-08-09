<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Grace Turismo</title>
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
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="cadastrar_viagem.php">Cadastrar Viagens</a></li>
        <li class="nav-item"><a class="nav-link" href="viagens_ativas.php">Viagens Ativas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Viagens Fechadas</a></li>
        <li class="nav-item"><a class="nav-link" href="viagens_fechadas.php">Relatório</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="principal">
  <div id="container">
    <h1>Cadastro de Nova Viagem</h1>

    <!-- Alerta de Cadastro de Viagem Concluído -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'sucesso'): ?>
      <div id="mensagem" class="alert alert-success text-center">
        Viagem cadastrada com sucesso!
      </div>

      <script>
        setTimeout(() => {
          const msg = document.getElementById('mensagem');
          if (msg) {
            msg.style.display = 'none';
          }
        }, 3000);
      </script>
    <?php endif; ?>


    <form action="salvar_viagem.php" method="POST">
      <div class="container-forms">
        <input type="text" name="destino" id="destino" placeholder="Destino (Ex: Urubici)" required>
      </div>
      <div class="container-forms">
        <input type="text" name="data_viagem" id="data" placeholder="Data (Ex: 26/07 a 27/07)" required>
      </div>
      <button type="submit">Cadastrar Viagem</button>
    </form>
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
