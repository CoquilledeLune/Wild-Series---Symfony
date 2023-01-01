<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
  #[Route('/', name: 'index')]
  public function index(CategoryRepository $categoryRepository): Response
    {
      $categories = $categoryRepository->findAll();

      return $this->render('base.html.twig', 
        ['categories' => $categories]
      );
    }

  #[Route('/new', name:'new')]
  public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
      $category = new Category(); 
      $form = $this->createForm(CategoryType::class, $category); 
      // Get data from HTTP request
      $form->handleRequest($request);
      // Was the form submitted ?
      if ($form->isSubmitted()) {
        $categoryRepository->save($category, true); 
        // Redirect to categories list
        return $this->redirectToRoute('category_index');
      }
      return $this->renderForm('category/new.html.twig', [
        'form' => $form,
      ]);
    }
/** Déclarer une route avec une variable, la variable se met entre accolades dans requirements on lui dit 
 * de quel type elle doit être, puis la méthode ('GET') et le name c'est le nom de ma route */
  #[Route('/{categoryName}', requirements: ['categoryName'=> '\w+'], methods: ['GET'], name: 'show')]
  public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
  
    {
      $category = $categoryRepository->findOneBy(['name' => $categoryName]);
      if ($category == NULL) {
        throw new NotFoundHttpException('Aucune catégorie nommée ' . $categoryName);
      }
      
        $programs = $programRepository->findBy(['category' => $category->getId()], ['id' => 'DESC'], 3);
        return $this->render('category/show.html.twig', ['category'=> $category, 'programs'=> $programs]); 
           
    }
}
