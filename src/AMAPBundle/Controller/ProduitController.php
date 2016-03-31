<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Stock;
use \AMAPBundle\Entity\Famille;
use \AMAPBundle\Entity\Saison;
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
            ->add('famille', EntityType::class,array('class' => 'AMAPBundle:Famille', 'choice_label' => 'libelle'))  
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter un produit'))
            ->getForm();
        
        /* Création formulaire pour ajouter une quantité d'un produit au stock */
        $form2 = $this->get('form.factory')->createNamedBuilder('formulaire_stock')
            ->add('produit', EntityType::class,array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle'))
            ->add('quantite', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter au stock'))
            ->getForm();
        
        $form3 = $this->get('form.factory')->createNamedBuilder('formulaire_creation_famille')
            ->add('libelle', TextType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Créer la famille'))
            ->getForm();
        
        $form4 = $this->get('form.factory')->createNamedBuilder('formulaire_creation_saison')
            ->add('libelle', TextType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Créer la saison'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted() ||
                $form2->handleRequest($request)->isSubmitted() ||
                $form3->handleRequest($request)->isSubmitted() ||
                $form4->handleRequest($request)->isSubmitted()){ 
            
            /* Gestion du formulaire pour ajouter un nouveau type de produit */
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();
                
                $produit = new Produit();
                
                $produit->setLibelle($data['libelle']);
                $produit->setFamille($data['famille']);
                
                $em->persist($produit);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_produit_ajouter'));
           }
           
           /* Gestion du formulaire pour ajouter une quantité d'un produit au stock */
           if ($form2->get('ajouter')->isClicked())
           {
                $data2 = $form2->getData();

                $produit2 = $data2['produit'];
                $quantite = $data2['quantite'];
                
                if($em->getRepository('AMAPBundle:Stock')->find($produit2))
                {
                    $stock = $em->getRepository('AMAPBundle:Stock')->find($produit2);
                    
                    $stock->setQuantite($stock->getQuantite()+$quantite);
                    $em->persist($stock);
                }
                else
                {
                    $stock = new Stock();

                    $stock->setProduit($produit2);
                    $stock->setQuantite($quantite);
                    $em->persist($stock);
                }
                   
                
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_stock_ajouter'));
            }  
            
           if ($form3->get('ajouter')->isClicked())
           {
                $data = $form3->getData();
                
                $famille = new Famille();
                
                $famille->setLibelle($data['libelle']);
                
                $em->persist($famille);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_produit_ajouter'));
           }
           
           if ($form4->get('ajouter')->isClicked())
           {
                $data = $form4->getData();
                
                $saison = new Saison();
                
                $saison->setLibelle($data['libelle']);
                
                $em->persist($saison);
                $em->flush();
                
                return $this->redirect($this->generateUrl('amap_produit'));
           }
            
        }
        
        $stockFinal = $em->getRepository('AMAPBundle:Stock')->findAll();
        $listFamille = $em->getRepository('AMAPBundle:Famille')->findAll();
        $listProduit = $em->getRepository('AMAPBundle:Produit')->findAll();
        $listSaison = $em->getRepository('AMAPBundle:Saison')->findAll();
        
        return $this->render('AMAPBundle:Produit:index.html.twig',array('form' => $form->createView(),
                                                                        'form2' => $form2->createView(),
                                                                        'form3' => $form3->createView(),
                                                                        'form4' => $form4->createView(),
                                                                        'stock' => $stockFinal,
                                                                        'page_courante' => 'produit',
                                                                        'listFamille' => $listFamille,
                                                                        'listProduit' => $listProduit,
                                                                        'listSaison' => $listSaison));
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
