<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * La classe gestisce i prezzi 
 *
 * @author Alex
 */
class GestionePrezziView {
    
    private $tPrezzi;
    
    function __construct() {
        $this->tPrezzi = new TabellaPrezziController();
    }
    
    //metodi
    
    /**
     * Funzione che stampa l'header della pagina
     */
    public function printHeader(){
    ?>
        <h2>Gestione Prezzi</h2>
        <p>Questa sezione è dedicata alla compilazione dinamica dei prezzi per i vari articoli</p>
    <?php
       
    
    }
    
    /**
     * Funzione che stampa il form di generazione tabella
     */
    public function printFormGenerazioneTabella(){
     
    ?>
        <form action="<?php echo admin_url().'admin.php?page=gestione_prezzi'; ?>" method="POST">
            <div>
                <label>Nome Articolo</label>
                <input type="text" value="" name="nameTable" required />
                <label> Numero ante</label>
                <select name="ante" >
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <div>
                <label>Altezza iniziale</label>
                <input type="text" value="" name="startRows" required />
                <label>Altezza Finale</label>
                <input type="text" value="" name="endRows" required />
                <label>Altezza step</label>
                <input type="text" value="" name="stepRows" required />
            </div>
            <div>
                <label>Lunghezza iniziale</label>
                <input type="text" value="" name="startCols" required />
                <label>Lunghezza Finale</label>
                <input type="text" value="" name="endCols" required />
                <label>Lunghezza step</label>
                <input type="text" value="" name="stepCols" required />
            </div>
            <div>
                <label>Prezzo iniziale</label>
                <input type="text" value="" name="prezzo-iniziale" required />
                <label>Tasso di incremento (%)</label>
                <input type="text" value="" name="incremento" required />
            <div>
                <input type="submit" value="Genera Tabella" name="genera-tabella-articolo" />
            </div>
        </form>
    <?php
    }
    
    /**
     * Funzione che fa da listener al form di generazione tabella
     * Ha lo scopo di salvare nel database tutti i campi possibili generati
     */
    public function listenerFormGenerazioneTabella(){
        //si suppone che il listener sia contenuto esternamente da un if 
        //che guarda se il genera-tabella-articolo è stato istanziato
        
        //Elaboro i dati
        $startRows = isset($_POST['startRows']) ? intval($_POST['startRows']) : null;
        $endRows = isset($_POST['endRows']) ? intval($_POST['endRows']) : null;
        $stepRows = isset($_POST['stepRows']) ? intval($_POST['stepRows']) : null;
        $startCols = isset($_POST['startCols']) ? intval($_POST['startCols']) : null;
        $endCols = isset($_POST['endCols']) ? intval($_POST['endCols']) : null;
        $stepCols = isset($_POST['stepCols']) ? intval($_POST['stepCols']) : null;
        
        $prezzoIniziale = isset($_POST['prezzo-iniziale']) ? floatval($_POST['prezzo-iniziale']) : null;
        $incremento = isset($_POST['incremento']) ? ($_POST['incremento']) : null;
        
        $nameTable = isset($_POST['nameTable']) ? strip_tags($_POST['nameTable']) : null;
        $ante = isset($_POST['ante']) ? strip_tags($_POST['ante']) : null;
        
        if($startRows == null || $endRows == null || $stepRows == null || $startCols == null || $endCols == null || $stepCols == null || $nameTable == null || $ante == null){
            echo '<p class="error">non tutti i campi sono stati compilati<p>';
            return false;
        }
        
        //Salvo le informazioni della tabella generando un oggetto Tabella
        $tabella = new Tabella();
        $tabella->setNomeTabella($nameTable);
        $tabella->setStartRows($startRows);
        $tabella->setEndRows($endRows);
        $tabella->setStepRows($stepRows);
        $tabella->setStartCols($startCols);
        $tabella->setEndCols($endCols);
        $tabella->setStepCols($stepCols);
        $tabella->setAnte($ante);
        $tabella->setPrezzoIniziale($prezzoIniziale);
        $tabella->setIncremento($incremento);
        
        //salvo l'oggetto nel database
        if(!$this->tPrezzi->saveTabellaArticolo($tabella)){
            echo '<p class="error">salvataggio non andato a buon fine<p>';
            return false;
        }
        
        return true;
    }
    
