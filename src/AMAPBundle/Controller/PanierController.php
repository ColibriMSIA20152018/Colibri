<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PanierController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('produit', EntityType::class, array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle') )
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit au Panier'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){ 
           if ($form->get('ajouter')->isClicked())
           {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                
                $panier = new Panier();
                
                $panier->addProduit($data['produit']);
                
                $em->persist($panier);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
           
        }
        
        return $this->render('AMAPBundle:Panier:index.html.twig',array(
            'form' => $form->createView(),
        ));
    }
    
       public function ajouterMessageAction()
    {
        return $this->render('AMAPBundle:Panier:messageAjouter.html.twig');
    }
    
}
