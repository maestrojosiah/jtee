<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $products = $this->em()->getRepository('AppBundle:Product')->findAll();
        if(count($products) > 0){
            $display_products = $this->getRandomDoctrineItem($this->em(), 'AppBundle\Entity\Product', 8);    
        } else {
            $display_products = NULL;
        }
        
        return $this->render('default/index.html.twig', ['display_products' => $display_products]);
    }

    /**
     * @Route("/jtee/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/jtee/agent/find", name="find_agent")
     */
    public function findAction(Request $request)
    {
        $agents = $this->em()->getRepository('AppBundle:User')
            ->findBy(
                array('active' => true, 'category' => 'le'),
                array('residence' => 'ASC')
            );
        $compressed_list = [];
        foreach ($agents as $key => $agent) {
            $compressed_list[$agent->getResidence()] = $agent;
        }
        // replace this example code with whatever you need
        return $this->render('default/find_agent.html.twig', ['agents' => $compressed_list]);
    }

    /**
     * @Route("/jtee/agent/list", name="list_agent")
     */
    public function listAgentsAction(Request $request)
    {
        $residence = $request->request->get('residence');
    
        $agents = $this->em()->getRepository('AppBundle:User')
            ->searchMatchingResidents($residence);

        $agents_list = [];
        foreach ($agents as $key => $agent) {
            $agents_list[] = [
                $agent->getFName()." ".$agent->getLName(), 
                $agent->getPhone(), 
                $agent->getEmail(), 
                $agent->getUsername().".jteekenya.org",
                $agent->getResidence() 
             ];
        }
        return new JsonResponse($agents_list);
    }

    /**
     * @Route("/jtee/products/list", name="search_products")
     */
    public function searchProductsAction(Request $request)
    {
        $result_text = $request->request->get('search_text');
    
        $products = $this->em()->getRepository('AppBundle:Product')
            ->searchMatchingProducts($result_text);

        $result_text = [];
        foreach ($products as $key => $product) {
            $result_text[] = [
                $product->getTitle(), 
                $product->getAuthor(), 
                $product->getCategory(), 
                substr($product->getDescription(), 0, 100)."...",
                $this->generateUrl("product_show", ['id' => $product->getId()])
             ];
        }
        return new JsonResponse($result_text);
    }

    /**
     * Retrieve one random item of given class from ORM repository.
     * 
     * @param EntityManager $em    The Entity Manager instance to use
     * @param string        $class The class name to retrieve items from
     * @return object
     */ 
    function getRandomDoctrineItem(EntityManager $em, $class, $limit)
    {
        static $counters = [];
        if (!isset($counters[$class])) {
            $counters[$class] = (int) $em->createQuery(
                'SELECT COUNT(c) FROM '. $class .' c' 
            )->getSingleScalarResult();
        }
        // return $counters;
        return $em
            ->createQuery('SELECT c FROM ' . $class .' c ORDER BY c.id ASC')
            ->setMaxResults($limit)
            ->setFirstResult(mt_rand(0, $counters[$class] - 1))
            ->getResult()
        ;
    }
    
    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }


}
