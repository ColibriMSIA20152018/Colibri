<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AMAPBundle\Entity\Panier;
use \AMAPBundle\Entity\PanierProduit;
use \AMAPBundle\Entity\Produit;
use \AMAPBundle\Entity\Saison;
use \AMAPBundle\Entity\Acteur;
use \AMAPBundle\Entity\Adresse;
use \AMAPBundle\Entity\Amap;
use \AMAPBundle\Entity\TypeActeur;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class AmapController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamedBuilder('formulaire_creation_amap')
            ->add('libelle',TextType::class)
			->add('numRue',TextType::class)
            ->add('typeVoie', TextType::class )
            ->add('nomVoie', TextType::class )
            ->add('ville', TextType::class )
            ->add('cp', TextType::class )
            ->add('ajouter', SubmitType::class, array('label' => 'Créer amap'))
            ->getForm();

        if ($form->handleRequest($request)->isSubmitted()){
           if ($form->get('ajouter')->isClicked())
           {
                $data = $form->getData();

                $amap = new Amap();

				$amap->setLibelle($data['libelle']);

				$adresse = new Adresse();

                $adresse->setNumRue($data['numRue']);
                $adresse->setTypeVoie($data['typeVoie']);
                $adresse->setNomVoie($data['nomVoie']);
                $adresse->setville($data['ville']);
                $adresse->setCp($data['cp']);

				$amap->setAdresse($adresse);

				$em->persist($adresse);
                $em->persist($amap);

                $em->flush();

                //return $this->redirect($this->generateUrl('amap_panier_ajouter'));
           }

        }

        $listAmap = $em->getRepository('AMAPBundle:Amap')->findAll();

        return $this->render('AMAPBundle:Amap:index.html.twig',array(
            'form' => $form->createView(),
            'page_courante' => 'amap',
            'listamap' => $listAmap));
    }
}