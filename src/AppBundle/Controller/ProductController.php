<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findBy(
            array('deleted' => 0),
            array('id' => 'ASC')
        );

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Lists all product entities.
     *
     * @Route("/admin/deleted", name="product_deleted")
     * @Method("GET")
     */
    public function deletedAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findBy(
            array('deleted' => 1),
            array('id' => 'ASC')
        );

        return $this->render('product/restore.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Lists all product entities.
     *
     * @Route("/author/{author}", name="product_author")
     * @Method("GET")
     */
    public function authorAction($author)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findBy(
            array('author' => $author),
            array('id' => 'ASC')
        );

        return $this->render('product/author.html.twig', array(
            'products' => $products,
            'author' => $author,
        ));
    }

    /**
     * Lists all product entities.
     *
     * @Route("/category/{category}", name="product_category")
     * @Method("GET")
     */
    public function categoryAction($category)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findBy(
            array('category' => $category),
            array('id' => 'ASC')
        );

        return $this->render('product/category.html.twig', array(
            'products' => $products,
            'category' => $category,
        ));
    }

    /**
     * Lists all product entities.
     *
     * @Route("/availability/{availability}", name="product_availability")
     * @Method("GET")
     */
    public function availableAction($availability)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findBy(
            array('availability' => $availability),
            array('id' => 'ASC')
        );

        return $this->render('product/availability.html.twig', array(
            'products' => $products,
            'availability' => $availability,
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/admin/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);
        $user = $this->user();

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $category = $form->get('category')->getData();
            $now = date("d-m-Y h:i:s");
            $product->setUploaded(new \DateTime($now));            
            $product->setDeleted(0);            
            $product_title = $form->get('title')->getData();
            $trimmed_title = str_replace(" ", "_", $product_title);
            $originalName = $image->getClientOriginalName();;
            $filepath = $this->get('kernel')->getProjectDir()."/web/img/products/$category/$trimmed_title/";
            $image->move($filepath, $originalName);
            $simple_filepath = "/img/products/$category/$trimmed_title/";
            $product->setImage($simple_filepath . $originalName);
            $product->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        $related_products = $this->getRandomDoctrineItem($this->em(), 'AppBundle\Entity\Product', $product->getCategory(), 3);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
            'related_products' => $related_products,
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/admin/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);
        $formerFileName = $product->getImage();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $image = $editForm->get('image')->getData();
            $category = $editForm->get('category')->getData();
            $now = date("d-m-Y h:i:s");
            $product->setUploaded(new \DateTime($now));            
            $product_title = $editForm->get('title')->getData();
            $trimmed_title = str_replace(" ", "_", $product_title);
             // if (is_object($image)){
                $product_title = $editForm->get('title')->getData();
                $trimmed_title = str_replace(" ", "_", $product_title);
                $originalName = $image->getClientOriginalName();;
                $filepath = $this->get('kernel')->getProjectDir()."/web/img/products/$category/$trimmed_title/";
                $simple_filepath = "/img/products/$category/$trimmed_title/";
                $image->move($filepath, $originalName);
                $product->setImage($simple_filepath . $originalName);
            // } else {
            //     $config->setImage($formerFileName);
            // }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/admin/{id}", name="product_delete")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setDeleted(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/admin/restore/{id}", name="product_restore")
     */
    public function restoreAction(Request $request, Product $product)
    {

        $product->setDeleted(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('product_deleted');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Retrieve one random item of given class from ORM repository.
     * 
     * @param EntityManager $em    The Entity Manager instance to use
     * @param string        $class The class name to retrieve items from
     * @return object
     */ 
    function getRandomDoctrineItem(EntityManager $em, $class, $like, $limit)
    {
        static $counters = [];
        if (!isset($counters[$class])) {
            $counters[$class] = (int) $em->createQuery(
                'SELECT COUNT(c) FROM '. $class .' c' 
            )->getSingleScalarResult();
        }
        // return $counters;
        return $em
            ->createQuery('SELECT c FROM ' . $class .' c WHERE c.category = :like ORDER BY c.id ASC')
            ->setMaxResults($limit)
            ->setParameter('like', $like)
            ->setFirstResult(mt_rand(0, 5))
            ->getResult()
        ;
    }

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function find($entity, $id){
        $entity = $this->em()->getRepository("AppBundle:$entity")->find($id);
        return $entity;
    }

    private function findby($entity, $by, $actual){
        $query_string = "findBy$by";
        $entity = $this->em()->getRepository("AppBundle:$entity")->$query_string($actual);
        return $entity;
    }

    private function findandlimit($entity, $by, $actual, $limit, $order){
        $entity = $this->em()->getRepository("AppBundle:$entity")
            ->findBy(
                array($by => $actual),
                array('id' => $order),
                $limit
            );
        return $entity;
    }
    
    private function findbyandlimit($entity, $by, $actual, $by2, $actual2, $limit, $offset){
        $entity = $this->em()->getRepository("AppBundle:$entity")
            ->findBy(
                array($by => $actual, $by2 => $actual2),
                array('id' => 'ASC'),
                $limit,
                $offset
            );
        return $entity;
    }

    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();
        return true;
    }

    private function delete($entity){
        $this->em()->remove($entity);
        $this->em()->flush();
        return true;
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

}
