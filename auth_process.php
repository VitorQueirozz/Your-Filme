<?php 

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conexao, $BASE_URL);

    // Resgata o tipo do formulário, se é register ou login
    $type = filter_input(INPUT_POST, "type");

    // Verificação do tipo de formulário
    if($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // Verificação de dados minimos que tem que receber
        if($name && $lastname && $email && $password) {

            //Verificar se as senha são iguais
            if($password === $confirmpassword) {

                if($userDao->findByEmail($email) === false) {

                    $user = new User();

                    // Criação de token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);

                } else {
                    //Enivar msg de erro, email ja existe
                    $message->setMessage("Email já cadastrado, tente outro email.", "error", "back");
                }

            } else {

                //Enivar msg de erro, senhas não batem
                $message->setMessage("As senha não são iguais.", "error", "back");
            }

        } else {

            // Mensagem de erro, faltam dados
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }

    } else if ($type === "login") {


        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");

        //Tenta autenticar usuario
        if($userDao->authenticateUser($email, $password)) {

            $message->setMessage("Seja Bem-vindo!", "success", "editprofile.php");

        //Redireciona o usuario, caso não conseguir autenticar
        } else {

            $message->setMessage("Usuário e/ou senha incorretos.", "error", "back");

        }
    } else {

        $message->setMessage("Informações inválidas!.", "error", "index.php");

    }

?>