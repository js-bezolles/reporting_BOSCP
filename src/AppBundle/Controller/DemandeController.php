<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Demande;
use AppBundle\Form\BetweenDateSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * Demande controller.
 *
 * @Route("demande")
 */
class DemandeController extends Controller
{

    /**
     *
     * @Route("/partenaire/{id}", name="demande_partenaire",  requirements={"id": "\d+"})
     * @Method("GET")
     *
     */
    public function filtrePartenaireAction($id)
    {
        $demandes = $this->getDctrine()->getRepository('AppBundle:Partenaire')->findDemandeByPartenaire($id,null, null);
        return $this->render('demande/table.html.twig', array(
                'demandes' => $demandes)
        );
    }

    /**
     *
     * @Route("/export/", name="export_liste_demande", options={"expose"=true})
     * @Method("GET")
     *
     */
    public function generateCsvAction(){
        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();
            $results = $em->getRepository('AppBundle:Demande')->findByUser($this->getUser(),null,null);

            $handle = fopen('php://output', 'r+');

            foreach ($results as $demande) {
                fputcsv(
                    $handle,
                    [$demande->getId(), $demande->getNumDemande(), $demande->getCodEtatDemande(),
                        $demande->getCodeOrigine(), $demande->getIndConfirmPro(), $demande->getNumEpj(),
                        $demande->getNatPro(), $demande->getDateTrt()->format('Y-m-d H:i:s'),
                        $demande->getDateReception()->format('Y-m-d H:i:s'),
                        $demande->getModerateur(), $demande->getImLabelDd()
                    ],
                    ';'
                );
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
    }

    /**
     *
     * @Route("/export/csv", name="export_liste_demande_filtre", options={"expose"=true})
     * @Method("GET")
     *
     */
    public function generateCsvFiltreAction(){
        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();
            $results = $em->getRepository('AppBundle:Demande')->findByUserAndByDate($this->getUser(),null,null,null,null);

            $handle = fopen('php://output', 'r+');

            foreach ($results as $demande) {
                $dateTrt = "";
                $dateRcpt = "";
                if($demande->getDateTrt() !=null)
                    $dateTrt = $demande->getDateTrt()->format('Y-m-d H:i:s');
                if($demande->getDateReception() != null)
                    $dateRcpt = $demande->getDateReception()->format('Y-m-d H:i:s');

                fputcsv(
                    $handle,
                    [$demande->getId(), $demande->getNumDemande(), $demande->getCodEtatDemande(),
                        $demande->getCodeOrigine(), $demande->getIndConfirmPro(), $demande->getNumEpj(),
                        $demande->getNatPro(), $dateTrt, $dateRcpt,
                        $demande->getModerateur(), $demande->getImLabelDd()
                    ],
                    ';'
                );
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
    }

    /**
     * Lists all demande entities.
     *
     * @Route("", name="demande_index",options={"expose"=true})
     *
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $offset = 0;
        $limit = 1000;
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $form = $this->createForm(new BetweenDateSearchType(),$data);
//            ->add('recherche', 'submit', array('attr' => array('class' => 'btn btn-primary glyphicon glyphicon-search')));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $dataResult = $form->getData();

                $demandesTraitees = $em->getRepository('AppBundle:Demande')->findByUserAndByDate($user,$dataResult,$offset,$limit,true);
                $demandesEnCours = $em->getRepository('AppBundle:Demande')->findByUserAndByDate($user,$dataResult,$offset,$limit,false);

                return $this->render('demande/index.html.twig', array(
                    'demandes' => array (
                        array('data' => $demandesTraitees, 'label' => 'Traitées', 'id' => 'traitees'),
                        array('data' => $demandesEnCours, 'label' => 'En Cours', 'id' => 'enCours')
                    ),
                    'form' => $form->createView()
                ));
            }
        }
        $demandes = $em->getRepository('AppBundle:Demande')->findByUser($user,$offset,$limit);

        return $this->render('demande/index.html.twig', array(
            'demandes' => array (
                array('data' => $demandes, 'label' => 'Toutes les demandes', 'id' => 'all'),
            ),
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a demande entity.
     *
     * @Route("/show/{id}", name="demande_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(Demande $demande)
    {

        return $this->render('demande/show.html.twig', array(
            'demande' => $demande,
        ));
    }

    /**
     *
     * @Route("/traitees", name="demandes_traitees")
     * @Method("GET")
     */
    public function indexTraiteesAction()
    {

        $em = $this->getDoctrine()->getManager();

        $demandes = $em->getRepository('AppBundle:Demande')->findAllTraitees();

        return $this->render('demande/index.html.twig', array(
            'demandes' => $demandes,
        ));
    }


    /**
     *
     * @Route("/encours", name="demandes_en_cours")
     * @Method("GET")
     */
    public function indexEnCoursAction()
    {

        $em = $this->getDoctrine()->getManager();

        $demandes = $em->getRepository('AppBundle:Demande')->findAllEnCours();

        return $this->render('demande/index.html.twig', array(
            'demandes' => $demandes,
        ));
    }

    /**
     *
     * @Route("/homeTopPanelTraitees", name="home_top_panel_traitees", options={"expose"=true})
     * @Method("GET")
     */
    public function calculStatsTraitees(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $day = \DateTime::createFromFormat('Y-m-d',$request->get('date'));
            $idPartenaire = $request->get('idPartenaire');
        }else {
            $day = new \DateTime();
            $idPartenaire = '0';
        }

        $em = $this->getDoctrine()->getManager();

//        $traiteesVeille = $em->getRepository('AppBundle:Demande')->findNbTaitees($day, $idPartenaire);
        $traiteesVeilleModere = $em->getRepository('AppBundle:Demande')->findNbTaiteesModere($day, $idPartenaire);
        $traiteesVeilleAuto = $em->getRepository('AppBundle:Demande')->findNbTraiteesAuto($day);
        $traiteesVeille = $traiteesVeilleModere + $traiteesVeilleAuto;
        $traiteesVeilleAJ = $em->getRepository('AppBundle:Demande')->findNbTaiteesAJ($day, $idPartenaire);

        $traiteesVeilleAutoPercent = '-';

        if ((int)$traiteesVeille != 0){
            $traiteesVeilleAutoPercent = number_format($traiteesVeilleAuto / $traiteesVeille, 0);
        }

        return $this->render('globals/traiteesHomeTopPanel.html.twig', array(
            'nbTraiteesVeille' => $traiteesVeille,
            'nbTraiteesVeilleAJ' => $traiteesVeilleAJ,
            'nbTraiteesVeilleModerees' => $traiteesVeilleModere,
            'nbTraiteesVeilleAuto' => $traiteesVeilleAuto,
            'traiteesVeilleAutoPercent' => $traiteesVeilleAutoPercent
        ));
    }

    /**
     *
     * @Route("/homeTopPanelEnCours", name="home_top_panel_encours", options={"expose"=true})
     * @Method("GET")
     */
    public function calculStatsEnCours(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $day = \DateTime::createFromFormat('Y-m-d',$request->get('date'));
            $idPartenaire = $request->get('idPartenaire');
        }else {
            $day = new \DateTime();
            $idPartenaire = 0;
        }

        $em = $this->getDoctrine()->getManager();

        $demandeEnCours = $em->getRepository('AppBundle:Demande')->findNbEnCours($day, $idPartenaire);
        $demandeEnCoursOlder48h = $em->getRepository('AppBundle:Demande')->findNbEnCoursOlder48h($day, $idPartenaire);
        $demandesArriveesAuj = $em->getRepository('AppBundle:Demande')->findNbArrivees($day);
        $datePlusAncienneEnCours = $em->getRepository('AppBundle:Demande')->findOlderEnCours($day);
        $avgDeltaTraitement = $em->getRepository('AppBundle:Demande')->averageDeltaTraitement($day, $idPartenaire);
        $averageDeltaDelaiTraitement = $em->getRepository('AppBundle:Demande')->averageDeltaDelaiTraitement($day, $idPartenaire);

        if ($avgDeltaTraitement == "") {
            $avgDeltaTraitement = "-";
        }

        return $this->render('globals/enCoursHomeTopPanel.html.twig', array(
            'demandeEnCours' => $demandeEnCours,
            'demandeEnCoursAuj' => $demandesArriveesAuj,
            'datePlusAncienneEnCours' => $datePlusAncienneEnCours,
            'avgDeltaTraitement' => $avgDeltaTraitement,
            'demandeEnCoursAvant48h' => $demandeEnCoursOlder48h,
            'avgDeltaDelaiTraitement' => $averageDeltaDelaiTraitement
        ));
    }

