<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><?= $titulo ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="<?= $url_inicio ?>"><?= $url_inicio_name ?></a>
        </li>

        <?php if (!$url_misanuncios_visible) { ?> 
        <li class="nav-item ">
          <a class="nav-link" href="<?= $url_misanuncios ?>"><?= $url_misanuncios_name ?></a>
        </li>
        <?php } ?> 

        <?php if (!$url_nuevoanuncio_visible) { ?> 
        <li class="nav-item ">
          <a class="nav-link" href="<?= $url_nuevoanuncio ?>"><?= $url_nuevoanuncio_name ?></a>
        </li>
        <?php } ?> 

        <li class="nav-item ">
          <a class="nav-link" href="<?= $url_ingresar ?>"><?= $url_ingresar_name ?></a>
        </li>

        <li class="nav-item ">
          <a class="nav-link" href="<?= $url_crearcuenta ?>"><?= $url_crearcuenta_name ?></a>
        </li>    
       
        <?php if (!$micuenta_visible) { ?> 
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $micuenta_name ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $url_configuracion ?>"><?= $url_configuracion_name ?></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= $url_salir ?>"><?= $url_salir_name ?></a></li>
          </ul>
        </li>
        <?php } ?>       
       
      </ul>      
    </div>
  </div>
</nav>