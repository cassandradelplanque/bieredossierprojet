<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CallApiService;
use App\Entity\Note;
use App\Repository\NoteRepository;


class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(CallApiService $callApiService): Response
    {
        //compter les pages
        $taille=0;
        $pagecompteur=1;
        $valdetest= $callApiService->getBierepourcompter($pagecompteur);
        while ($valdetest != null){
           $taille+=sizeof($valdetest);
           $pagecompteur+=1;
           $valdetest= $callApiService->getBierepourcompter($pagecompteur);


        }
       
        
        //déterminer la page sur laquelle on se trouve
        if (isset($_GET['page'])){
           $chaine1=$_GET['chaine1'];
           $chaine2=$_GET['chaine2'];
            if ($_GET['sens']==0){
                if ($_GET['page']!=1){
                    $page=$_GET['page']-1;
                    
                }
                else{
                    $page=$_GET['page'];
                }
                
            }
            else{
                $page=$_GET['page']+1;
            }
            
        }
        else{
            $page=1;
            $chaine1="";
            $chaine2="";
        }
        
        //prendre en compte les requetes de recherche et former une chaine de caractère avec
        if(isset($_POST['name'])||isset($_POST['malt'])||isset($_POST['hops'])||isset($_POST['yeast'])){
            
            $chaine1="";
        }
        if(isset($_POST['name'])){
            $page=1;
           
            if ($_POST['name']!=null){
                $chaine1.="&beer_name=". $_POST['name'];
               
                
            } 
            
        } 
        if(isset($_POST['malt'])){
            $page=1;
           
            if ($_POST['malt']!=null){
                $chaine1.="&malt=". $_POST['malt'];
               
                
            } 
            
        } 
        if(isset($_POST['hops'])){
            $page=1;
            
            if ($_POST['hops']!=null){
                $chaine1.="&hops=". $_POST['hops'];
               
                
            } 
            
        } 
        if(isset($_POST['yeast'])){
            $page=1;
            
            if ($_POST['yeast']!=null){
                $chaine1.="&yeast=". $_POST['yeast'];
               
                
            } 
            
        }
        
        
        if (isset($_POST['valeurnote'])){
            
            
            $page=1;
            if($_POST['valeurnote']!=0 && $_POST['valeurnote']!=1){
            $repository = $this->getDoctrine()->getRepository(Note::class);
            $notation= $repository->findBy(['perso' =>   $this->getUser(), 'val' => $_POST['valeurnote']]);
            $chaine2="&ids=";
            foreach ($notation as $valeur){
                $chaine2.=$valeur->getBiereId();
                $chaine2.="|";
               
            }
            
            
           
          
            
            }
            if ($_POST['valeurnote']==1){
               
            $repository = $this->getDoctrine()->getRepository(Note::class);
            $notation= $repository->findByopppose( $this->getUser(), $_POST['valeurnote']);
            $chaine2="&ids=";
            $number=0;
            for($n=1;$n<=$taille;$n++){ 
                $variabletest=0; 
                foreach ($notation as $aenlever){
                    $code=$aenlever->getBiereId();
                    if ($code == $n ){
                        $variabletest=1;
                    }
                    
                }
                if ($variabletest==0){
                    $chaine2.=$n.'|';
                }
            }
            
           
            
                
        
            
        }
        if ($_POST['valeurnote']==0){
            $chaine2="";
        }
        
       
        }
        $pagesuivante=$page+1;
        $texte="page=".$page;
        $texte.=$chaine1;
        $texte.=$chaine2;
        //recupération des informations sur les bières en faisant une requetes basé sur la chaine formée plus tot 
        $recup= $callApiService->getBieresnom($texte);
        $texte2="page=".$pagesuivante;
        $texte2.=$chaine1;
        $texte2.=$chaine2;
        //test si il peut y avoir une page suivante 
        $test=1;
        if ($callApiService->getBieresnom($texte2)==null){
            $test=0;
        }


        return $this->render('home/index.html.twig', [
            'recup'=>$recup,
            'page'=>$page,
            'chaine1'=>$chaine1,
            'chaine2'=>$chaine2,
            'test'=>$test
        ]);
    }
    /**
     * @Route("/fiche", name="home_fiche", methods={"GET"})
     */
    public function fiche(CallApiService $callApiService): Response
    {
       
        $recup=$callApiService->getBierebyid($_GET['beer']);
        
        
        $repository = $this->getDoctrine()->getRepository(Note::class);
        $notation= $repository->findBy(['perso' =>   $this->getUser(), 'biere_id' =>  $_GET['beer'] ]);
        $note= 'Inconnue';
        $test=-1;
        if ($notation!=null){
            $note=$notation[0]->getVal();
            $test=$notation[0]->getId();

        }
         
        return $this->render('home/fiche.html.twig', [
            'recup'=>$recup,
            'note'=>$note,
            'test'=>$test

        ]);
    }
    /**
     * @Route("/fichehasard", name="home_fichehasard", methods={"GET"})
     */
    public function Hasard(CallApiService $callApiService): Response
    {
       
        $recup=$callApiService->getBierehasard();
        $repository = $this->getDoctrine()->getRepository(Note::class);
        $notation= $repository->findBy(['perso' =>   $this->getUser(), 'biere_id' =>  $recup[0]['id'] ]);
        $note= 'Inconnue';
        $test=-1;
        if ($notation!=null){
            $note=$notation[0]->getVal();
            $test=$notation[0]->getId();

        }
   
        return $this->render('home/fiche.html.twig', [
            'recup'=>$recup,
            'note'=>$note,
            'test'=>$test

        ]);
    }

    
}
