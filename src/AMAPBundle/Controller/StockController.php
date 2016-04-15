<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Stock;
use \AMAPBundle\Entity\Famille;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Entrepot;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class StockController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        /* Création formulaire pour ajouter une quantité d'un produit au stock */
        $form = $this->get('form.factory')->createNamedBuilder('formulaire_stock')
			->add('entrepot', EntityType::class,array('class' => 'AMAPBundle:Entrepot', 'choice_label' => 'libelle'))
            ->add('produit', EntityType::class,array('class' => 'AMAPBundle:Produit', 'choice_label' => 'libelle'))
            ->add('quantite', IntegerType::class)
            ->add('ajouter', SubmitType::class, array('label' => 'Ajouter au stock'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){

           /* Gestion du formulaire pour ajouter une quantité d'un produit au stock */
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $produit = $data['produit'];
                $quantite = $data['quantite'];
				$entrepot = $data['entrepot'];

                if($em->getRepository('AMAPBundle:Stock')->findBy(array('produit' =>$produit, 'entrepot' => $entrepot)))
                {
                    $stock = $em->getRepository('AMAPBundle:Stock')->findBy(array('produit' =>$produit, 'entrepot' => $entrepot));

                    $stock[0]->setQuantite($stock[0]->getQuantite()+$quantite);
                    $em->persist($stock[0]);
                }
                else
                {
                    $stock = new Stock();

                    $stock->setProduit($produit);
                    $stock->setQuantite($quantite);
					$stock->setEntrepot($entrepot);

					$entrepot->addStock($stock);

                    $em->persist($stock);
					$em->persist($entrepot);
                }

                $em->flush();
            }
        }

        $stockFinal = $em->getRepository('AMAPBundle:Stock')->findAll();

        return $this->render('AMAPBundle:Stock:index.html.twig',array('form' => $form->createView(),
                                                                        'stock' => $stockFinal,
                                                                        'page_courante' => 'stock'));
    }

    public function ajouterStockMessageAction()
    {
        return $this->render('AMAPBundle:Stock:messageStockAjouter.html.twig',array('page_courante' => 'stock'));
    }

}
