<?php 

    require_once("models/User.php");
    require_once("models/Message.php");

    class UserDAO implements UserDAOInterface {

        private $conexao;
        private $url;
        private $message;

        //Conexao com banco de dados
        public function __construct(PDO $conexao, $url) {
            $this->conexao = $conexao; //Chama a conexao do Banco
            $this->url = $url; //Chama a url do sistema $BASE_URL
            $this->message = new Message($url);
        }

        public function buildUser($data){

            $user = new User();

            $user->id = $data['id'];
            $user->name = $data['name'];
            $user->lastname = $data['lastname'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->image = $data['image'];
            $user->bio = $data['bio'];
            $user->token = $data['token'];

            return $user;
        }

        //Cria o usuario e faz o login
        public function create(User $user, $authUser = false){

            $stmt = $this->conexao->prepare("INSERT INTO user(name, lastname, email, password, token) VALUES(:name, :lastname, :email, :password, :token)");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":token", $user->token);

            $stmt->execute();
        
            // Autenticar usuario, caso auth seja true
            if($authUser) {
                $this->setTokenToSession($user->token);
            }
        }

        //Atualizar o usuario
        public function update(User $user, $redirect = true){

            $stmt = $this->conexao->prepare("UPDATE user SET
                name = :name,
                lastname = :lastname,
                email = :email,
                image = :image,
                bio = :bio,
                token = :token
                WHERE id = :id
            ");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":image", $user->image);
            $stmt->bindParam(":bio", $user->bio);
            $stmt->bindParam(":token", $user->token);
            $stmt->bindParam(":id", $user->id);

            $stmt->execute();

            if($redirect) {

                //Redireciona para o perfil do usuario
                $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
            }

        }

        //Verifica caso usuario não esteja logado
        public function verifyToken($protected = false){

            if(!empty($_SESSION["token"])) {
                
                //Pega o token da session
                $token = $_SESSION["token"];

                $user = $this->findByToken($token);

                if($user) {
                    return $user;
                } else if($protected) {

                     //Redireciona usuario não autenticado
                     $this->message->setMessage("Faça a autenticação para acessar está página", "success", "index.php");
                }

            } else if($protected) {

                //Redireciona usuario não autenticado
                $this->message->setMessage("Faça a autenticação para acessar está página", "success", "index.php");
           }
        }

        //redireciona usario pra pagina especifica e autenticação completa
        public function setTokenToSession($token, $redirect = true){

            //Salva token na session
            $_SESSION["token"] = $token;

            if($redirect) {

                //Redireciona para o perfil do usuario
                $this->message->setMessage("Seja Bem-vindo!", "success", "editprofile.php");
            }

        }
        public function authenticateUser($email, $password){

            $user = $this->findByEmail($email);

            if($user) {

                //Checar se as senhas batem
                if(password_verify($password, $user->password)) {

                    // Gerar um token e inserir na session
                    $token = $user->generateToken();

                    $this->setTokenToSession($token, false);

                    // Atualizar token no usuario
                    $user->token = $token;

                    $this->update($user, false);

                    return true;

                } else {
                    return false;
                }
            } else {

                return false;
                
            }
        }

        //Procurar por usuario pelo: Email, Id, Token
        public function findByEmail($email){

            if($email != "") {

                $stmt = $this->conexao->prepare("SELECT * FROM user WHERE email = :email");

                $stmt->bindParam(":email", $email);

                $stmt->execute();

                if($stmt->rowCount() > 0) {

                    $data = $stmt->fetch();
                    $user = $this->buildUser($data);

                    return $user;
                } else {
                    return false;
                }

            } else {
                return false;
            }

        }
        public function findById($id){

            if($id != "") {

                $stmt = $this->conexao->prepare("SELECT * FROM user WHERE id = :id");

                $stmt->bindParam(":id", $id);

                $stmt->execute();

                if($stmt->rowCount() > 0) {

                    $data = $stmt->fetch();
                    $user = $this->buildUser($data);

                    return $user;
                } else {
                    return false;
                }

            } else {
                return false;
            }

        }
        public function findByToken($token){

            if($token != "") {

                $stmt = $this->conexao->prepare("SELECT * FROM user WHERE token = :token");

                $stmt->bindParam(":token", $token);

                $stmt->execute();

                if($stmt->rowCount() > 0) {

                    $data = $stmt->fetch();
                    $user = $this->buildUser($data);

                    return $user;
                } else {
                    return false;
                }

            } else {
                return false;
            }

        }

        public function destroyToken() {

            // Remove o token da sessão
            $_SESSION["token"] = "";

            // Redireciona e apresenta a msg de sucesso
            $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
        }
        
        //Mudar senha
        public function changePassword(User $user){

            $stmt = $this->conexao->prepare("UPDATE user SET password = :password WHERE id = :id");

            $stmt->bindParam("password", $user->password);
            $stmt->bindParam("id", $user->id);

            $stmt->execute();

            // Redireciona e apresenta a msg de sucesso
            $this->message->setMessage("Senha altera com sucesso!", "success", "editprofile.php");
        }
    }
?>