<?php

namespace App\Controller;

use App\Entity\Cotation;
use App\Form\CotationType;
use App\Repository\CotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cotation')]
final class CotationController extends AbstractController
{
    #[Route(name: 'app_cotation_index', methods: ['GET'])]
    public function index(CotationRepository $cotationRepository): Response
    {
        return $this->render('cotation/index.html.twig', [
            'cotations' => $cotationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cotation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cotation = new Cotation();
        $form = $this->createForm(CotationType::class, $cotation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cotation);
            $entityManager->flush();

            return $this->redirectToRoute('app_cotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cotation/new.html.twig', [
            'cotation' => $cotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cotation_show', methods: ['GET'])]
    public function show(Cotation $cotation): Response
    {
        return $this->render('cotation/show.html.twig', [
            'cotation' => $cotation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cotation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cotation $cotation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CotationType::class, $cotation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cotation/edit.html.twig', [
            'cotation' => $cotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cotation_delete', methods: ['POST'])]
    public function delete(Request $request, Cotation $cotation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cotation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cotation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cotation_index', [], Response::HTTP_SEE_OTHER);
    }
}