    /**
     *
     * @Route("/homeTopPanelRatio", name="home_top_panel_ratio", options={"expose"=true})
     * @Method("GET")
     */
    public function calculStatsRatio(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $day = \DateTime::createFromFormat('Y-m-d',$request->get('date'));
            $idPartenaire = $request->get('idPartenaire');
        }else {
            $day = new \DateTime();
            $idPartenaire = 0;
        }

        $em = $this->getDoctrine()->getManager();

        $traiteesVeilleModere = $em->getRepository('AppBundle:Demande')->findNbTaiteesAJ($day, $idPartenaire);
        $arriveesVeille = $em->getRepository('AppBundle:Demande')->findNbArriveesModere($day, $idPartenaire);
        $tauxTraitementVeille = 100;
        if ($arriveesVeille != 0) {
            $tauxTraitementVeille = $traiteesVeilleModere / $arriveesVeille * 100;
        }
        $tauxTraitementVeille = number_format($tauxTraitementVeille, 2);
        return $this->render('globals/ratioHomeTopPanel.html.twig', array(
            'tauxTraitementVeille' => $tauxTraitementVeille,
        ));
    }


    /**
     *
     * @Route("/veille", name="demandes_traitees_veille", options={"expose"=true})
     * @Method("GET")
     */
    public function listeDemandeVeille()
    {
        $date = date('Y-m-d');
        $day = \DateTime::createFromFormat('Y-m-d', $date);
        $day->setTime(0,0,0);
        $previous_day = clone $day;
        // substract 1 day
        $previous_day->sub(new \DateInterval('P1D'));

        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $idPartenaire = $user->getPartenaires()[0]->getId();
        }else{
            $idPartenaire = '0';
        }

        $em = $this->getDoctrine()->getManager();

