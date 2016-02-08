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
                               
            <p class="step">1. Seleziona il tipo di infisso</p>
            <div class="porta box">porta</div>
            <div class="finestra box">finestra</div>
            <div class="selezione-ante"></div>
            <input class="ricerca" type="button" name="ricerca-infissi" value="Prosegui">
            <div class="selezione-infissi"></div>
            <div class="selezione-misure"></div>
            <div class="selezione-apertura">
                <p class="step">5. Seleziona il tipo di apertura</p>
                <div class="box si"><p>Sinistra interna</p></div>
                <div class="box se"><p>Sinistra esterna</p></div>
                <div class="box di"><p>Destra interna</p></div>
                <div class="box de"><p>Destra esterna</p></div>
            </div>
            <div class="particolari-costruttivi clear">
                <h3>Particolari Costruttivi</h3>
                <div class="clear container-barra">
                    <p class="step">6. BARRA</P>
                    <div class="box barra-tonda">
                        <p>Barra tonda con &#248; 
                            <select name="barra-tonda">
                                <option value="16">16</option>
                                <option value="18">18</option>
                            </select>                    
                        </p>
                    </div>
                    <div class="box barra-quadrata">
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
                    <div class="box serratura-leva">
                        <p>
                          A leva con maniglia in pvc
                        </p>
                    </div>
                    <div class="box serratura-cilindro">
                        <p>
                          Solo cilindro
                        </p>
                    </div>
                </div>
                <div class="clear container-nodo">
                    <p class="step">8. NODO</p>
                    <div class="box nodo-b">
                        <p>Nodo B</p>
                    </div>
                    <div class="box nodo-d">
                        <p>Nodo D</p>
                    </div>
                    <div class="box nodo-l">
                        <p>Nodo L</p>
                    </div>
                    <div class="box nodo-g">
                        <p>Nodo G</p>
                    </div>
                    <div class="box nodo-altro">
                        <p>Altro</p>
                    </div>
                </div>
                
                <div class="clear selezione-colore">
                    <p class="step">9. COLORE</p>
                    <p>RAL<br>Colori preferenziali disponibili in tonalità lucida o opaca</p>
                    <div class="selettore-box">
                        <div class="selettore-show">
                            <div class="none">seleziona RAL</div>
                        </div>
                        <div class="selettore-arrow"></div>
                    </div>
                    <div class="select-ral">
                        <div class="none">seleziona RAL</div>
                        <div class="ral-9016">RAL 9016</div>
                        <div class="ral-9010">RAL 9010</div>
                        <div class="ral-7035">RAL 7035</div>
                        <div class="ral-9006">RAL 9006</div>
                        <div class="ral-7040">RAL 7040</div>                        
                        <div class="ral-7030">RAL 7030</div>
                        <div class="ral-9007">RAL 9007</div>                        
                        <div class="ral-7016 text-white">RAL 7016</div>                        
                        <div class="ral-8028 text-white">RAL 8028</div>                        
                        <div class="ral-8003 text-white">RAL 8003</div>
                        <div class="ral-8001 text-white">RAL 8001</div>
                        <div class="ral-6005 text-white">RAL 6005</div>
                        <div class="ral-6009 text-white">RAL 6009</div>
                        <div class="ral-3003 text-white">RAL 3003</div>
                        <div class="ral-1013">RAL 1013</div>
                        <div class="ral-9005 text-white">RAL 9005</div>
                    </div>
                    <p>MICACEI</p>
                    <div class="box scuro-gr">
                        <p>Grigio micaceo scuro gr</p>
                    </div>
                    <div class="box marmo">
                        <p>Verde rangrizzato marmo</p>
                    </div>
                    <div class="box opaco">
                        <p>Nero raggrinzato opaco</p>
                    </div>
                    <div class="box antracite">
                        <p>Nero raggrinzato antracite</p>
                    </div>
                    <div class="box brown">
                        <p>Marrone raggrinzato brown</p>
                    </div>
                    <div class="box asfalto">
                        <p>Grigio raggrinzato asfalto</p>
                    </div>
                </div>
                
                <div class="clear selezione-cerniera">
                    <p class="step">10. CERNIERA</p>
                    <div class="box normale">
                        <p>Normale</p>
                    </div>
                    <div class="box collo-standard">
                        <p>Collo standard 3 cm</p>
                    </div>
                    <div class="box collo-allungato">
                        <p>Collo allungato<br>X = <input type="text" name="collo-allungato" value="" /> cm</p>
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
                            <div class="box-2 maggiorazione-<?php echo $m->getID() ?>">
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
        
    <?php    
    }
    
    
    
    
    
}
