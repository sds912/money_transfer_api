<?php


namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SuperAdminController extends Controller{


    /** @var UsersRepository $userRepository */
    private $usersRepository;


     /**
     * AuthController Constructor
     *
     * @param UsersRepository $usersRepository
     */
    
    public function __construct(UserRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }


   public function addNewUserSystem(){

   

    }

   
}