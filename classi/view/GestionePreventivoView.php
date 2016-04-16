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
        $parameters['tipo'] = 0;
        $parameters['type-order'] = 'ASC';
       
        $preventivi = $this->pController->getPreventivi($parameters);
        
        $this->printTable($preventivi, 0);
        
    }
     
       
    public function printPreventiviVisionati(){
         //ottengo tutti i preventivi non visionati
        $parameters['visionato'] = 1;
        $parameters['tipo'] = 0;
        $parameters['order'] = 'data_visionato';
        $parameters['type-order'] = 'DESC';
        $parameters['limit'] = 10;
        
        
        $preventivi = $this->pController->getPreventivi($parameters);
        
              
        $this->printTable($preventivi, 1, true);
    }
    
    public function printOrdiniNonVisionati(){       
        
        //ottengo tutti i preventivi non visionati
        $parameters['visionato'] = 0;
        $parameters['tipo'] = 1;
        $parameters['order'] = 'ID';
        $parameters['type-order'] = 'ASC';
       
        $preventivi = $this->pController->getPreventivi($parameters);
        
        $this->printTable($preventivi, 0);
        
    }
     
       
    public function printOrdiniVisionati(){
         //ottengo tutti i preventivi non visionati
        $parameters['visionato'] = 1;
        $parameters['tipo'] = 1;
        $parameters['order'] = 'data_visionato';
        $parameters['type-order'] = 'DESC';
        $parameters['limit'] = 10;
        
        
        $preventivi = $this->pController->getPreventivi($parameters);
        
              
        $this->printTable($preventivi, 1, false, true);
    }
    
    
    private function printTable($preventivi, $visionato, $preventivo = false, $pdfSchema = false){
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
                    <td>FOTO</td>
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
                    <td><?php echo getTime($p->getData()) ?></td>
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
    <?php
                    //prendo le immagini
                    $foto = $this->pController->getFotoPreventivo($p->getId());
                    if($foto != null){
                        foreach($foto as $item){
                            $f = new Foto();
                            $f = $item;
    ?>
                        <a target="_blank" href="<?php echo $f->getUrlFoto() ?>"><img src="<?php echo $f->getUrlThumbFoto() ?>" /></a>
    <?php
                        }
                    }
                    else{
                        echo 'nessuna foto disponibile';
                    }
    ?>
                    </td>
                    <td>
                        <form action="<?php echo curPageURL(); ?>" method="POST" >
                             <input type="hidden" name="idPreventivo" value="<?php echo $p->getId() ?>" />
    <?php
                if($visionato == 0){
    ?>                  
                            <input type="submit" name="visionato" value="VISIONATO" />                    
    <?php
                }else if($visionato == 1 && $preventivo == true){
    ?>
                            <input type="submit" name="ordine" value="APPROVA ORDINE" />
    <?php
                }else if($visionato == 1 && $pdfSchema == true){
    ?>
                            <input type="submit" name="pdfSchema" value="PDF SCHEMA" />
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
    
    public function listenerPreventivo(){
        $idPreventivo = isset($_POST['idPreventivo']) ? stripslashes($_POST['idPreventivo']) : null;
        $this->pController->setPreventivoToOrdine($idPreventivo);
    }
    
}
