<?php 

    class User {

        public $id;
        public $name;
        public $lastname;
        public $email;
        public $password;
        public $image;
        public $bio;
        public $token;

        public function getFullName($user) {
            return $user->name . " " . $user->lastname;
        }

        public function generateToken() {
            return bin2hex(random_bytes(50));
        }

        public function generatePassword($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        public function imageGenerateName() {
            return bin2hex(random_bytes(60)) . ".jpg";
        }
    }

    //Métodos que o DAO deve ter
    interface UserDAOInterface  {

        //Construir o Objeto
        public function buildUser($data);

        //Cria o usuario e faz o login
        public function create(User $user, $authUser = false);

        //Atualizar o usuario
        public function update(User $user, $redirect = true);

        //Verifica caso usuario não esteja logado
        public function verifyToken($protected = false);

        //redireciona usario pra pagina especifica e autenticação completa
        public function setTokenToSession($token, $redirect = false);
        public function authenticateUser($email, $password);

        //Procurar por usuario pelo: Email, Id, Token
        public function findByEmail($email);
        public function findById($id);
        public function findByToken($token);

        // Remove o token da sessão
        public function destroyToken();

        //Mudar senha
        public function changePassword(User $user);
    }

?>