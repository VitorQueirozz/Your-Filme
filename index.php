<?php 
    require_once("templates/header.php");

    require_once("dao/MovieDAO.php");

    // DAO dos Filmes
    $movieDao = new MovieDAO($conexao, $BASE_URL);

    $latesMovies = $movieDao->getLatesMovies(); 

    $actionMovies = $movieDao->getMoviesByCategory("Ação");
    $comedyMovies = $movieDao->getMoviesByCategory("Comédia");
    $ficcaoMovies = $movieDao->getMoviesByCategory("Fantasia / Ficção");
    $terrorMovies = $movieDao->getMoviesByCategory("Terror");
    $animesMovies = $movieDao->getMoviesByCategory("Animes");
    $romanceMovies = $movieDao->getMoviesByCategory("Romance");
?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title">Filmes novos</h2>
    <h2 class="section-description">Veja as críticas dos últimos filmes adicionador no YourMovie</h2>
    <div class="movies-container">
        <?php foreach($latesMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($latesMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes cadastrados!</p>
        <?php endif; ?>
    </div>
    <h2 class="section-title">Ação</h2>
    <h2 class="section-description">Veja os melhores filmes de ação</h2>
    <div class="movies-container">
        <?php foreach($actionMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($actionMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes de ação cadastrados!</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Comédia</h2>
    <h2 class="section-description">Veja os melhores filmes de comédia</h2>
    <div class="movies-container">
        <?php foreach($comedyMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($comedyMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes de comédia cadastrados!</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Fantasia / Ficção</h2>
    <h2 class="section-description">Veja os melhores filmes de Fantasia / Ficção</h2>
    <div class="movies-container">
        <?php foreach($ficcaoMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($ficcaoMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes de Fantasia / Ficção cadastrados!</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Terror</h2>
    <h2 class="section-description">Veja os melhores filmes de Terror</h2>
    <div class="movies-container">
        <?php foreach($terrorMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($terrorMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes de Terror cadastrados!</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Romance</h2>
    <h2 class="section-description">Veja os melhores filmes de Romance</h2>
    <div class="movies-container">
        <?php foreach($romanceMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($romanceMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes de Romance cadastrados!</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Animes</h2>
    <h2 class="section-description">Veja os melhores filmes de animes</h2>
    <div class="movies-container">
        <?php foreach($animesMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if(count($animesMovies) === 0): ?>
            <p class="empty-list">Ainda não há filmes de Anime cadastrados!</p>
        <?php endif; ?>
    </div>
</div>

<?php 
    include_once("templates/footer.php");
?>

