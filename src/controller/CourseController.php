<?php
namespace Controller;

use App\App;
use Core\Controller;
use Entity\ClientEntity;
use Core\Validator;
use Core\File;
use Model\DetteModel;
use Model\ArticleModel;
use Model\DettearticleModel;
use Model\CourseModel;
use Model\PaiementModel;
use Core\Validator2;
use Core\Session;
use Core\Authorize;
class CourseController extends Controller{
    private $courseModel;
    private $sessionModel;
    private $userModel;
    private $dettearticleModel;
    private $articleModel;
    public function __construct(Session $session, Validator2 $validator, File $file,Authorize $authorize)
    {
        parent::__construct($session, $validator, $file,$authorize);
        $this->courseModel = App::getInstance()->getModel("Course");
        $this->sessionModel = App::getInstance()->getModel("Session");
        $this->userModel = App::getInstance()->getModel("User");
    }
    

}