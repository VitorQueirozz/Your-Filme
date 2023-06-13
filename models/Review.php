<?php 

    class Review {

        public $id;
        public $rating;
        public $review;
        public $users_id;
        public $movies_id;

    }

    interface ReviewDAOInterface {

        // Objeto dela
        public function buildReview($data);

        // Cria o objeto
        public function create(Review $review);

        //Sabe as notas e criticas pelo id
        public function getMoviesReview($id);

        //Saber se o usuario ja fez a revisao daquele filme
        public function hasAlreadyReviewed($id, $userId);

        //Receber todas as notas de um filme
        public function getRatings($id);

    }

?>