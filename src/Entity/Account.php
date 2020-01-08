<?php 

namespace App\Entity;

class Account
{

    private $username;
    private $email;
    private $phone;
    private $address;
    private $numberOfAgencies;
    private $numberOfPartner;
    private $totalBalance;
    private $totalTransaction;
    


    public function __construct()
    {
        
    }

    

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

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
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of numberOfAgencies
     */ 
    public function getNumberOfAgencies()
    {
        return $this->numberOfAgencies;
    }

    /**
     * Set the value of numberOfAgencies
     *
     * @return  self
     */ 
    public function setNumberOfAgencies($numberOfAgencies)
    {
        $this->numberOfAgencies = $numberOfAgencies;

        return $this;
    }

    /**
     * Get the value of numberOfPartner
     */ 
    public function getNumberOfPartner()
    {
        return $this->numberOfPartner;
    }

    /**
     * Set the value of numberOfPartner
     *
     * @return  self
     */ 
    public function setNumberOfPartner($numberOfPartner)
    {
        $this->numberOfPartner = $numberOfPartner;

        return $this;
    }

    /**
     * Get the value of totalBalance
     */ 
    public function getTotalBalance()
    {
        return $this->totalBalance;
    }

    /**
     * Set the value of totalBalance
     *
     * @return  self
     */ 
    public function setTotalBalance($totalBalance)
    {
        $this->totalBalance = $totalBalance;

        return $this;
    }
}
