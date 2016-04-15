<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Stock;
use \AMAPBundle\Entity\Famille;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Entrepot;
use \AMAPBundle\Entity\Adresse;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class EntrepotController extends Controller
{
    public function creerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_entrepot')
            ->add('libelle', TextType::class)
            ->add('numRue',TextType::class)
            ->add('typeVoie', TextType::class )
            ->add('nomVoie', TextType::class )
            ->add('ville', TextType::class )
            ->add('cp', TextType::class )
            ->add('creer', SubmitType::class, array('label' => 'Créer un entrepot'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){

           if ($form->get('creer')->isClicked())
           {
                $data = $form->getData();

                $entrepot = new Entrepot();

				$entrepot->setLibelle($data['libelle']);

				$adresse = new Adresse();

                $adresse->setNumRue($data['numRue']);
                $adresse->setTypeVoie($data['typeVoie']);
                $adresse->setNomVoie($data['nomVoie']);
                $adresse->setville($data['ville']);
                $adresse->setCp($data['cp']);

				$entrepot->setAdresse($adresse);

				$em->persist($adresse);
                $em->persist($entrepot);

                $em->flush();
            }
        }

		$entrepots = $em->getRepository('AMAPBundle:Entrepot')->findAll();

        return $this->render('AMAPBundle:Entrepot:index.html.twig',array('form' => $form->createView(),
																		'listEntrepot' => $entrepots,
                                                                        'page_courante' => 'entrepot'));
    }

}
