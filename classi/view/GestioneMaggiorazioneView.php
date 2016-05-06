<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * La classe gestisce le maggiorazioni 
 *
 * @author Alex
 */
class GestioneMaggiorazioneView {
    
    private $mController;
        
    function __construct() {
        $this->mController = new MaggiorazioneController();
    }

    
    //Metodi
    
    /**
     * Stampa l'header
     */
    public function printHeader(){
    ?>
        <div class="header">
            <h2>Gestione Maggiorazioni</h2>
            <p>Questa sezione è dedicata alla gestione delle Maggiorazioni</p>
        </div>
    <?php
    }
    
    /**
     * Form di generazione maggiorazione
     */
    public function printFormGenerazioneMaggiorazione(){
    ?>
        

        <form class="inserisci-maggiorazione" action="<?php echo admin_url().'admin.php?page=gestione_maggiorazioni'; ?>" method="POST">
            <div class="fascia-titolo form">
                <h3>Inserisci maggiorazione</h3>  
            </div>
            <div class="container-dati-maggiorazione">
                <div class="row">
                    <div class="field nome">                        
                        <label>Nome Maggiorazione</label><br>
                        <input type="text" name="nome" value="" style="width:100%" required />
                    </div>
                    <div class="field">  
                        <label>Quantità</label><br>
                        <input type="text" name="quantita" value="" style="text-align: right" required />
                    </div>  
                    <div class="field">                         
                        <label>Unità di misura</label><br>
                        <select name="unita">
                            <option value="%">%</option>
                            <option value="E">€</option>
                        </select>
                    </div>
                </div>
                <div class="clear">
                    <div class="field">
                        <input type="submit" name="aggiungi-maggiorazione" value="Aggiungi" />
                    </div>
                </div>                
            </div>
        </form>
    <?php        
    }
    
    /**
     * Ascoltatore generazione maggiorazione
     * @return boolean
     */
    public function listenerGenerazioneMaggiorazione(){
        $nomeMaggiorazione = isset($_POST['nome']) ? stripslashes($_POST['nome']) : null;
        $qtMaggiorazione = isset($_POST['quantita']) ? intval($_POST['quantita']) : null;
        $unitaMaggiorazione = isset($_POST['unita']) ? stripslashes($_POST['unita']) : null;
        
        if($nomeMaggiorazione == null || $qtMaggiorazione == null || $unitaMaggiorazione == null){
            return false;
        }
        
        //salvo le informazioni
        $m = new Maggiorazione();
        $m->setNome($nomeMaggiorazione);
        $m->setQuantita($qtMaggiorazione);
        $m->setUnitaMisura($unitaMaggiorazione);
        
        if(!$this->mController->saveMaggiorazione($m)){
            echo '<p class="error">salvataggio non andato a buon fine<p>';
            return false;
        }
        
        return false;        
    }
    
    public function printTableMaggiorazioni(){
        //ottengo le maggiorazioni
        $maggiorazioni = $this->mController->getMaggiorazioni();
        
        if(count($maggiorazioni) == 0){
            echo '<p>Non ci sono maggiorazioni da visualizzare</p>';
            return false;
        }
        
    ?>  
        <div class="container-tabella maggiorazioni">
        <table class="table">
            <tr class="row-title">
                <td>Nome maggiorazione</td>
                <td>Quantità</td>
                <td>Azione</td>
            </tr>
    <?php
        foreach($maggiorazioni as $maggiorazione){
            $m = new Maggiorazione();
            $m = $maggiorazione;
    ?>
            <tr class="row-data">
                <td>
                    <?php echo $m->getNome(); ?>
                </td>
                <td>
                    <?php echo $m->getQuantita().' '.str_replace('E', '€', $m->getUnitaMisura()) ?>
                </td>
                <td>
                    <form action="<?php echo admin_url().'admin.php?page=gestione_maggiorazioni'; ?>" method="POST">  
                        <input type="hidden" name="id-maggiorazione" value="<?php echo $m->getID() ?>" />
                        <input type="submit" name="cancella-maggiorazione" value="cancella" />
                    </form>
                </td>
            </tr>
    <?php        
        }
    ?>
        </table>   
        </div>
        
    <?php     
    }
    
    public function listenerTableMaggiorazioni(){
        $id = intval($_POST['id-maggiorazione']);
        
        if(!$this->mController->deleteMaggiorazione($id)){
            echo '<p class="error">Errore nella cancellazione</p>';
        }
    }
}
