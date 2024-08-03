<?php

namespace Model;

use Core\MysqlDatabase;
use Entity\ClientEntity;
use Core\Model;
class ClientModel extends Model
{
    protected $table = 'clients';

   
   

    public  function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id";
        $params = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'id' => $id
        ];
        return $this->query($sql, $params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->query($sql, ['id' => $id]);
    }
    public function infosClientDebt($number) {
        $sql = "
            SELECT 
                c.id,
                c.nom,
                c.prenom,
                c.mail,
                c.telephone,
                c.photo,
                SUM(d.montant) AS totalDette,
                SUM(p.montantverse) AS montantVerse
            FROM 
                clients c
            LEFT JOIN 
                dettes d ON d.idclient = c.id
            LEFT JOIN 
                paiements p ON p.iddette = d.id
            WHERE 
                c.telephone = :number
            GROUP BY 
                c.id, c.nom, c.prenom, c.mail, c.telephone
        ";
        return $this->query($sql, ['number' => $number], ClientEntity::class);
    }
    

}
