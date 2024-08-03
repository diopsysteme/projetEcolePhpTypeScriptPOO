<?php
 namespace Core;
 use App\App;
use Core\Controller;
use Entity\ClientEntity;
use Core\Validator;
  class ErrorController extends Controller{
     public function __construct(Session  $session,Validator $validator,$file)
     {
         parent::__construct($session,$validator,$file);
     }
     public function loadError(int $error){
        http_response_code($error);
         $this->renderView($error);
     }
 }