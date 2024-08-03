<?php

namespace Controller;

use App\App;
use Core\Controller;
use Core\Session;
use Core\Validator2;
use Core\File;
use Core\Authorize;
use Model\UserModel;

class SecuriteController extends Controller
{
    private ?UserModel $securityModel;

    public function __construct(Session $session, Validator2 $validator, File $file,Authorize $authorize)
    {
        parent::__construct($session, $validator, $file,$authorize);
        $this->securityModel = App::getInstance()->getModel("User");
    }

    public function login()
    {

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $user = $this->securityModel->conn($username, $password);
        if ($user) {
            $this->authorize->saveUser($user) ;
        if($user->type=="etudiant")
            $this->redirect("/etudiant/cours");
        elseif($user->type=="professeur")
            $this->redirect("/list/session2");
        elseif($user->type=="charger")
        $this->redirect("/charger/acceuil");
        } else {
        $error=[
            "lm"=>"login et ou mot de passe incorrects"
        ];
    }
    $this->renderView('login/login', ['error' => $error],'neant');
    }

    public function showLogin(){
        $this->renderView('login/login', [],'neant');
    }
    public function logout()
    {
        Session::destroy();
        $this->redirect("/login");
       
    }
}
