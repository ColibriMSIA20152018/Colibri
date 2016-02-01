<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('libelle', TextType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){ 
           if ($form->get('ajouter')->isClicked())
           {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                
                $produit = new Produit();
                
                $produit->setLibelle($data['libelle']);
                
                $em->persist($produit);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_produit_ajouter'));
           }
           
        }
        
        return $this->render('AMAPBundle:Produit:index.html.twig',array(
            'form' => $form->createView(),
        ));
    }
    
    public function ajouterAction()
    {
        return $this->render('AMAPBundle:Produit:messageAjouter.html.twig');
    }
    
    public function produitAction()
    {
        return $this->render('AMAPBundle:Default:index.html.twig');
    }
}
