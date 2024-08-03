<?php
namespace Entity;
use Core\Entity;
class ArticleEntity extends Entity{
    public function setArticle($id,$libelle,$quantitevendu,$pu,$qt_stock) {
        $this->libelle=$libelle;
        $this->pu=$pu;
        $this->quantitevendu=$quantitevendu;
        $this->id=$id;
        $this->qt_stock=$qt_stock;
    }
    private $id;
    private $libelle;
    private $pu;
    private $qt_stock;
    private $created_at;
    private $updated_at;
    private $quantitevendu;
    private $prixdevente;
    private $idarticle;
    private $iddette;

}