<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{


// *************** RECHERCHE DE 2 JOUEURS ALEATOIRES POUR LE COMBAT **************************
    /**
     * @Route("/fight", name="fight")
     */
    public function fight(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        if ($session->has('$fighter1')) {
            //recherche aléatoire
            $fighters = $em->getRepository('App:Person')->findAll();
            $nbFighters = count($fighters);

            if ($nbFighters > 1) {
                $firstFighter = rand(0, $nbFighters-1);
                do {
                    $secondFighter = rand(0, $nbFighters - 1);
                } while ($secondFighter == $firstFighter);

                $fighter1 = $fighters[$firstFighter];
                $fighter2 = $fighters[$secondFighter];
            } else {
                $this->addFlash('danger', 'Pas assez de combattant en base');
                $fighter1 = null;
                $fighter2 = null;
            }

        }


        //mettre en session les id des combattants grace a l'objet request

        $session->set('fighter1', $fighter1->getId());
        $session->set('fighter2', $fighter2->getId());

        return $this->render('person/fight_view.html.twig', ['fighter1' => $fighter1, 'fighter2' => $fighter2]);
    }


    /**
     * @Route("/attack/{fighter1Id}/{fighter2Id}", name="attack")
     */
    public function attack($fighter1Id,$fighter2Id) {
        $em = $this->getDoctrine()->getManager();
        $fighter1 = $em->getRepository('App:Person')->find($fighter1Id);
        $fighter2 = $em->getRepository('App:Person')->find($fighter2Id);

        $fighter1->attack($fighter2);
        $em->flush();



        return $this->render('person/fight_view.html.twig', ['fighter1' => $fighter1, 'fighter2' => $fighter2]);
    }

    /**
     * @Route("/heal/{fighterId}, name="heal")
     */
    public function heal($fighterId) {
        $em = $this->getDoctrine()->getManager();
        $fighter = $em->getRepository('App:Person')->find($fighterId);


        $fighter->heal();
        $em->flush();



        return $this->redirectToRoute('fight');
    }
}
