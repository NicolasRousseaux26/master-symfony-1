<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        // On crée un formulaire avec deux paramètres: la classe du formulaire et l'objet à ajouter dans la BDD
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter le produit en BDD...
            $entityManager = $this->getDoctrine()->getManager();
            // On met l'objet en attente
            $entityManager->persist($product);
            // Exécute la requête (INSERT...)
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté.');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id)
    {
        // On récupère le dépôt qui contient nos produits
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        // SELECT * FROM product WHERE id = $id
        $product = $productRepository->find($id);

        // Si le produit n'existe pas en BDD
        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
