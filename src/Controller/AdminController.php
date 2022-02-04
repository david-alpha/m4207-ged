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
	#[Route('/connexion', name: 'connexion')]
    public function connexion(Request $request, ManagerRegistry $doctrine): Response
    {
		//Récupération variables de formulaire
		$email = $request->request->get('email');
		$pwd = $request->request->get('pwd');
		dump($email,$pwd);
		$ok=0;
		//lien avec la base de données
		if($user= $doctrine->getRepository(User::class)->findOneByEmail($email)){
		if($user->getPwd() == $pwd){
			$ok=1;
			dump($ok);
			return $this->render('admin/dashboard.html.twig');
		}else{
			return $this->redirectToRoute('login');
		}
		}else{
			$ok=1;
			dump($ok);
			return $this->redirectToRoute('login');
		}
			
		
		
        
    }
}
