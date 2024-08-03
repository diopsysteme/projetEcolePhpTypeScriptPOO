<?php

namespace Core;

class Controller
{
    protected Session $session;
    //Session 
    //layout 
    //validator 
    protected ?File $file;
    protected ?Authorize $authorize;
    protected $template="template" ;
    protected $validator;

    public function __construct(Session $session,$validator,$file,$authorize) {
        $this->session = $session;
        $this->session::start();
        $this->validator = $validator;
        $this->file = $file;
        $this->authorize=$authorize;
        $this->authorize->setSesion($this->session);
    }
    
    public function renderView($view, $data = [],$layout = null)
    {
        if ($layout) {
            $this->template = $layout;
        }
        if (count($data)) {
            extract($data);
        }

        ob_start();
        // var_dump($_ENV['VIEW'] . "{$view}.html.php");
        require_once $_ENV['VIEW'] . "{$view}.html.php";
        $content = ob_get_clean();
        require_once $_ENV['VIEW'] . "{$this->template}.html.php";
    }
    protected function articlesPanier(){
        $articls=$this->session::get("articles");
            $articles=[];
            foreach($articls as $dd){
                $articlee = "\Entity\ArticleEntity";
                $articlee = new \ReflectionClass($articlee);
                $art = $articlee->newInstance();
                $art->unserialize($dd);   
                $articles[]=$art->unserialize($dd);;
            }
            return $articles;
    }
    protected function redirect($url, $statusCode = 302)
    {
        header("Location: " . $url, true, $statusCode);
        exit();
    }
    //renderJson
    //fromArray
}
