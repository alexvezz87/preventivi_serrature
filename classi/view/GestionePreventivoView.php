<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GestionePreventiviView
 *
 * @author Alex
 */
class GestionePreventivoView {
    
    private $pController;
    
    
    function __construct() {
        $this->pController = new PreventivoController();
    }

    /**
     * Stampa l'header
     */
    public function printHeader(){
    ?>
        <h2>Gestione Preventivi</h2>
        <p>Questa sezione è dedicata alla gestione dei preventivi</p>
    <?php
    }
    
    /**
     * La funzione stampa la tabella dei preventivi non visionati
     */
    public function printPreventiviNonVisionati(){       
        
        //ottengo tutti i preventivi non visionati
        $parameters['visionato'] = 0;
        $parameters['order'] = 'ID';
        $parameters['type-order'] = 'ASC';
       
        $preventivi = $this->pController->getPreventivi($parameters);
        
        $this->printTable($preventivi, 0);
        
    }
    
    
       
    public function printPreventiviVisionati(){
         //ottengo tutti i preventivi non visionati
        $parameters['visionato'] = 1;
        $parameters['order'] = 'data_visionato';
        $parameters['type-order'] = 'DESC';
        $parameters['limit'] = 10;
        
        
        $preventivi = $this->pController->getPreventivi($parameters);
        
              
        $this->printTable($preventivi, 1);
    }
    
    
    private function printTable($preventivi, $visionato){
        global $URL_IMG;    
        
        //se visionato == 0 --> tabella dei non visionati
        //se visionato == 1 --> tabella dei visionati
        
        if(count($preventivi) > 0 && $preventivi != false){
    ?>
            <table border="1" >
                <tr class="row-title">
                    <td>ID</td>
                    <td>Data</td>
                    <td>Rivenditore/Agente</td>
                    <td>Nome Cliente</td>
                    <td>Indirizzo Cliente</td>
                    <td>Telefono Cliente</td>
                    <td>Prezzo Totale</td>
                    <td>PDF</td>
                    <td>AZIONE</td>    
                </tr>
    <?php    
            foreach($preventivi as $item){                
                $p = new Preventivo();
                $p = $item;
                //ottengo i dati del rivenditore/agente
                $nomeRivenditore = "";
                if($p->getIdUtente() != 0){
                    $user_info = get_userdata($p->getIdUtente());
                    $nomeRivenditore = $user_info->display_name; 
                }
                
    ?>
                <tr class="row-data">
                    <td><?php echo $p->getId() ?></td>
                    <td><?php echo $this->getTime($p->getData()) ?></td>
                    <td><?php echo $nomeRivenditore ?></td>                    
                    <td><?php echo $p->getClienteNome() ?></td>
                    <td><?php echo $p->getClienteVia() ?></td>
                    <td><?php echo $p->getClienteTel() ?></td>
                    <td>&euro; <?php echo $p->getSpesaTotale() ?></td>
                    <td>
    <?php
                    if($p->getPdf() != null && $p->getPdf() != ''){
    ?>
                        <a target="_blank" href="<?php echo $p->getPdf() ?>">
                            <img alt="pdf" src="<?php echo $URL_IMG ?>ico_pdf.png" />
                        </a>
    <?php                    
                    }
    ?>
                    </td>
                    <td>
                        <form action="<?php echo admin_url().'admin.php?page=gestione_preventivi'; ?>" method="POST" >
                             <input type="hidden" name="idPreventivo" value="<?php echo $p->getId() ?>" />
    <?php
                if($visionato == 0){
    ?>                  
                            <input type="submit" name="visionato" value="VISIONATO" />                    
    <?php
                }
    ?>                  
                            <input type="submit" name="cancella-preventivo" value="Cancella" />
                         </form>  
                    </td>
                </tr>
    <?php
            }
    ?>
            </table>
    <?php                
        }
        else{
    ?>
            <p> Non ci sono preventivi da visualizzare </p>
    <?php
        }
    }
    
    public function listenerNonVisionati(){
        $idPreventivo = isset($_POST['idPreventivo']) ? stripslashes($_POST['idPreventivo']) : null;
        $this->pController->setPreventivoVisionato($idPreventivo);
        
    }
    
    public function listenerCancella(){
         $idPreventivo = isset($_POST['idPreventivo']) ? stripslashes($_POST['idPreventivo']) : null;
         if(!$this->pController->deletePreventivo($idPreventivo)){
             echo '<p class="error">Errore nella cancellazione del preventivo</p>';
         }
    }
    
    /**
     * Funzione che converte il timestamp in un tempo leggibile
     * @param type $time
     * @return type
     */
    private function getTime($time){
        //viene passata una data nella forma aaaa-mmm-dd hh:mm:ss (es. 2015-09-13 16:30:40)
        //devo restituire gg/mm/aaaa hh:mm

        $temp = explode(' ', $time);
        $time1 = explode('-', $temp[0]);
        $time2 = explode(':', $temp[1]);

        return $time1[2].'/'.$time1[1].'/'.$time1[0].' '.$time2[0].':'.$time2[1];
    }
}
