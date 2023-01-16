<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
  #[Route('/', name: 'index')]
  public function index(CategoryRepository $categoryRepository, RequestStack $requestStack): Response
    {
      $categories = $categoryRepository->findAll();
      // Messages flash
      $session = $requestStack->getSession();
      if (!$session->has('total')) {
        $session->set('total', 0);
      }
      $total = $session->get('total');
      // Fin messages flash
      return $this->render('category/index.html.twig', 
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
      if ($form->isSubmitted()&& $form->isValid()) {
        $categoryRepository->save($category, true); 
        // Put your message flash here
        $this->addFlash('success', 'La nouvelle catégorie a été créée');        
        // Redirect to categories list
        return $this->redirectToRoute('category_index');
      }
      return $this->renderForm('category/new.html.twig', [
        'form' => $form,
      ]);
    }
/** Déclarer une route avec une variable, la variable se met entre accolades dans requirements on lui dit 
 * de quel type elle doit être, puis la méthode ('GET') et le name c'est le nom de ma route */
  #[Route('/{categoryName}', methods: ['GET'], name: 'show')]
  public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
  
    {
      $category = $categoryRepository->findOneBy(['name' => $categoryName]);
      if ($category == NULL) {
        throw new NotFoundHttpException('Aucune catégorie nommée ' . $categoryName);
      }
      
        $programs = $programRepository->findBy(['category' => $category->getId()], ['id' => 'DESC'], 3);
        return $this->render('category/show.html.twig', ['category'=> $category, 'programs'=> $programs]); 
           
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }
// Message flash de suppression
$this->addFlash('danger', 'La category a été supprimée');

        return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
    }
}
