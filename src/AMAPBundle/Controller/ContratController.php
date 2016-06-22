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
  
        $session = $request->getSession();
        
        if($this->getUser()->getTypeActeur()->getId()==2){
            $listcontrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('consommateur'=>$this->getUser()->getId(),'amap'=>$session->get('amap')));
        }else{
            $listcontrat = $em->getRepository('AMAPBundle:Contrat')->findBy(array('producteur'=>$this->getUser()->getId(),'amap'=>$session->get('amap')));
        }
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
            'page_courante' => 'contrat',
            'listproducteur' => $listproducteur,
            'listcontrat' => $listcontrat,
            'tabProd' => $tab
              ));
    }
}