<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


$temp_path = "/in_elaborazione/wp-test/"; //LOCALE
include $_SERVER['DOCUMENT_ROOT'].$temp_path.'/wp-load.php';

require_once '../classi/classes.php';


add_action('wp_ajax_my_action', 'pps_ajax_callback');


pps_ajax_callback();

function pps_ajax_callback(){
    $tprezzi = new TabellaPrezziController();
    
    if(isset($_POST['id_type'])){
        //riconosciuto il parametro POST che identifica il tipo di infisso
        //si può avere id_type = F per una finestra e id_type = P per una portafinestra
        //bisogna eseguire una query che ritorni il numero di ante a seconda tel type
        
        $parameters['type'] = $_POST['id_type'];
        unset($_POST['id_type']);
        
        $result = $tprezzi->getAnte($parameters);
        echo json_encode($result);      
    }
    
    if(isset($_POST['id_type_2']) && isset($_POST['ante'])){
        
        $parameters['type'] = $_POST['id_type_2'];
        $parameters['ante'] = $_POST['ante'];
        
        unset($_POST['id_type_2']);
        unset($_POST['ante']);
        
        $result = $tprezzi->getTabelleByParameters($parameters);
        echo json_encode($result);
    }
    
    if(isset($_POST['id_tabella'])){
        //ottengo i dati relativi ad una determinata tabella
        $result = $tprezzi->getTabellaById($_POST['id_tabella']);
        unset($_POST['id_tabella']);
        echo json_encode($result);
    }
    
    
    if(isset($_POST['id_tabella_2']) && isset($_POST['row']) && isset($_POST['col'])){
        
        $result = $tprezzi->getPrezzo($_POST['id_tabella_2'], $_POST['row'], $_POST['col']);
        unset($_POST['col']);
        unset($_POST['row']);
        unset($_POST['id_tabella_2']);
        
        echo json_encode($result);
    }
    
    
    if(isset($_POST['preventivo'])){
        
        global $DIR_TEMP_IMG_PREVENTIVI;
        global $DIR_TEMP_IMG_PREVENTIVI_THUMB;
        global $DIR_IMG_PREVENTIVI;
        global $DIR_IMG_PREVENTIVI_THUMB;
        global $URL_IMG_PREVENTIVI;
        
        $pController = new PreventivoController();
        $preventivo = $_POST['preventivo'];
        
        $p = new Preventivo();
        $p = $pController->convertToPreventivo($preventivo);
        
        //1. salvare i dati nel database        
        //2. comporre il pdf 
        //3. creare la mail e allegarci il pdf
                
        $result = array();
        
        //1. salvo il preventivo nel database
        $idPreventivo = $pController->savePreventivo($p);
        if($idPreventivo != false){
            $result['salvato'] = true;
            
            //1a. Salvo le immagini del preventivo nel DB
            $temp = $p->getFoto();
            //ho ottenuto un array di nomi di foto
            //potrebbero anche non esserci foto, in quel caso $temp = null
            if($temp != null){
                foreach($temp as $item){
                    $foto = new Foto();
                    $foto->setIdPreventivo($idPreventivo);
                    $foto->setNomeFoto($item);                
                    //salvo la voto
                    if($pController->saveFoto($foto) != false){
                        //la foto è stata salvata.
                        //devo copiare la foto dalla cartella temporanea alla cartella definitiva                    
                        copy($DIR_TEMP_IMG_PREVENTIVI.$item, $DIR_IMG_PREVENTIVI.$item);
                        copy($DIR_TEMP_IMG_PREVENTIVI_THUMB.$item, $DIR_IMG_PREVENTIVI_THUMB.$item);
                        //devo eliminare la foto e il thumb dalla cartella temporanea
                        unlink($DIR_TEMP_IMG_PREVENTIVI_THUMB.$item);
                        unlink($DIR_TEMP_IMG_PREVENTIVI.$item);        
                    }
                }
            }
            
            //2. compongo il pdf
            $pdf = $pController->createPDF($idPreventivo);           
            
            if($pdf['url'] != false){                
                //inserisco l'url del pdf nel record del preventivo appena salvato
                $pController->updateUrlPdf($idPreventivo, $pdf['url']);
                $result['pdf'] = $pdf['url'];  
                
                //3. Invio della mail
                if($pController->sendEmailtoAdmin($idPreventivo, $pdf['dir'])){
                    $result['mail'] = true;
                }
                else{
                    $result['mail'] = false;
                }                
            }
            else{
                $result['pdf'] = false;
            }
        }
        else{
            $result['salvato'] = false;
        }
        
        echo json_encode($result);
        
        
        
    }
    
    //ascoltatore sul cancella file 
    if(isset($_POST['nomeFile'])){
        global $DIR_TEMP_IMG_PREVENTIVI;
        global $DIR_TEMP_IMG_PREVENTIVI_THUMB;
        
        //rimuovo il thumbnail
        unlink($DIR_TEMP_IMG_PREVENTIVI_THUMB.$_POST['nomeFile']);
        unlink($DIR_TEMP_IMG_PREVENTIVI.$_POST['nomeFile']);
        
    }
    
}

        
       

?>