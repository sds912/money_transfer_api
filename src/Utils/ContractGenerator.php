<?php 


namespace App\Utils;

use App\Entity\Partner;
use App\Entity\PartnerAccount;
use App\Entity\User;
use Twig\Environment;

class  ContractGenerator 
{
    private $twig;
    private  $mailer;


    public function __construct(\Swift_Mailer $mailer, Environment  $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function generate(Partner $partner, PartnerAccount $account, User $creator)
    {
        $message = (new \Swift_Message("Contrat d'ouverture de compte partenaire"))
        ->setFrom('senghor.pape912@hotmail.com')
        ->setTo($partner->getUser()->getEmail())
        ->setBody(
            $this->twig->render('contract.html.twig',[
                'partner' => $partner,
                'account' => $account,
                'creator' => $creator
            ])
        )
        ->setContentType('text/html');

        $this->mailer->send($message);
       
    }
}