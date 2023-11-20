<?php

namespace App\Controller;

use App\Model\Model;
use App\Model\Endereco;
class EnderecoController {
    private $db;
    private $endereco;
    public function __construct(Endereco $endereco) {
        $this->db = new Model();
        $this->endereco=$endereco;
    }

    public function insert(){
        
           if($this->db->insert('endereco', [
            'cep'=> $this->endereco->getCep(),
            'rua'=> $this->endereco->getRua(),
            'bairro'=> $this->endereco->getBairro(),
            'cidade'=> $this->endereco->getCidade(),
            'uf'=> $this->endereco->getUf(),
            'iduser'=> $this->endereco->getIduser(),
                                                    ])){                                   
            return true;
        }
        return false;
    }


    public function update($id, $data) {
        $conditions = ['iduser' => $id];
        $updateData = [
            'cep' => $data['cep'],
            'rua' => $data['rua'],
            'bairro' => $data['bairro'],
            'cidade' => $data['cidade'],
            'uf' => $data['uf'],
        ];

        return $this->db->update('endereco', $updateData, $conditions);
    }


    
    public function getUserAddress($id){
        $conditions = ['iduser' => $id];
        $enderecoData = $this->db->select('endereco', $conditions);
    
        if (!empty($enderecoData)) {
            $endereco = new Endereco();
            $endereco->setIduser($enderecoData[0]['iduser']);
            $endereco->setCep($enderecoData[0]['cep']);
            $endereco->setRua($enderecoData[0]['rua']);
            $endereco->setBairro($enderecoData[0]['bairro']);
            $endereco->setCidade($enderecoData[0]['cidade']);
            $endereco->setUf($enderecoData[0]['uf']);
    
            return $endereco;
        } else {
            return false;  
        }
    }

}