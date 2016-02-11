<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PanierController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_ajout_produit_panier')
            ->add('produit', EntityType::class, array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle') )
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle') )  
            ->add('quantite', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit à un Panier'))
            ->getForm();
        
        $form2 = $this->get('form.factory')->createNamedBuilder('formulaire_retirer_panier')
            ->add('panier', EntityType::class, array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle') )  
            ->add('ajouter', SubmitType::class, array('label' => 'Retirer un Panier'))
            ->getForm();
        
        $form3 = $this->get('form.factory')->createNamedBuilder('formulaire_creation_panier')
            ->add('libelle', TextType::class )
            ->add('saison', EntityType::class,array('class' => 'AMAPBundle:Saison', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer le panier'))
            ->getForm();
        
        $paniers = $em->getRepository('AMAPBundle:Panier')->findAll();
        
        if ($form->handleRequest($request)->isSubmitted() || 
                $form2->handleRequest($request)->isSubmitted() || 
                $form3->handleRequest($request)->isSubmitted() ){ 
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData(); 
                
                $panierproduit = new PanierProduit();
                
                
                $panierproduit->setProduit($data['produit']);
                $panierproduit->setQuantite($data['quantite']);
                
                $panier = $data['panier']->addPanierproduit($panierproduit);
                
                $panierproduit->setPanier($panier);
             
                $em->persist($panier);
                $em->persist($panierproduit);
                $em->flush();
                
               
                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
           
           if ($form2->get('ajouter')->isClicked())
           {

                $data2 = $form2->getData(); 
                               
                $panier = $data2['panier'];
                
                $panierproduit = $panier->getPanierproduit();
                
                foreach ($panierproduit as $produit) {
                    $stock = $em->getRepository('AMAPBundle:Stock')->find($produit->getProduit());
                    
                    $stock->setQuantite($stock->getQuantite() - $produit->getQuantite());
                    
                    $em->persist($stock);   
                }
               
                $em->flush();
                
                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
           
           if ($form3->get('ajouter')->isClicked())
           {
                $data = $form3->getData(); 
                
                $panier = new Panier();
                
                $panier->setLibelle($data['libelle']);
                $panier->setSaison($data['saison']);
                          
                $em->persist($panier);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
           
           
        }
        
        return $this->render('AMAPBundle:Panier:index.html.twig',array(
            'form' => $form->createView(), 'paniers' => $paniers, 
            'form2' => $form2->createView(),
            'form3' => $form3->createView()
        ));
    }
    
    public function ajouterMessageAction()
    {
        return $this->render('AMAPBundle:Panier:messageAjouter.html.twig');
    }
    
}