//        $traiteesVeille = $em->getRepository('AppBundle:Demande')->findNbTaitees($previous_day, $idPartenaire);
        $traiteesVeilleModere = $em->getRepository('AppBundle:Demande')->findNbTaiteesModere($previous_day, $idPartenaire);
        $traiteesVeilleAuto = $em->getRepository('AppBundle:Demande')->findNbTraiteesAuto($previous_day);
        $traiteesVeille = $traiteesVeilleModere + $traiteesVeilleAuto;
        $traiteesVeilleAJ = $em->getRepository('AppBundle:Demande')->findNbTaiteesAJ($previous_day, $idPartenaire);
        $traiteesVeilleAutoPercent = '-';

        if ((int)$traiteesVeille != 0){
            $traiteesVeilleAutoPercent = number_format($traiteesVeilleAuto / $traiteesVeille, 0);
        }

        $arriveesVeille = $em->getRepository('AppBundle:Demande')->findNbArriveesModere($previous_day, $idPartenaire);
        $tauxTraitementVeille = 100;
        if($arriveesVeille != 0){
            $tauxTraitementVeille = $traiteesVeilleAJ/$arriveesVeille*100;
        }
        $tauxTraitementVeille = number_format($tauxTraitementVeille, 2);

        $demandeEnCours = $em->getRepository('AppBundle:Demande')->findNbEnCours($day, $idPartenaire);
        $demandeEnCoursOlder48h = $em->getRepository('AppBundle:Demande')->findNbEnCoursOlder48h($day, $idPartenaire);
        $demandesArriveesAuj = $em->getRepository('AppBundle:Demande')->findNbArrivees($day);
        $datePlusAncienneEnCours = $em->getRepository('AppBundle:Demande')->findOlderEnCours($day);
        $avgDeltaTraitement = $em->getRepository('AppBundle:Demande')->averageDeltaTraitement($day, $idPartenaire);
        $avgDeltaDelaiTraitement = $em->getRepository('AppBundle:Demande')->averageDeltaDelaiTraitement($day, $idPartenaire);

        if($avgDeltaTraitement == "")
        {
            $avgDeltaTraitement = "-";
        }

