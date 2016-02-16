<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CalcolaPreventivoView
 *
 * @author Alex
 */
class CalcolaPreventivoView {
    
    private $tPrezzi;
    private $tMaggiorazioni;
    
    function __construct() {
        $this->tPrezzi = new TabellaPrezziController();
        $this->tMaggiorazioni = new MaggiorazioneController();
    }

    //METODI
    
    public function printSelezioneInfisso(){
    ?>    
            <h3 class="title-infisso">Infisso N. <span>1</span></h3>       
            <p class="step">1. Seleziona il tipo di infisso</p>
            <div class="porta box" data-type="tipo-infisso" data-name="porta">porta</div>
            <div class="finestra box" data-type="tipo-infisso" data-name="finestra">finestra</div>
            <div class="selezione-ante"></div>
            <input class="ricerca" type="button" name="ricerca-infissi" value="Prosegui">
            <div class="selezione-infissi"></div>
            <div class="selezione-misure"></div>
            <div class="selezione-apertura">
                <p class="step">5. Seleziona il tipo di apertura</p>
                <div class="box si" data-type="apertura" data-name="sinistra-interna"><p>Sinistra interna</p></div>
                <div class="box se" data-type="apertura" data-name="sinistra-esterna"><p>Sinistra esterna</p></div>
                <div class="box di" data-type="apertura" data-name="destra-interna"><p>Destra interna</p></div>
                <div class="box de" data-type="apertura" data-name="destra-esterna"><p>Destra esterna</p></div>
            </div>
            <div class="particolari-costruttivi clear">
                <h3>Particolari Costruttivi</h3>
                <div class="clear container-barra">
                    <p class="step">6. BARRA</P>
                    <div class="box barra-tonda" data-type="barra" data-name="barra-tonda">
                        <p>Barra tonda con &#248; 
                            <select name="barra-tonda">
                                <option value="16">16</option>
                                <option value="18">18</option>
                            </select>                    
                        </p>
                    </div>
                    <div class="box barra-quadrata" data-type="barra" data-name="barra-quadrata">
                        <p>Barra quadrata con &#248; 
                            <select name="barra-quadrata">
                                <option value="14">14</option>
                                <option value="16">16</option>
                            </select>                    
                        </p>
                    </div>
                </div>
                <div class="clear container-serratura">
                    <p class="step">7. SERRATURA</p>
                    <div class="box serratura-leva" data-type="serratura" data-name="leva">
                        <p>
                          A leva con maniglia in pvc
                        </p>
                    </div>
                    <div class="box serratura-cilindro" data-type="serratura" data-name="cilindro">
                        <p>
                          Solo cilindro
                        </p>
                    </div>
                </div>
                <div class="clear container-nodo">
                    <p class="step">8. NODO</p>
                    <div class="box nodo-b" data-type="nodo" data-name="B">
                        <p>Nodo B</p>
                    </div>
                    <div class="box nodo-d" data-type="nodo" data-name="D">
                        <p>Nodo D</p>
                    </div>
                    <div class="box nodo-l" data-type="nodo" data-name="L">
                        <p>Nodo L</p>
                    </div>
                    <div class="box nodo-g" data-type="nodo" data-name="G">
                        <p>Nodo G</p>
                    </div>
                    <div class="box nodo-altro" data-type="nodo" data-name="altro">
                        <p>Altro</p>
                    </div>
                </div>
                
                <div class="clear selezione-colore">
                    <p class="step">9. COLORE</p>
                    <!-- SELEZIONE RAL -->
                    <p>RAL<br>Colori preferenziali disponibili in tonalità lucida o opaca</p>
                    <div class="ral-box selettore-box">
                        <div class="selettore-show">
                            <div class="none" data-type="colore" data-name="none">seleziona RAL</div>
                        </div>
                        <div class="selettore-arrow"></div>
                    </div>
                    <div class="select-ral">
                        <div class="none" data-type="colore" data-name="none">seleziona RAL</div>
                        <div class="ral-9016" data-type="colore" data-name="ral-9016">RAL 9016</div>
                        <div class="ral-9010" data-type="colore" data-name="ral-9010">RAL 9010</div>
                        <div class="ral-7035" data-type="colore" data-name="ral-7035">RAL 7035</div>
                        <div class="ral-9006" data-type="colore" data-name="ral-9006">RAL 9006</div>
                        <div class="ral-7040" data-type="colore" data-name="ral-7040">RAL 7040</div>                        
                        <div class="ral-7030" data-type="colore" data-name="ral-7030">RAL 7030</div>
                        <div class="ral-9007" data-type="colore" data-name="ral-9007">RAL 9007</div>                        
                        <div class="ral-7016 text-white" data-type="colore" data-name="ral-7016">RAL 7016</div>                        
                        <div class="ral-8028 text-white" data-type="colore" data-name="ral-8028">RAL 8028</div>                        
                        <div class="ral-8003 text-white" data-type="colore" data-name="ral-8003">RAL 8003</div>
                        <div class="ral-8001 text-white" data-type="colore" data-name="ral-8001">RAL 8001</div>
                        <div class="ral-6005 text-white" data-type="colore" data-name="ral-6005">RAL 6005</div>
                        <div class="ral-6009 text-white" data-type="colore" data-name="ral-6009">RAL 6009</div>
                        <div class="ral-3003 text-white" data-type="colore" data-name="ral-3003">RAL 3003</div>
                        <div class="ral-1013" data-type="colore" data-name="ral-1013">RAL 1013</div>
                        <div class="ral-9005 text-white" data-type="colore" data-name="ral-9005">RAL 9005</div>
                    </div>
                    
                    <!-- SELEZIONE MICACEI -->
                    <p>MICACEI</p>
                    
                    <div class="micacei-box selettore-box">
                        <div class="selettore-show">
                            <div class="none" data-type="colore" data-name="none">selezione Micacei</div>
                        </div>
                        <div class="selettore-arrow"></div>
                    </div>
                    <div class="select-micacei">
                        <div class="none" data-type="colore" data-name="none">selezione Micacei</div>
                        <div class="scuro-gr" data-type="colore" data-name="micaceo-scuro-gr">Grigio micaceo scuro gr</div>
                        <div class="marmo" data-type="colore" data-name="micaceo-marmo">Verde rangrizzato marmo</div>
                        <div class="opaco" data-type="colore" data-name="micaceo-opaco">Nero raggrinzato opaco</div>
                        <div class="antracite" data-type="colore" data-name="micaceo-antracite">Nero raggrinzato antracite</div>
                        <div class="brown" data-type="colore" data-name="micaceo-brown">Marrone raggrinzato brown</div>
                        <div class="asfalto" data-type="colore" data-name="micaceo-asfalto">Grigio raggrinzato asfalto</div>
                    </div>
                    
                    
                </div>
                
                <div class="clear selezione-cerniera">
                    <p class="step">10. CERNIERA</p>
                    <div class="box normale" data-type="cerniera" data-name="normale">
                        <p>Normale</p>
                    </div>
                    <div class="box collo-standard" data-type="cerniera" data-name="collo-standard-3cm">
                        <p>Collo standard 3 cm</p>
                    </div>
                    <div class="box collo-allungato" data-type="cerniera" data-name="collo-allungato">
                        <p>Collo allungato<br>X = <input type="text" name="collo-allungato" value="0" /> cm</p>
                    </div>
                </div>
            </div>
            
            <!-- totale pulito infisso -->
            <input type="hidden" name="totale-infisso" value="" />
            
            <div class="clear maggiorazioni">
                <p class="step">11. Aggiungi eventuali maggiorazioni</p>
                <?php
                    //trovo le maggiorazioni
                    $maggiorazioni = $this->tMaggiorazioni->getMaggiorazioni();
                    if(count($maggiorazioni) > 0){                    
                        foreach($maggiorazioni as $item){
                            $m = new Maggiorazione();
                            $m = $item;
                    ?>
                            <div class="box-2" data-type="maggiorazione" data-name="<?php echo $m->getID() ?>">
                                <div class="nome">
                                    <?php echo $m->getNome() ?>
                                </div>
                                <div class="valore">
                                    + <?php echo $m->getQuantita() ?> <?php echo $m->getUnitaMisura() ?>
                                    <?php if($m->getUnitaMisura() == '€'){echo ' netti a pezzo ';} ?>
                                    da listino
                                </div>
                                <div style="float:none; clear:both; width:100%;"></div>
                                <input type="hidden" name="maggiorazione-qt" value="<?php echo $m->getQuantita() ?>"/>
                                <input type="hidden" name="maggiorazione-um" value="<?php echo $m->getUnitaMisura() ?> " />
                            </div>
                              
                    <?php
                        }
                    ?>
                       
                    <?php
                    }
                    else{
                    ?>
                        <p>Maggiorazioni non presenti</p>
                    <?php
                    }
                ?>
            </div>
            <div class="numero-infissi clear">
                <label>Di quanti infissi di questo tipo hai bisogno?</label>
                <input type="number" name="numero-infissi" value="1" min="1" />                
            </div>
            
            <!-- Spesa complessiva infisso -->
            <div class="spesa-parziale-infisso">
                <input type="hidden" value="" name="spesa-parziale-infisso" />  
                <p class="step">Spesa per questo infisso: &euro; <span class="spesa-infisso">0</span></p>
            </div>
        
    <?php    
    }
    
    
    
    
    
}
