<?php

namespace Smith981\NewsboxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Smith981\NewsboxBundle\Entity\Issue;
use Smith981\NewsboxBundle\Form\IssueType;
use Smith981\NewsboxBundle\Form\IssueFilterType;

/**
 * Issue controller.
 *
 * @Route("/")
 */
class IssueController extends Controller
{
    /**
     * Lists all Issue entities.
     *
     * @Route("admin/issue/", name="issue")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        list($filterForm, $queryBuilder) = $this->filter();

        $queryBuilder->orderBy('e.publishedDate', 'desc');
        $queryBuilder->addOrderBy('e.id', 'desc');

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
        );
    }
   
   /**
    * Create filter form and process filter request.
    */
    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new IssueFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('Smith981NewsboxBundle:Issue')->createQueryBuilder('e');

        // Reset filter
        if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'reset') {
            $session->remove('IssueControllerFilter');
        }

        // Filter action
        if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('IssueControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('IssueControllerFilter')) {
                $filterData = $session->get('IssueControllerFilter');
                $filterForm = $this->createForm(new IssueFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('issue', array('page' => $page));
        };

        // Paginator - view
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Creates a new Issue entity.
     *
     * @Route("/admin/issue", name="issue_create")
     * @Method("POST")
     * @Template("Smith981NewsboxBundle:Issue:new.html.twig")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function createAction(Request $request)
    {
        $entity  = new Issue();

        $entity->setCreated(new \DateTime());

        $form = $this->createForm(new IssueType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('issue_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Issue entity.
     *
     * @Route("/admin/issue/new", name="issue_new")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction()
    {
        $entity = new Issue();
        $form   = $this->createForm(new IssueType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Issue entity.
     *
     * @Route("admin/issue/{id}", name="issue_show")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Smith981NewsboxBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue ' . $id);
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Issue entity.
     *
     * @Route("admin/{id}/edit", name="issue_edit")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Smith981NewsboxBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue ' . $id);
        }

        $editForm = $this->createForm(new IssueType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Issue entity.
     *
     * @Route("admin/issue/{id}", name="issue_update")
     * @Method("PUT")
     * @Method("POST")
     * @Template("Smith981NewsboxBundle:Issue:edit.html.twig")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Smith981NewsboxBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue ' . $id);
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IssueType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            /**
             * If issue is being published, set the Published Date to current date time.
             */
            if($entity->getStatus() == 1) {
                $entity->setPublishedDate(new \DateTime());
            }
            else {
                $entity->setPublishedDate(null);
            }

            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('issue_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Issue entity.
     *
     * @Route("admin/issue/{id}", name="issue_delete")
     * @Method("DELETE")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Smith981NewsboxBundle:Issue')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Issue ' . $id);
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('issue'));
    }

    /**
     * Creates a form to delete a Issue entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Public view of an issue
     *
     * @Route("issue/{id}", name="issue_public_show")
     * @Method("GET")
     * @Template("Smith981NewsboxBundle:Public:index.html.twig")
     */
    public function publicShowAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Smith981NewsboxBundle:Issue')->find($id);

        if (!$entity || $entity->getStatus() != 1) {
            throw $this->createNotFoundException('Unable to find Issue ' . $id);
        }

        $stories = $entity->getStories();

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'stories'     => $stories,
        );
    }

    /**
     * Lists all Issue entities
     *
     * @Route("issueslist", name="issue_public_index")
     * @Method("GET")
     * @Template("Smith981NewsboxBundle:Public:issues.html.twig")
     */
    public function publicIndexAction()
    {

        list($filterForm, $queryBuilder) = $this->filter();

        $queryBuilder->add('where', 'e.status = 1');
        $queryBuilder->orderBy('e.publishedDate', 'desc');
        $queryBuilder->addOrderBy('e.id', 'desc');

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return array(
            'issues' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * Show most recent issue
     *
     * @Route("/", name="public_home")
     * @Method("GET")
     * @Template("Smith981NewsboxBundle:Public:index.html.twig")
     */
    public function publicHomeAction()
    {

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('Smith981NewsboxBundle:Issue')->createQueryBuilder('e'); 
        $queryBuilder->select()
                     ->from('Smith981NewsboxBundle:Issue', 'i')
                     ->where('e.status = 1')
                     ->orderBy('e.id', 'desc');

        /**
         * @todo Get a single result instead of just grabbing the first from the array
         */
        $entities = $queryBuilder->getQuery()->getResult();
        $entity = $entities[0];

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find a published issue');
        }

        $stories = $entity->getStories();
        $imageUrl = $entity->getImageUrl();

        return array(
            'stories'     => $stories,
            'entity'      => $entity
        );
    }
}
