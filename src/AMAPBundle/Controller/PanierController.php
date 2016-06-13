<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\TypePanier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Stock;
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
	    ->add('amap', EntityType::class, array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Retirer un Panier'))
            ->getForm();

        $form3 = $this->get('form.factory')->createNamedBuilder('formulaire_creation_panier')
            ->add('libelle', TextType::class )
            ->add('saison', EntityType::class,array('class' => 'AMAPBundle:Saison', 'choice_label' => 'libelle'))
            ->add('type_panier', EntityType::class,array('class' => 'AMAPBundle:TypePanier', 'choice_label' => 'libelle'))
            ->add('prix', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Créer le panier'))
            ->getForm();


         $form4 = $this->createFormBuilder()
            ->add('libelle', TextType::class )
            ->add('ajouter', SubmitType::class, array('label' => 'Créer Type Panier'))
            ->getForm();

        $paniers = $em->getRepository('AMAPBundle:Panier')->findAll();

        if ($form->handleRequest($request)->isSubmitted() ||
                $form2->handleRequest($request)->isSubmitted() ||
                $form3->handleRequest($request)->isSubmitted() ||
                $form4->handleRequest($request)->isSubmitted()){
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
                $entrepot = $data2['amap']->getEntrepot();

                $panierproduit = $panier->getPanierproduit();

                foreach ($panierproduit as $produit) {
                    $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' => $produit->getProduit(), 'entrepot' => $entrepot));

					$stock[0]->setQuantite($stock[0]->getQuantite() - $produit->getQuantite());

                    $em->persist($stock[0]);
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
                $panier->setPrix($data['prix']);

                $em->persist($panier);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }

           if ($form4->get('ajouter')->isClicked())
           {
                $data = $form4->getData();

                $typePanier = new TypePanier();

                $typePanier->setLibelle($data['libelle']);

                $em->persist($typePanier);
                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }
        }

        $stockFinal = $em->getRepository('AMAPBundle:Stock')->findAll();

        return $this->render('AMAPBundle:Panier:index.html.twig',array(
            'form' => $form->createView(),
            'paniers' => $paniers,
            'form2' => $form2->createView(),
            'stock' => $stockFinal,
            'form4' => $form4->createView(),
            'form3' => $form3->createView(),
            'page_courante' => 'panier'
        ));
    }

     public function consulterAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $paniers = $em->getRepository('AMAPBundle:Panier')->findAll();
        
        return $this->render('AMAPBundle:Panier:consulterPaniers.html.twig',array('paniers'=>$paniers,
                                                                                    'page_courante'=>'panier'));
    }
    
    public function ajouterMessageAction()
    {
        return $this->render('AMAPBundle:Panier:messageAjouter.html.twig');
    }

}
