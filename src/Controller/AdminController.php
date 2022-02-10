<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
	#[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('admin/login.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
	#[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
		//on détruit toute trace de la session
		$session = $request->getSession();
		$session->clear();
		//on redirige vers le login
        return $this->redirectToRoute('login');
    }
	#[Route('/connexion', name: 'connexion')]
    public function connexion(Request $request, ManagerRegistry $doctrine): Response
    {
		//Récupération variables de formulaire
		//ne pas oublier le use en haut de fichier
		$email = $request->request->get('email');
		$pwd = $request->request->get('pwd');
		dump($email,$pwd);
		$ok=0;
		//lien avec la base de données
		if($user= $doctrine->getRepository(User::class)->findOneByEmail($email)){
			if($user->getPwd() == $pwd){
				$ok=1;
				dump($ok);
				//on démarre la session
				//ne pas oublier le use en haut de fichier
				$session = new Session();
				// set session attributes
				$session->set('nameUser', $user->getNom());
				$session->set('roleUser', $user->getRole());
				dump($session->get('nameUser'),$session->get('roleUser'));
				return $this->render('admin/dashboard.html.twig');
			}else{
				return $this->redirectToRoute('login');
			}
		}else{
			$ok=0;
			dump($ok);
			return $this->redirectToRoute('login');
		}
			
		
		
        
    }
}
