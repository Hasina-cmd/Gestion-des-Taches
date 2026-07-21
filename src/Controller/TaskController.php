<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAllOrderedForUser($this->getUser()),
        ]);
    }

    #[Route('/task/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $task->setCreatedBy($this->getUser());
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            $this->addFlash('success', "T\u{e2}che cr\u{e9}\u{e9}e avec succ\u{e8}s.");
            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/new.html.twig', ['task' => $task, 'form' => $form]);
    }

    #[Route('/task/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        $this->checkOwnership($task);

        return $this->render('task/show.html.twig', ['task' => $task]);
    }

    #[Route('/task/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $em): Response
    {
        $this->checkOwnership($task);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "T\u{e2}che modifi\u{e9}e avec succ\u{e8}s.");
            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/edit.html.twig', ['task' => $task, 'form' => $form]);
    }

    #[Route('/task/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $em): Response
    {
        $this->checkOwnership($task);

        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', "T\u{e2}che supprim\u{e9}e.");
        }

        return $this->redirectToRoute('app_task_index');
    }

    private function checkOwnership(Task $task): void
    {
        if ($task->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
    }
}