    /**
     * La funzione stampa a video le tabelle degli articoli già presenti nel database
     */
    public function printTabelleArticoli(){
        //ottengo le tabelle in forma di array
        $tabelle = $this->tPrezzi->getTabelleArticoli();
        
        if(count($tabelle) == 0){
            echo '<p>Non ci sono tabelle di articoli da visualizzare</p>';
            return false;
        }
        
        
        foreach($tabelle as $tItem){
            $tabella = new Tabella();
            $tabella = $tItem;
            $colspan = intval(($tabella->getEndCols() - $tabella->getStartCols()) / $tabella->getStepCols()) + 1;
            $rowspan = intval(($tabella->getEndRows() - $tabella->getStartRows()) / $tabella->getStepRows()) + 1;
        ?>
            <form action="<?php echo admin_url().'admin.php?page=gestione_prezzi'; ?>" method="POST">
                <input type="hidden" name="id-tabella" value="<?php echo $tabella->getId() ?>" />
                <h3><?php echo $tabella->getNomeTabella() ?></h3>
                <p>Numero Ante: <?php echo $tabella->getAnte() ?></p>
                <table class="tabella-articolo">
                    <!-- prima riga -->
                    <tr>
                        <td>
                            H / L
                        </td>
                        <?php for($i=$tabella->getStartCols(); $i <= $tabella->getEndCols(); $i+=$tabella->getStepCols()){ ?>
                        <td class="title-cols">
                            <?php echo $i ?>
                        </td>
                        <?php } ?>
                    </tr>
                    <!-- fine prima riga -->
                    <!-- seconda riga -->
                    <tr>
                        <td class="title-rows">
                            <?php echo $tabella->getStartRows() ?>
                        </td>
                        <td colspan='<?php echo $colspan ?>' rowspan="<?php echo $rowspan ?>">
                            <?php echo $this->printTabellaPrezzi($tabella->getPrezzi(), $rowspan, $colspan) ?>
                        </td>
                    </tr>
                    <!-- fine seconda riga -->
                    <!-- altre righe -->
                    <?php for($j=($tabella->getStartRows() + $tabella->getStepRows()); $j <= $tabella->getEndRows(); $j+=$tabella->getStepRows()) { ?>
                    <tr>
                        <td class="title-rows">
                            <?php echo $j ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <!-- fine altre righe -->
                </table>
                <div>
                    <input type="submit" value="AGGIORNA PREZZI" name="aggiorna-prezzi" />
                    <input type="submit" value="CANCELLA TABELLA" name="cancella-tabella" />
                </div>
            </form>
        <?php    
        }        
    }
    
    /**
     * La funzione stampa a video i prezzi di una tabella articoli
     */
    private function printTabellaPrezzi($prezzi, $rows, $cols){    
        
        echo '<table class="tabella-prezzi">';
    
        $countItem = 0;
        
        foreach ($prezzi as $pItem){
            $countItem++;
            $prezzo = new Prezzo();
            $prezzo = $pItem;
            if($countItem == 1){
                echo '<tr>';
            }
            echo '<td><label>&euro;</label><input type="text" name="cella-'.$prezzo->getValRow().'-'.$prezzo->getValCol().'" value="'.$prezzo->getPrezzo().'" /></td>';        
        
            if($countItem == $cols){
                echo '</tr>';
                $countItem = 0;
            }             
        }
        
        echo '</table>';    
    } 
    
    public function listenerUpdateTabella(){
        $idTabella = $_POST['id-tabella'];
        
        foreach($_POST as $key => $value){
            if (strpos($key, 'cella') !== FALSE){
                $temp = explode('-', $key);
                $row = $temp[1];
                $col = $temp[2];
                
                $prezzo = new Prezzo();
                $prezzo->setIdTabella($idTabella);
                $prezzo->setValCol($col);
                $prezzo->setValRow($row);
                $prezzo->setPrezzo($value);
                
                if(!$this->tPrezzi->updatePrezzoArticolo($prezzo)){                   
                    echo '<p class="error">Errore!</p>';
                }
                               
            }
           
        }
    }
    
    public function listenerDeleteTabella(){
        $idTabella = $_POST['id-tabella'];
        if($this->tPrezzi->deleteTabellaPrezzi($idTabella)){
            echo '<p class="ok">Tabella eliminata con successo</p>';
        }
        else{
            echo '<p class="error">Errore nella cancellazione</p>';
        }
    }
}