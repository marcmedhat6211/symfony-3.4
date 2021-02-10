<?php

namespace ProductBundle\Controller;

use App\Form\EditCategoryFormType;
use ProductBundle\Entity\Category;
use ProductBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category.")
 *
 */
class CategoryController extends Controller
{

    /**
     * @Route("/{page}", name="index", requirements={
                "page"="\d+"
     *     }
     *  )
     */
    public function indexAction($page = 1): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('@Product/category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request) {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category added successfully');
            return $this->redirect($this->generateUrl('category.index'));
        } else {
            return $this->render('@Product/category/create.html.twig', [
                'controller_name' => 'CategoryController',
                'form' => $form->createView(),
            ]);
        }

        return $this->render('@Product/category/create.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction(Request $request, Category $category) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Category updated successfuly');
            return $this->redirect($this->generateUrl('category.index'));
        }


        return $this-> render('@Product/category/create.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Category $category) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Category deleted');

        return $this->redirect($this->generateUrl('category.index'));
    }
}