//        return $this->render('globals/home.html.twig');
        return $this->render('globals/home.html.twig', array(
            'tauxTraitementVeille' => $tauxTraitementVeille,
            'nbTraiteesVeille' => $traiteesVeille,
            'nbTraiteesVeilleAJ' => $traiteesVeilleAJ,
            'nbTraiteesVeilleModerees' => $traiteesVeilleModere,
            'nbTraiteesVeilleAuto' => $traiteesVeilleAuto,
            'demandeEnCours' => $demandeEnCours,
            'demandeEnCoursAvant48h' => $demandeEnCoursOlder48h,
            'demandeEnCoursAuj' => $demandesArriveesAuj,
            'datePlusAncienneEnCours' => $datePlusAncienneEnCours,
            'avgDeltaTraitement' => $avgDeltaTraitement,
            'avgDeltaDelaiTraitement' => $avgDeltaDelaiTraitement,
            'traiteesVeilleAutoPercent' => $traiteesVeilleAutoPercent
        ));
    }

    /**
     *
     * @Route("/veille/date_{date}", name="demandes_traitees_date")
     * @Method("GET")
     */
    public function listeDemandeADate($date)
    {
        $d = date('Y-m-d', strtotime($date));
        $day = \DateTime::createFromFormat('Y-m-d', $d);
        $day->setTime(0,0,0);
        $previous_day = clone $day;
        // substract 1 day
        $previous_day->sub(new \DateInterval('P1D'));

        $idPartenaire = '0';

        $em = $this->getDoctrine()->getManager();

        $traiteesVeille = $em->getRepository('AppBundle:Demande')->findNbTaitees($previous_day, $idPartenaire);
        $traiteesVeilleModere = $em->getRepository('AppBundle:Demande')->findNbTaiteesModere($previous_day, $idPartenaire);
        $traiteesVeilleAuto = $em->getRepository('AppBundle:Demande')->findNbTraiteesAuto($previous_day, $idPartenaire);
        $traiteesVeilleAJ = $em->getRepository('AppBundle:Demande')->findNbTaiteesAJ($previous_day, $idPartenaire);
        $traiteesVeilleAutoPercent = '-';

        if ((int)$traiteesVeille != 0){
            $traiteesVeilleAutoPercent = number_format($traiteesVeilleAuto / $traiteesVeille, 0);
        }

        $arriveesVeille = $em->getRepository('AppBundle:Demande')->findNbArriveesModere($previous_day, $idPartenaire);
        $tauxTraitementVeille = 100;
        if($arriveesVeille != 0){
            $tauxTraitementVeille = $traiteesVeilleAJ/$arriveesVeille*100;
        }
        $tauxTraitementVeille = number_format($tauxTraitementVeille, 2);

        $demandeEnCours = $em->getRepository('AppBundle:Demande')->findNbEnCours($day, $idPartenaire);
        $demandeEnCoursOlder48h = $em->getRepository('AppBundle:Demande')->findNbEnCoursOlder48h($day, $idPartenaire);
        $demandesArriveesAuj = $em->getRepository('AppBundle:Demande')->findNbArrivees($day, $idPartenaire);
        $datePlusAncienneEnCours = $em->getRepository('AppBundle:Demande')->findOlderEnCours($day);
        $avgDeltaTraitement = $em->getRepository('AppBundle:Demande')->averageDeltaTraitement($day, $idPartenaire);
        $avgDeltaDelaiTraitement = $em->getRepository('AppBundle:Demande')->averageDeltaDelaiTraitement($day, $idPartenaire);

        if($avgDeltaTraitement == "")
        {
            $avgDeltaTraitement = "-";
        }

//        return $this->render('globals/home.html.twig');
        return $this->render('globals/home.html.twig', array(
            'tauxTraitementVeille' => $tauxTraitementVeille,
            'nbTraiteesVeille' => $traiteesVeille,
            'nbTraiteesVeilleAJ' => $traiteesVeilleAJ,
            'nbTraiteesVeilleModerees' => $traiteesVeilleModere,
            'nbTraiteesVeilleAuto' => $traiteesVeilleAuto,
            'demandeEnCours' => $demandeEnCours,
            'demandeEnCoursAvant48h' => $demandeEnCoursOlder48h,
            'demandeEnCoursAuj' => $demandesArriveesAuj,
            'datePlusAncienneEnCours' => $datePlusAncienneEnCours,
            'avgDeltaTraitement' => $avgDeltaTraitement,
            'avgDeltaDelaiTraitement' => $avgDeltaDelaiTraitement,
            'traiteesVeilleAutoPercent' => $traiteesVeilleAutoPercent
        ));

    }

    /**
     * Lists all demande entities.
     *
     * @Route("/demande_chart_builder", name="demande_chart_builder")
     *
     */
    public function chartBuilder()
    {
        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $idPartenaire = $user->getPartenaires()[0]->getId();
        }else{
            $idPartenaire = '0';
        }

        $date = date('Y-m-d');
        $day = \DateTime::createFromFormat('Y-m-d', $date);
        $day->setTime(0,0,0);

//        $test = "test2";
        $em = $this->getDoctrine()->getManager();
        $chartLabels4Week = $em->getRepository('AppBundle:Demande')->createChartLabels($day, 28);
        $chartDatasEnCours4Week = $em->getRepository('AppBundle:Demande')->findChartDatasEnCours($day, 28, $idPartenaire);
        $chartDatasTraitees4Week = $em->getRepository('AppBundle:Demande')->findChartDatasTraitees($day, 28, $idPartenaire);

        //$chartLabels1Week = $em->getRepository('AppBundle:Demande')->createChartLabels(7);
        //$chartDatasEnCours1Week = $em->getRepository('AppBundle:Demande')->findChartDatasEnCours(7);
        //$chartDatasTraitees1Week = $em->getRepository('AppBundle:Demande')->findChartDatasTraitees(7);

        $chartDatasRatio = $em->getRepository('AppBundle:Demande')->findChartDatasRatio($day, 28, $idPartenaire);


        return $this->render('demande/chart.html.twig', array(
                'chartLabels4Week' => $chartLabels4Week,
                'chartDatasEnCours4Week' => $chartDatasEnCours4Week,
                'chartDatasTraitees4Week' => $chartDatasTraitees4Week,
                'chartLabelsRatio' => $chartLabels4Week,
                'chartDatasRatio' => $chartDatasRatio
            )
        );
    }
}
