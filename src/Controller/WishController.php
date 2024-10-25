<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/create', name: 'create',methods: ['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        //Création de l'objet Wish.
        $wish = new Wish();
        //Création du formulaire et on l'associe à notre objet wish.
        $wishForm = $this->createForm(WishType::class, $wish);
        //Récupére les données du form et on les injecte dans le wish
        $wishForm->handleRequest($request);
        //On teste si le formulaire est soumis et s'il est valide
        if($wishForm->isSubmitted() && $wishForm->isValid()){
            //hydrate les propriétés absentes du formulaire
            $wish->setPublished(true);
            //Sauvegarde en BDD
            $em->persist($wish);
            $em->flush();
            //Afficher un message sur la prochaine page
            $this->addFlash('success',"Idea successfully added!");
            //Redirige vers la page de details de l'idée fraichement créée.
            return $this->redirectToRoute('app_wish_detail',['id'=>$wish->getId()]);
        }
        //Affiche le formulaire
        return $this->render('wish/create.html.twig',
            ['wishForm' => $wishForm]);
    }

    #[Route('/{id}/update', name: 'update',requirements:['id'=>'\d+'],methods: ['GET','POST'])]
    public function update(int $id, WishRepository $wishRepository, Request $request, EntityManagerInterface $em): Response
    {
        //Récupération du wish à modifier en fonction de son id présent dans l'url.
        $wish = $wishRepository->find($id);
        if(!$wish){
            throw $this->createNotFoundException('Wish not found! Sorry !');
        }
        //formulaire et on l'associe à notre objet wish.
        $wishForm = $this->createForm(WishType::class, $wish);
        //Récupére les données du form et on les injecte dans le wish
        $wishForm->handleRequest($request);
        //On teste si le formulaire est soumis et s'il est valide
        if($wishForm->isSubmitted() && $wishForm->isValid()){
            //hydrate les propriétés absentes du formulaire
            $wish->setDateUpdated(new \DateTimeImmutable());
            //Sauvegarde en BDD
            $em->flush();
            //Afficher un message sur la prochaine page
            $this->addFlash('success',"Idea successfully updated!");
            //Redirige vers la page de details de l'idée fraichement créée.
            return $this->redirectToRoute('app_wish_detail',['id'=>$wish->getId()]);
        }
        //Affiche le formulaire
        return $this->render('wish/create.html.twig',
            ['wishForm' => $wishForm]);
    }

    #[Route('/{id}/delete', name: 'delete',requirements:['id'=>'\d+'],methods: ['GET'])]
   public function delete(int $id, WishRepository $wishRepository,Request $request, EntityManagerInterface $em): Response{
        $wish = $wishRepository->find($id);
    //public function delete(Wish $wish, WishRepository $wishRepository,Request $request, EntityManagerInterface $em): Response{
        if(!$wish){
            throw $this->createNotFoundException('Wish not found! Sorry !');
        }
        dump($request);
        if($this->isCsrfTokenValid('delete'.$wish->getId(), $request->get('token'))){
            $em->remove($wish);
            $em->flush();
            $this->addFlash('success',"Idea successfully deleted!");
        }else{
            $this->addFlash('danger',"Sorry we are not allowed to delete!");
        }
        //On redirige vers la page des listes.
        return $this->redirectToRoute('app_wish_list');
    }
}
