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
    
}

        
       

?>