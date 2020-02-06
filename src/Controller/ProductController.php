<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, Uploader $uploader)
    {
        $product = new Product();
        // On crée un formulaire avec deux paramètres: la classe du formulaire et l'objet à ajouter dans la BDD
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Je génère le slug à la création du produit
            $product->setSlug($slugger->slug($product->getName())->lower());

            // On fait l'upload...
            /** @var UploadedFile $image */
            if ($image = $form->get('image')->getData()) {
                $fileName = $uploader->upload($image);
                // Met à jour l'entité
                $product->setImage($fileName);
            }

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
     * @Route("/product/{slug}", name="product_show")
     */
    public function show($slug)
    {
        // On récupère le dépôt qui contient nos produits
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        // SELECT * FROM product WHERE slug = $slug
        // $productRepository->findOneBy(['slug' => $slug]);
        /** @var Product $product */
        $product = $productRepository->findOneBySlug($slug);

        // User
        // dump($product->getUser()->getUsername());

        // Si le produit n'existe pas en BDD
        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product", name="product_list")
     */
    public function list(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     */
    public function edit(Request $request, Product $product, Uploader $uploader)
    {
        // Du code...
        $this->denyAccessUnlessGranted('edit', $product);

        // On crée le formulaire avec le produit à modifier
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On fait l'upload...
            /** @var UploadedFile $image */
            if ($image = $form->get('image')->getData()) {
                // Supprimer l'image déjà existante
                if ($product->getImage()) {
                    $uploader->remove($product->getImage());
                }

                $fileName = $uploader->upload($image);
                // Met à jour l'entité
                $product->setImage($fileName);
            }

            // Met à jour l'objet dans la BDD
            $this->getDoctrine()->getManager()->flush();

            // Redirige vers la liste des produits après l'UPDATE
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
           'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager, Uploader $uploader)
    {
        // On vérifie la validité du token CSRF
        // On se protège d'une faille CSRF
        if ($this->isCsrfTokenValid('delete', $request->get('token'))) {
            if ($product->getImage()) {
                $uploader->remove($product->getImage());
            }

            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_list');
    }
}
