<?php

namespace ProductBundle\Controller;

use App\Entity\Image;
use App\Form\CreateProductFormType;
use App\Form\EditProductFormType;
use App\Repository\AccessoryRepository;
use App\Repository\ProductRepository;
use App\Services\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use ProductBundle\Entity\Product;
use ProductBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/product", name="product.")
 */
class ProductController extends Controller
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     */
    public function indexAction($page = 1): Response
    {
        $em = $this->getDoctrine()->getRepository(Product::class);
        $products = $em->findAll();

        return $this->render('@Product/product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Product $product) {

        return $this->render('@Product/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request) {
        //getting the entity manager
        $em = $this->getDoctrine()->getManager();
        $product = new Product();

        //creating the form
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //setting product's values
            $product->setName($form["name"]->getData());
            $product->setDescription($form["description"]->getData());
            $product->setPrice($form["price"]->getData());
            $product->setCode($form["code"]->getData());
            $product->setStock($form["stock"]->getData());
            $product->setAvailability($form["availability"]->getData());

            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product added successfuly');

            return $this->redirect($this->generateUrl('product.index'));
        } else {
            return $this->render('@Product/product/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this-> render('@Product/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction(Request $request, Product $product) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        //checking if the form is submitted and valid
        if($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            $this->addFlash('success', 'Product updated successfuly');

            return $this->redirect($this->generateUrl('product.index'));
        } else {
            return $this->render('@Product/product/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this-> render('@Product/product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Product $product) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        $this->addFlash('success', 'Product deleted');

        return $this->redirect($this->generateUrl('product.index'));
    }
}
