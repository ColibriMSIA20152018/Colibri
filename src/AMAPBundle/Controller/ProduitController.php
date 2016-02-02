<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Stock;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class ProduitController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        /* Création du formulaire pour ajouter un nouveau type de produit */
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_produit')
            ->setAttributes(array('name' => 'formulaire'))
            ->add('libelle', TextType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit'))
            ->getForm();
        
        
        /* Création formulaire pour ajouter une quantité d'un produit au stock */
        $form2 = $this->get('form.factory')->createNamedBuilder('formulaire_stock')
            ->add('produit', EntityType::class,array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle'))
            ->add('quantite', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter au stock'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted() || $form2->handleRequest($request)->isSubmitted()){ 
            
            /* Gestion du formulaire pour ajouter un nouveau type de produit */
            if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();
                
                $produit = new Produit();
                
                $produit->setLibelle($data['libelle']);
                
                $em->persist($produit);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_produit_ajouter'));
           }
           
           /* Gestion du formulaire pour ajouter une quantité d'un produit au stock */
           if ($form2->get('ajouter')->isClicked())
           {
                $data2 = $form2->getData();

                $produit = $data2['produit'];
                $quantite = $data2['quantite'];
                
                if($em->getRepository('AMAPBundle:Stock')->findBy(array('produit' => $produit)))
                {
                    $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' => $produit));
                            
                    $stock[0]->setQuantite($stock[0]->getQuantite()+$quantite);
                }
                else
                {
                    $stock = new Stock();

                    $stock->setProduit($produit);
                    $stock->setQuantite($quantite);
                }
                   
                $em->persist($stock[0]);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_stock_ajouter'));
            }         
        }
        
        $stockFinal = $em->getRepository('AMAPBundle:Stock')->findAll();
        
        return $this->render('AMAPBundle:Produit:index.html.twig',array('form' => $form->createView(),
                                                                        'form2' => $form2->createView(),
                                                                        'stock' => $stockFinal));
    }
    
    public function ajouterAction()
    {
        return $this->render('AMAPBundle:Produit:messageAjouter.html.twig');
    }
    
    public function produitAction()
    {
        return $this->render('AMAPBundle:Default:index.html.twig');
    }
    
    public function ajouterStockMessageAction()
    {
        return $this->render('AMAPBundle:Produit:messageStockAjouter.html.twig');
    }
    
}
