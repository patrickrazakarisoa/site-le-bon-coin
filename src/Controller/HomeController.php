<?php

namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="page_acceuil")
     */
    public function index()
    {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        return $this->render('acceuil.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/detail/{id} ", name="page_detail")
     */
    public function detail($id)
    {
        $detail = $this->getDoctrine()->getRepository(Contact::class)->find($id);
        return $this->render('detail.html.twig', [
            "id" => $id,
            "detail" => $detail
        ]);
    }

    /**
     * @Route("/annonce/delete/{id}", name="annonce-delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $entityManager->getRepository(Annonce::class)->find($id);

        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('page_acceuil');
    }

    /**
     * @Route("/add-annonce", name="add-annonce")
     */
    public function addAnnonce(Request $request)
    {
        $new_annonce = new Annonce;
        $form = $this->createForm(AnnonceType::class, $new_annonce);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($new_annonce);
            $entityManager->flush();

            $this->addFlash("annonce_add_success", "L'annonce a été ajouté avec succès");
            return $this->redirectToRoute('page_acceuil');
        }

        return $this->render('depo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
