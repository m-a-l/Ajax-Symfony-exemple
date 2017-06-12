<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ProductResearchType;

/**
 * Product controller.
 *
 * @Route("/")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $accountingService = $this->get('app.service.accounting_service');
        $products = $em->getRepository('AppBundle:Product')->findAll();

        $newProduct = new Product();
        $createForm = $this->createForm('AppBundle\Form\ProductType', $newProduct);
        $searchForm = $this->createForm('AppBundle\Form\ProductResearchType');
        $productToEdit = $em->getRepository('AppBundle:Product')->findAll()[0];
        $editForm = $this->createForm('AppBundle\Form\ProductType', $productToEdit);
        $deleteForm = $this->createDeleteForm($productToEdit);

        $vat = [];
        foreach($products as $product){
            $vat[$product->getId()] = $accountingService->getVatPrice($product->getPrice());
        }

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'vat' => $vat,
            'searchForm' => $searchForm->createView(),
            'createForm' => $createForm->createView(),
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product_to_edit' => $productToEdit,
        ));
    }
    /**
     * Search for  product entities with keyword.
     *
     * @Route("/search", name="_search")
     * @Method({"POST"})
     */
    public function searchAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $keyword = '';
            $keyword = $request->get('keyword');
            $em = $this->getDoctrine()->getManager();

            if($keyword != '') {
                $qb = $em->createQueryBuilder();

                $qb->select('a')
                    ->from('AppBundle:Product', 'a')
                    ->where("a.name LIKE :keyword")
                    ->orderBy('a.name', 'ASC')
                    ->setParameter('keyword', '%'.$keyword.'%');

                $query = $qb->getQuery();
                $products = $query->getResult();
            } else {
                $products = $em->getRepository('AppBundle:Product')->findAll();
            }

            return  $this->render('product/list.html.twig', array(
                'products' => $products,
            ));
        } else {

            return $this->indexAction();
        }
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = new Product;

        if($request->isXmlHttpRequest()) {
            $data = $request->get('appbundle_product');
            $product->setUuid(uniqid());
            $product->setName($data['name']);
            $product->setPrice($data['price']);

            $em->persist($product);
            $em->flush($product);
            
        }
        $products = $em->getRepository('AppBundle:Product')->findAll();
        
        return  $this->render('product/list.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $accountingService = $this->get('app.service.accounting_service');

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
            'vat' => $accountingService->getVatPrice($product->getPrice()),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="_edit")
     * @Method({"POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            if($request->isXmlHttpRequest()) {
                $data = $request->get('appbundle_product');
                $product->setName($data['name']);
                $product->setPrice($data['price']);

                $em->persist($product);
                $em->flush($product);
            }
            $products = $em->getRepository('AppBundle:Product')->findAll();

            return  $this->render('product/list.html.twig', array(
                'products' => $products,
            ));
        } else {

            return $this->indexAction();
        }

    }

    /**
     * create a form to edit an existing product entity.
     *
     * @Route("/{id}/form/edit", name="_form_edit")
     * @Method({"POST"})
     */    
    public function formEditAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        return $this->render('product/edit.html.twig', array(
            'product_to_edit' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush($product);
        }

        return $this->redirectToRoute('_index');
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
            ->setAction($this->generateUrl('_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
