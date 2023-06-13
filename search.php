<?php 
    require_once("templates/header.php");

    require_once("dao/MovieDAO.php");

    // DAO dos Filmes
    $movieDao = new MovieDAO($conexao, $BASE_URL);

    // Resgata busca do usuario
    $q = filter_input(INPUT_GET, "q");

    $movies = $movieDao->findByTitle($q);
?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title" id="search-title">Voce está buscado por: <span id="search-result"><?= $q ?></span> </h2>
    <h2 class="section-description">Resultados de busca retornados com base na sua pesquisa.</h2>
    <div class="movies-container">
        <?php foreach($movies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($movies) === 0): ?>
            <p class="empty-list">Não há filmes para esta busca, <a class="back-link" href="<?= $BASE_URL ?>">voltar</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php 
    include_once("templates/footer.php");
?>

