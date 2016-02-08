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
        <h2>Gestione Maggiorazioni</h2>
        <p>Questa sezione è dedicata alla gestione delle Maggiorazioni</p>
    <?php
    }
    
    /**
     * Form di generazione maggiorazione
     */
    public function printFormGenerazioneMaggiorazione(){
    ?>
        <form action="<?php echo admin_url().'admin.php?page=gestione_maggiorazioni'; ?>" method="POST">
            <div>
                <table>
                    <tr>
                        <td style="width:70%">
                            <label>Nome Maggiorazione</label><br>
                            <input type="text" name="nome" value="" style="width:100%" required />
                        </td>
                        <td>
                            <label>Quantità</label><br>
                            <input type="text" name="quantita" value="" style="text-align: right" required />
                        </td>
                        <td>
                            <label>Unità di misura</label><br>
                            <select name="unita">
                                <option value="%">%</option>
                                <option value="€">€</option>
                            </select>
                        </td>
                        <td>
                            <br><input type="submit" name="aggiungi-maggiorazione" value="Aggiungi" />
                        </td>
                    </tr>
                </table>
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
        
        <table class="table-maggiorazioni">
            <tr class="table-header">
                <td>Nome maggiorazione</td>
                <td>Quantità</td>
                <td>Azione</td>
            </tr>
    <?php
        foreach($maggiorazioni as $maggiorazione){
            $m = new Maggiorazione();
            $m = $maggiorazione;
    ?>
            <tr>
                <td>
                    <?php echo $m->getNome(); ?>
                </td>
                <td>
                    <?php echo $m->getQuantita().' '.$m->getUnitaMisura() ?>
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
        
    <?php     
    }
    
    public function listenerTableMaggiorazioni(){
        $id = intval($_POST['id-maggiorazione']);
        
        if(!$this->mController->deleteMaggiorazione($id)){
            echo '<p class="error">Errore nella cancellazione</p>';
        }
    }
}
