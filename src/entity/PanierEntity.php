<?php
namespace Entity;
use Core\Entity;

class PanierEntity extends Entity{
    private ClientEntity $client;
    private array $articles = [];
    private int $idvendeur = 1;

    public function __construct(ClientEntity $client) {
        $this->client = $client;
    }

    public function ajouterArticle(ArticleEntity $article) {
        $this->articles[] = $article;
    }

    public function getClient(): ClientEntity {
        return $this->client;
    }

    public function getArticles(): array {
        return $this->articles;
    }

    public function getIdVendeur(): int {
        return $this->idvendeur;
    }
   
    
}