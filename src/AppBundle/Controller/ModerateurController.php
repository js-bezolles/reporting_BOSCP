<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Moderateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Moderateur controller.
 *
 * @Route("moderateur")
 */
class ModerateurController extends Controller
{
    /**
     * Lists all moderateur entities.
     *
     * @Route("/", name="moderateur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $moderateurs = $em->getRepository('AppBundle:Moderateur')->findAll();

        return $this->render('moderateur/index.html.twig', array(
            'moderateurs' => $moderateurs,
        ));
    }

    /**
     * Creates a new moderateur entity.
     *
     * @Route("/new", name="moderateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $moderateur = new Moderateur();
        $form = $this->createForm('AppBundle\Form\ModerateurType', $moderateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($moderateur);
            $em->flush($moderateur);

            return $this->redirectToRoute('moderateur_show', array('id' => $moderateur->getId()));
        }

        return $this->render('moderateur/new.html.twig', array(
            'moderateur' => $moderateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a moderateur entity.
     *
     * @Route("/{id}", name="moderateur_show")
     * @Method("GET")
     */
    public function showAction(Moderateur $moderateur)
    {
        $deleteForm = $this->createDeleteForm($moderateur);

        return $this->render('moderateur/show.html.twig', array(
            'moderateur' => $moderateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing moderateur entity.
     *
     * @Route("/{id}/edit", name="moderateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Moderateur $moderateur)
    {
        $deleteForm = $this->createDeleteForm($moderateur);
        $editForm = $this->createForm('AppBundle\Form\ModerateurType', $moderateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('moderateur_edit', array('id' => $moderateur->getId()));
        }

        return $this->render('moderateur/edit.html.twig', array(
            'moderateur' => $moderateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a moderateur entity.
     *
     * @Route("/{id}", name="moderateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Moderateur $moderateur)
    {
        $form = $this->createDeleteForm($moderateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($moderateur);
            $em->flush($moderateur);
        }

        return $this->redirectToRoute('moderateur_index');
    }

    /**
     * Creates a form to delete a moderateur entity.
     *
     * @param Moderateur $moderateur The moderateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Moderateur $moderateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('moderateur_delete', array('id' => $moderateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Lists moderateur-partenaire information.
     *
     * @Route("/show/moderateur_partenaire", name="moderateur_partenaire_index")
     *
     */
    public function indexModerateurPartenaire()
    {
        $em = $this->getDoctrine()->getManager();

        $idPartenaire = -1;

        $moderateurs = $em->getRepository('AppBundle:Moderateur')->findModByPartenaire($idPartenaire);

        return $this->render('moderateur/indexWithPartenaire.html.twig', array(
            'moderateurs' => $moderateurs,
        ));
    }
}
