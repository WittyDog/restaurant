<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @Route("menu")
 */
class MenuController extends Controller
{
    /**
     * Lists all menu entities.
     *
     * @Route("/", name="menu_index")
     * @Method("GET")
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('RestaurantBundle:Menu')->findBy(
            array(),
            array('ordre' => 'asc')
        );

        return $this->render('menu/index.html.twig', array(
            'menus' => $menus,
        ));
    }

    /**
     * Creates a new menu entity.
     *
     * @Route("/new", name="menu_new")
     * @Method({"GET", "POST"})
     *
     * @Security("has_role('ROLE_EDITEUR')")
     */
    public function newAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm('RestaurantBundle\Form\MenuType', $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $menu->setAuteur($this->getUser());

            $em->persist($menu);
            $em->flush($menu);

            // Demande de validation envoyé par mail si l'utilisation n'est pas autorisé à publier
            if($menu->getStatut() == "en validation" && !$this->get('security.authorization_checker')->isGranted('ROLE_REVIEWER'))
                $this->get("app.email_handler")->sendMenuValidationRequest($menu);

            // Envoi d'une notification
            $this->addFlash('info', "Le menu intitulé '" . $menu->getTitre() . "' a bien été enregistré.");

            return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
        }

        return $this->render('menu/new.html.twig', array(
            'menu' => $menu,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a menu entity.
     *
     * @Route("/{id}", name="menu_show")
     * @Method("GET")
     */
    public function showAction(Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);

        return $this->render('menu/show.html.twig', array(
            'menu' => $menu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     * @Route("/{id}/edit", name="menu_edit")
     * @Method({"GET", "POST"})
     *
     * @Security("has_role('ROLE_EDITEUR')")
     */
    public function editAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm('RestaurantBundle\Form\MenuType', $menu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // Demande de validation envoyé par mail si l'utilisation n'est pas autorisé à publier
            if($menu->getStatut() == "en validation" && !$this->get('security.authorization_checker')->isGranted('ROLE_REVIEWER'))
                $this->get("app.email_handler")->sendMenuValidationRequest($menu);

            // Envoi d'une notification
            $this->addFlash('info', "Le menu intitulé '" . $menu->getTitre() . "' a bien été mis a jour.");

            return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
        }

        return $this->render('menu/edit.html.twig', array(
            'menu' => $menu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     * @Route("/{id}/validate", name="menu_validate")
     * @Method("GET")
     *
     * @Security("has_role('ROLE_REVIEWER')")
     */
    public function validateAction(Menu $menu)
    {
        $menu->setStatut("validé");
        $this->getDoctrine()->getManager()->flush($menu);

        // Envoi d'une notification
        $this->addFlash('info', "Le menu intitulé '" . $menu->getTitre() . "' a bien été validé et publié.");

        //Notification des serveurs par mail
        $this->get("app.email_handler")->notifyMenuPublication($menu);

        return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     * @Route("/{id}/refuse", name="menu_refuse")
     * @Method("GET")
     *
     * @Security("has_role('ROLE_REVIEWER')")
     */
    public function refuseAction(Menu $menu)
    {
        $menu->setStatut("refusé");
        $this->getDoctrine()->getManager()->flush($menu);

        // Envoi d'une notification
        $this->addFlash('info', "Le menu intitulé '" . $menu->getTitre() . "' a bien été refusé.");

        return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
    }

    /**
     * Deletes a menu entity.
     *
     * @Route("/{id}", name="menu_delete")
     * @Method("DELETE")
     *
     * @Security("has_role('ROLE_CHEF')")
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $form = $this->createDeleteForm($menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush($menu);

            // Envoi d'une notification
            $this->addFlash('info', "Le menu intitulé '" . $menu->getTitre() . "' a bien été supprimé.");
        }

        return $this->redirectToRoute('menu_index');
    }

    /**
     * Creates a form to delete a menu entity.
     *
     * @param Menu $menu The menu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('menu_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
