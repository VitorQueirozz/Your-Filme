<?php 

    class Movie {
   
        public $id;
        public $title;
        public $description;
        public $image;
        public $trailer;
        public $category;
        public $length;
        public $users_id;

        public function imageGenerateName() {
            return bin2hex(random_bytes(60)) . ".jpg";
        }

    }

    interface MovieDAOInterface {

        // Recebe os dados e faz um Objeto de filme
        public function buildMovie($data);

        // Vai encontar todos os filmes do banco de dados
        public function findAll();

        // Vai pegar todos o filmes em ordem decrescente
        public function getLatesMovies();

        // Pega os filmes de uma determinada categoria
        public function getMoviesByCategory($category);

        // Pega os filmes do usaurio especifico
        public function getMoviesByUserId($id);

        // Vai encontrar o filme pelo id
        public function findById($id);

        // Vai encontrar o filme pelo titulo especifico
        public function findByTitle($title);

        // Cria o filme
        public function create(Movie $movie);

        // Atualiza o filme
        public function update(Movie $movie);

        // Deleta o filme
        public function destroy($id);
    }

?>