<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $photo = $form->get('photo')->getData();
            if($photo){
                $nomPhoto = uniqid().'.'.$photo->guessExtension();

                try{
                    $photo->move(
                        $this->getParameter('upload_dir'),
                        $nomPhoto
                    );
                }
                catch(FileException $e){
                    $this->addFlash('error', "Impossible d'uploader l'image");
                    return $this->redirectToRoute('produit_index');
                }

                $produit->setPhoto($nomPhoto);
            }



            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }






    /**
     * @Route("/{id}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {

if($produit != null){

            if($this->getUser() == null){
                $this->addFlash("danger", "Vous devez être connecté");
                return $this->redirectToRoute('app_login');
            }

            $entityManager= $this->getDoctrine()->getManager();

            if($panierRepository->findOneBy(['utilisateur' => $this->getUser(), 'etat' => false ]) == false){

                $panier = new Panier();
                $panier->setUtilisateur($this->getUser());
                $panier->setDateAchat(new \DateTime());
                $panier->setEtat(false);
                $entityManager->persist($panier);
                $entityManager->flush();

            }
            else{
                $panier = $panierRepository->findOneBy(['utilisateur' => $this->getUser(), 'etat' => false]);
            }

            $contenuPanier = new ContenuPanier();
            $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $contenuPanier->setProduit($produit);
                $contenuPanier->setPanier($panier);
                $contenuPanier->setDate(new \DateTime());
                $entityManager->persist($contenuPanier);
                $entityManager->flush(); 
                $this->addFlash("success", "Produit ajouté au panier");
                return $this->redirectToRoute('contenu_panier_index');
            }


        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'ajout_article' => $form->createView(),
        ]);
    }

    else {       
        $this->addFlash("danger","Impossible");

    }
    }  




    
    /**
     * @Route("/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }
}
