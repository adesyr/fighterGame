<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PersonFormController extends AbstractController
{

    // *************************CREATION DU FORMULAIRE D'AJOUT****************
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {


    //Instanciation d'un personnage
        $person = new Person();


     // Génération du formulaire depuis la classe Person
        $form = $this->createForm(PersonType::class, $person);

     // Requete pour récupérer les donnnées dans le formaulaire
        $form->handleRequest($request);

     // Vérifications
        if ($form->isSubmitted()) {     // si le formulaire a été envoyé
            if ($form->isValid()) {    // si les données sont valides

     //Pour manipuler les entités
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

     // message flash : message en session destiné à n'être affiché qu'une seule fois
     //on l'injecte dans template base pour affichage pour chaque page
                $this->addFlash('success', 'Personnage bien enregistré');

     // on peut rediriger vers une autre page de cette manière
     // return $this->redirectToRoute('user_list');
            }
            else {
                $this->addFlash('danger', 'Le formulaire n\'est pas valide');
            }
        }

        // on passe la vue du formulaire au template
        return $this->render('person/create_person.html.twig', [
            'formPerson' => $form->createView(),
            'person' => $person
        ]);

    }
//**************RECHERCHE EN BDD POUR AFFICHER LES PERSONNAGES ******************
    /**
     * @Route("/findAll", name="find_all")
     */
    public function findAll(Request $request) {
        $em = $this->getDoctrine()->getManager();
        //getRepository permet de récupérer entités en argument
        $figthers = $em->getRepository('App:Person')->findAll();

        // générer une 404 si aucun article en base ne correspond
        if ($figthers == null) {
            throw new NotFoundHttpException();
        }

        return $this->render('person/person_list.html.twig', ['person' => $figthers]);
    }



}
