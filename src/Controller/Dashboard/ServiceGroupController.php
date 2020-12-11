<?php

namespace App\Controller\Dashboard;

use App\Entity\ServiceGroup;
use App\Form\ServiceGroupType;
use App\Repository\ServiceGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/service-group", name="service-group_")
 */
class ServiceGroupController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ServiceGroupRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $manager, ServiceGroupRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @return Response
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('dashboard/service-group/index.html.twig', [
            'groups' => $this->repository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(ServiceGroupType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($group = $form->getData());
            $this->manager->flush();
            $this->addFlash('success', sprintf('The service group "%s" was created.', $group->getName()));

            return $this->redirectToRoute('service-group_index');
        }

        return $this->render('dashboard/service-group/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ServiceGroup $group
     * @param Request $request
     * @return Response
     * @Route("/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(ServiceGroup $group, Request $request): Response
    {
        $form = $this->createForm(ServiceGroupType::class, $group)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', sprintf('The service group "%s" was edited.', $group->getName()));

            return $this->redirectToRoute('service-group_index');
        }

        return $this->render('dashboard/service-group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ServiceGroup $group
     * @param Request $request
     * @return RedirectResponse
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(ServiceGroup $group, Request $request): RedirectResponse
    {
        if (
        $this->isCsrfTokenValid(
            sprintf('service-group_delete_%s', $group->getId()),
            $request->request->get('_token')
        )
        ) {
            $this->manager->remove($group);
            $this->manager->flush();
            $this->addFlash('success', sprintf('The service group "%s" was deleted.', $group->getName()));
        }

        return $this->redirectToRoute('service-group_index');
    }
}
