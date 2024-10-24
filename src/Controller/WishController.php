<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wishes', name: 'app_wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list',methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
        //Récupère les wishs qui sont publiés, et trie les du plus récent au plus ancien
        //ON PENSE OBJET §§§§§§§§§
        $wishes = $wishRepository->findBy(['published' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig',[
            'wishes' => $wishes
        ]);
    }

    #[Route('/{id}', name: 'detail',requirements:['id'=>'\d+'],methods: ['GET'])]
    public function detail(int $id,WishRepository $wishRepository): Response
    {
        //Récupère le wish en fonction de l'id présent dans l'URL
        $wish = $wishRepository->find($id);
        //Je teste pour voir si le wish est présent dans la BD sinon je déclenche une erreur 404
        if(!$wish){
            throw $this->createNotFoundException('Wish not found! Sorry !');
        }
        return $this->render('wish/detail.html.twig',
            ['wish' => $wish]);
    }
}
