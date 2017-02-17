<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Plat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Plat controller.
 *
 * @Route("plat")
 */
class PlatController extends Controller
{
    /**
     * Lists all plat entities.
     *
     * @Route("/", name="plat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $plats = $em->getRepository('RestaurantBundle:Plat')->findAll();

        return $this->render('plat/index.html.twig', array(
            'plats' => $plats,
        ));
    }

    /**
     * Creates a new plat entity.
     *
     * @Route("/new", name="plat_new")
     * @Method({"GET", "POST"})
     *
     * @Security("has_role('ROLE_EDITEUR')")
     */
    public function newAction(Request $request)
    {
        $plat = new Plat();
        $form = $this->createForm('RestaurantBundle\Form\PlatType', $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $plat->setStatut("brouillon");
            $plat->setAuteur($this->getUser());

            $em->persist($plat);
            $em->flush($plat);

            // Envoi d'une notification
            $this->addFlash('info', "Le plat intitulé '" . $plat->getTitre() . "' a bien été enregistré.");

            return $this->redirectToRoute('plat_show', array('id' => $plat->getId()));
        }

        return $this->render('plat/new.html.twig', array(
            'plat' => $plat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a plat entity.
     *
     * @Route("/{id}", name="plat_show")
     * @Method("GET")
     */
    public function showAction(Plat $plat)
    {
        $deleteForm = $this->createDeleteForm($plat);

        return $this->render('plat/show.html.twig', array(
            'plat' => $plat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing plat entity.
     *
     * @Route("/{id}/edit", name="plat_edit")
     * @Method({"GET", "POST"})
     *
     * @Security("has_role('ROLE_EDITEUR')")
     */
    public function editAction(Request $request, Plat $plat)
    {
        if($plat->getImage() != null) {
            $plat->setImage(new File($this->getParameter('files_directory') . '/' . $plat->getImage()));
        }

        $deleteForm = $this->createDeleteForm($plat);
        $editForm = $this->createForm('RestaurantBundle\Form\PlatType', $plat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $plat->setStatut("brouillon");
            $plat->setAuteur($this->getUser());

            $em->flush($plat);

            // Envoi d'une notification
            $this->addFlash('info', "Le plat intitulé '" . $plat->getTitre() . "' a bien été mis a jour.");

            return $this->redirectToRoute('plat_show', array('id' => $plat->getId()));
        }

        return $this->render('plat/edit.html.twig', array(
            'plat' => $plat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Validate a plat
     *
     * @Route("/{id}/validate", name="plat_validate")
     * @Method("GET")
     *
     * @Security("has_role('ROLE_REVIEWER')")
     */
    public function validateAction(Plat $plat)
    {
        $plat->setStatut("validé");
        $this->getDoctrine()->getManager()->flush($plat);

        // Envoi d'une notification
        $this->addFlash('info', "Le plat intitulé '" . $plat->getTitre() . "' a bien été validé et publié.");

        //Notification des serveurs par mail
        $this->get("app.email_handler")->notifyPlatPublication($plat);

        return $this->redirectToRoute('plat_show', array('id' => $plat->getId()));
    }

    /**
     * Refuse a plat
     *
     * @Route("/{id}/refuse", name="plat_refuse")
     * @Method("GET")
     *
     * @Security("has_role('ROLE_REVIEWER')")
     */
    public function refuseAction(Plat $plat)
    {
        $plat->setStatut("refusé");
        $this->getDoctrine()->getManager()->flush($plat);

        // Envoi d'une notification
        $this->addFlash('info', "Le plat intitulé '" . $plat->getTitre() . "' a bien été refusé.");

        return $this->redirectToRoute('plat_show', array('id' => $plat->getId()));
    }

    /**
     * Deletes a plat entity.
     *
     * @Route("/{id}", name="plat_delete")
     * @Method("DELETE")
     *
     * @Security("has_role('ROLE_CHEF')")
     */
    public function deleteAction(Request $request, Plat $plat)
    {
        $form = $this->createDeleteForm($plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($plat);
            $em->flush($plat);

            // Envoi d'une notification
            $this->addFlash('info', "Le plat intitulé '" . $plat->getTitre() . "' a bien été supprimé.");
        }

        return $this->redirectToRoute('plat_index');
    }

    /**
     * Creates a form to delete a plat entity.
     *
     * @param Plat $plat The plat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Plat $plat)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('plat_delete', array('id' => $plat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
