<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Acteur;
use \AMAPBundle\Entity\TypeActeur;
use \AMAPBundle\Entity\Contrat;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ContratController extends Controller
{
    public function creerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creer_contrat')
            ->add('producteur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                        'choice_label' => 'nom',
                                                        'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
                                                            return $er->createQueryBuilder('s')
                                                            ->where('s.typeActeur = ?1')
                                                            ->setParameter(1,'1');
                                                        }))
            ->add('consommateur',EntityType::class,array('class' => 'AMAPBundle:Acteur',
                                                         'choice_label' => 'nom',
                                                         'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
                                                            return $er->createQueryBuilder('s')
                                                            ->where('s.typeActeur = ?1')
                                                            ->setParameter(1,'2');
                                                        }))
			->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'))
            ->add('panier',EntityType::class,array('class' => 'AMAPBundle:Panier', 'choice_label' => 'libelle'))
            ->add('ajouter', SubmitType::class, array('label' => 'Créer contrat'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $contrat = new Contrat();

                $contrat->setProducteur($data['producteur']);
                $contrat->setConsommateur($data['consommateur']);
				$contrat->setAmap($data['amap']);
                $contrat->setPanier($data['panier']);

                $em->persist($contrat);
                $em->flush();
           }
        }

        $listcontrat = $em->getRepository('AMAPBundle:Contrat')->findAll();
        $typeProducteur = $em->getRepository('AMAPBundle:TypeActeur')->findBy(array('libelle' => 'Producteur'));
        $listproducteur = $em->getRepository('AMAPBundle:Acteur')->findBy(array('typeActeur' => $typeProducteur));

		/*
		 * FONCTION DE CALCUL DE QUANTITE A PRODUIRE POUR LE PRODUCTEUR
		 */
			$tab = array();
            $i = 0;
			/*
			 * BOUCLE PRODUCTEURS
			 */
            foreach ($listproducteur as $producteur) {

                    $j = 0;
					// Panier TEMP
                    $panier = new Panier();
                    $tab[$i][$j] = $producteur->getNom();
					/*
					 * BOUCLE CONTRATS
					 */
                    foreach ($listcontrat as $contrat) {
						//SI LE CONTRAT COURANT ET CELUI DU PRODUCTEUR COURANT
                        if ($producteur == $contrat->getProducteur())
						{
							/*
							 * BOUCLE PANIERPRODUIT DU CONTRAT COURANT
							 */
                            foreach($contrat->getPanier()->getPanierproduit() as $panierproduit)
                            {
                                $check=false;
								/*
								 * BOUCLE DU PANIER TEMP
								 */
                                foreach($panier->getPanierproduit() as $panierproduit2)
								{
									// SI DANS LE PANIER TEMP IL Y A DEJA LE PRODUIT DU CONTRAT
                                    if($panierproduit2->getProduit() == $panierproduit->getProduit())
									{
                                        $panierproduit2->setQuantite($panierproduit2->getQuantite() + $panierproduit->getQuantite());
                                        $check=true;
                                    }
                                }
								// SI DANS LE PANIER IL N'Y A PAS LE PRODUIT DU CONTRAT
                                if(!$check){
									$newPanierProd = new Panierproduit();
									$newPanierProd->setQuantite($panierproduit->getQuantite());
									$newPanierProd->setProduit($panierproduit->getProduit());
                                    $panier->addPanierproduit($newPanierProd);
                                }
                            }
                        }
                    }
                    $j++;
					// AJOUT DANS LE TAB QUI SERA RENVOYER A LA VUE
                    $tab[$i][$j] = $panier;
                    $i++;
            }

        return $this->render('AMAPBundle:Contrat:index.html.twig',array(
            'form' => $form->createView(),
            'page_courante' => 'contrat',
            'listproducteur' => $listproducteur,
            'listcontrat' => $listcontrat,
            'tabProd' => $tab
              ));
    }
}