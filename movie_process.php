<?php 

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Movie.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");

    $message = new Message($BASE_URL);
    $userDao= new UserDAO($conexao, $BASE_URL);
    $movieDao= new MovieDAO($conexao, $BASE_URL);

    // Resgata o tipo do formulário, se é register ou login
    $type = filter_input(INPUT_POST, "type");

    // Resgata dados do usuario
    $userData = $userDao->verifyToken();

    if($type === "create") {

        // Receber od dados dos inputs
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        $movie = new Movie();

        // Validação minima de dados
        if(!empty($title) && !empty($description) && !empty($category)) {

            $movie->title = $title;
            $movie->description = $description;
            $movie->trailer = $trailer;
            $movie->category = $category;
            $movie->length = $length;
            $movie->users_id = $userData->id;

            // Upload de imagem do filme
            if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                $image = $_FILES["image"];
                $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                $jpgArray = ["image/jpeg", "image/jpg"];

                //Checando tipo da imagem
                if(in_array($image["type"], $imageTypes)) {

                    // Checa se imagem jpg
                    if(in_array($image["type"], $jpgArray)) {

                        $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                    // Checa se imagem png
                    } else {
                        $imageFile = imagecreatefrompng($image["tmp_name"]);
                    }

                    //Gerando o nome da imagem
                    $imageName = $movie->imageGenerateName();

                    imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                    $movie->image = $imageName;

                } else {

                    $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

                }

            }


            $movieDao->create($movie);

        } else {

            $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");

        }

    } else if ($type === 'delete') {

        // Recebe od dados form
        $id = filter_input(INPUT_POST, "id");

        $movie = $movieDao->findById($id);

        if($movie) {

            if($movie->users_id === $userData->id) {

                $movieDao->destroy($movie->id);

            } else {

                $message->setMessage("Informações inválidas!.", "error", "index.php");

            }

        } else {

            $message->setMessage("Informações inválidas!.", "error", "index.php");

        }

    } else if ($type === "update") {

        // Receber od dados dos inputs
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");
        $id = filter_input(INPUT_POST, "id");

        $movieData = $movieDao->findById($id);

        // Verifica se encontrou o filme
        if($movieData) {

            if($movieData->users_id === $userData->id) {

                // Validação minima de dados
                if(!empty($title) && !empty($description) && !empty($category)) {
                
                    // Edição do filme
                    $movieData->title = $title;
                    $movieData->description = $description;
                    $movieData->trailer = $trailer;
                    $movieData->category = $category;
                    $movieData->length = $length;

                     // Upload de imagem do filme
                    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                        $image = $_FILES["image"];
                        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                        $jpgArray = ["image/jpeg", "image/jpg"];

                        //Checando tipo da imagem
                        if(in_array($image["type"], $imageTypes)) {

                            // Checa se imagem jpg
                            if(in_array($image["type"], $jpgArray)) {

                                $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                            // Checa se imagem png
                            } else {
                                $imageFile = imagecreatefrompng($image["tmp_name"]);
                            }

                            $movie = new Movie();

                            //Gerando o nome da imagem
                            $imageName = $movie->imageGenerateName();

                            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                            $movieData->image = $imageName;

                        } else {

                            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

                        }

                    }

                    $movieDao->update($movieData);

                } else {

                    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");

                }

            } else {

                $message->setMessage("Informações inválidas!.", "error", "index.php");

            }

        } else {

            $message->setMessage("Informações inválidas!.", "error", "index.php");

        }

    } else {

        $message->setMessage("Informações inválidas!.", "error", "index.php");

    }
?>