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
use Model\PaiementModel;
use Core\Validator2;
use Core\Session;
class DetteController extends Controller{
    private $detteModel;
    private $clientModel;
    private $paiementModel;
    private $dettearticleModel;
    private $articleModel;
    public function __construct(Session $session, Validator2 $validator, File $file)
    {
        parent::__construct($session,$validator,$file);
        $this->paiementModel = App::getInstance()->getModel("Paiement");
        $this->detteModel = App::getInstance()->getModel("Dette");
        $this->clientModel = App::getInstance()->getModel("Client");
        $this->dettearticleModel = App::getInstance()->getModel("Dettearticle");
        $this->articleModel = App::getInstance()->getModel("Article");
    }
    


    public function listPayments($id){

        // var_dump($id);

        $paiements=$this->detteModel->hasMany(PaiementModel::class,"iddette",$id);
        echo "listing payments";
        // var_dump($paiements);
        
        $this->renderView("paiement/details",["paiement" => $paiements]);
    }
    public function formpayer($id,$data=[]){
        // var_dump($id);
        $clients = $this->session::get("client");
        $entity=$this->clientModel->getEntityClass();
        $entityInstance = \Core\Factory::instantiateClass($entity);
        $entityInstance->unserialize($clients);
        $dette=$this->detteModel->searchByAttribute("id",$id);
        // var_dump($data);
        $this->renderView("dette/paiement",["dette" => $dette[0],"client"=>$entityInstance,"error"=>$data]);
    }
    public function registerDebt() {
        $this->detteModel->transaction(function () {
            $this->createDebt();
        });
        echo "Dette enregistrée avec succès.";
        $this->session::unset("articles");
        $this->redirect("/client");
    }
    
    public function createDebt(){
        $articles = $this->articlesPanier();
        $clients = $this->session::get("client");
        $entity = $this->clientModel->getEntityClass();
        $entityInstance = \Core\Factory::instantiateClass($entity);
        $entityInstance->unserialize($clients);
        $totalDebt=0;
        foreach ($articles as $article) {
            $quantitySold = (int) $article->quantitevendu;
            $unitPrice = (float) $article->pu;
            $totalDebt += $quantitySold * $unitPrice;
        }
        $dette=[
            "montant"=>$totalDebt,
            "idclient"=>$entityInstance->id,
            "montantVerse"=>0,
            "iduserquiafaitlavente"=>1
        ];
        $this->detteModel->save($dette);
        $detteid=$this->detteModel->lastInsertId();
        // quantitevendu | prixdevente | idarticle | iddette
        $articleDette=[];
        foreach ($articles as $article){
            $articleDette=[
                "prixdevente"=>$article->pu,
                "quantitevendu"=>$article->quantitevendu,
                "idarticle"=>$article->id,
                "iddette"=>$detteid
            ];
            $this->dettearticleModel->save($articleDette);
            $article=[
                "id"=>$article->id,
                "qt_stock"=>(int)$article->qt_stock-(int)$article->quantitevendu
            ];
            // var_dump($article);
            // die();
            $this->articleModel->save($article);
        }
    }
    public function payer($id){
        
        // var_dump($_POST);
        $amount=(float)$_POST["amount"];
        $ramount=(float)$_POST["ramount"];
        $amountp=(float)$_POST["amountp"];
        if($amount>$ramount || $ramount-$amount==$ramount){
            if($ramount==0)
            $error="la dette est deja soldée";
            elseif($amount>$ramount)
                $error="le montant verse doit etre inferieur à $ramount franc";
            elseif($ramount-$amount==$ramount)
                $error="le montant verse doit etre supperieur à 0";
        
            $this->formpayer($id,$error);
            return;
        }
        $dette = (object) [
            'montant' => $ramount+$amountp,
            "montantRestant"=>$ramount-$amount,
            "amountPaid"=>$amount+$amountp,
        ];
        
        $paiement = (object) [
            'montant' =>$amount,
            'date' => date('Y-m-d'),
        ];
        $clients = $this->session::get("client");
        $entity = $this->clientModel->getEntityClass();
        $entityInstance = \Core\Factory::instantiateClass($entity);
        $entityInstance->unserialize($clients);
        $pdfPath = $this->file->paymentReceipt($entityInstance, $dette, $paiement);

        $data=["iddette"=>$id,"montantverse"=>$amount];
        $this->paiementModel->save($data);
        $data=["id"=>$id,"montantverse"=>$amount+$amountp];
        $this->detteModel->save($data);
        $this->redirect("/dette/list/$id");

    }
    public function filtrePaginate(){
        if(isset($_POST["filter"]))
        $this->session::set("filtre",$_POST["filter"]);
       

        var_dump($_POST);

        $clients = $this->session::get("client");
        $entity = $this->clientModel->getEntityClass();
        $entityInstance = \Core\Factory::instantiateClass($entity);
        $entityInstance->unserialize($clients);

        $filters = $this->session::get("filtre");

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) :2;
        $offset = ($page - 1) * $pageSize;
        (int)$prev = ($page > 1) ? $page - 1 : 1;
        // var_dump($offset, $pageSize, $prev);
    $call = $this->detteModel->filterAndPaginate($filters, $entityInstance->id, $offset, $pageSize);
    $call2 = $this->detteModel->filterAndPaginate($filters, $entityInstance->id);
    $possible=ceil(count($call2)/$pageSize);
    $suiv = ($page < $possible) ? $page + 1 : $possible;

    $this->renderView('dette/dette', ['clients' => $entityInstance, "dettes" => $call,"prev"=>$prev,"suiv"=> $suiv]);

        
    }
    public function listdette($var)
    {
        // var_dump($var);
        $clients = $this->session::get("client");
        $entity = $this->clientModel->getEntityClass();
        $entityInstance = \Core\Factory::instantiateClass($entity);
        $entityInstance->unserialize($clients);
        if (!$clients) {
            $this->renderView('error');
            return;
        }
        $dettes = $this->clientModel->hasMany(DetteModel::class, "idclient", $entityInstance->id);
        // var_dump($dettes);
        $this->renderView('dette/dette', ['clients' => $entityInstance, "dettes" => $dettes]);
    }
    public function listArticle($id){
        // var_dump($id);
        $articles=$this->detteModel->belongsToMany(ArticleModel::class,"iddette",$id,DettearticleModel::class);
        // var_dump($articles);
        // echo "liste article";
        $this->renderView("dette/details",["articles" => $articles]);
    }
    
}