<?php
namespace App\Model;

class User{
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $perfilid;


    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */ 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of senha
     */ 
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */ 
    public function setSenha($senha)
    {
        $this->senha = password_hash($senha,PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Get the value of nome
     */ 


     public function getPerfilId() {
        return $this->perfilid;
    }
 
     public function setPerfilId($perfilid) 
     {
         $this->perfilid = $perfilid;
     }
    

     public function getId()
     {
         return $this->id;
     }
}