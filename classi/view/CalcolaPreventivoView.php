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
            <div data-order="1" class="tipo-infisso container-order">
                <p class="step">1. Seleziona il tipo di infisso</p>
                <div class="porta box order" data-type="tipo-infisso" data-name="Portafinestra">Portafinestra</div>
                <div class="finestra box order" data-type="tipo-infisso" data-name="Finestra">Finestra</div>
                <div class="clear"></div>
            </div>
            <div data-order="2" class="selezione-ante hidden container-order"></div>
            
            <input class="ricerca clear hidden" type="button" name="ricerca-infissi" value="Prosegui">
           
            <div data-order="3" class="selezione-infissi container-order hidden"></div>
            <div data-order="4" class="selezione-misure hidden container-order"></div>
            <div data-order="5" class="selezione-apertura container-order hidden">
                <p class="step">5. Seleziona il tipo di apertura</p>
                
                <div class="box box-lungo interna order" data-type="apertura" data-name="interna"><p>Interna (10% in più da listino)</p><span>telaio e anta in tubolare 40x30 mm</span></div>
                <div class="separator"></div>
                <div class="box box-lungo esterna order" data-type="apertura" data-name="esterna"><p>Esterna</p></div>
                <div class="separator"></div>
                <div class="box fx order" data-type="apertura" data-name="fissa"><p>Fissa</p></div>
                
                <div class="clear last"></div>
                <p class="description">Nel caso venga selezionata "APERTURA INTERNA", fare click sulla voce corrispondente anche nelle maggiorazioni</p>
            </div>
            <div data-order="6" class="seleziona-anta-principale container-order hidden"></div>
                            
            <div  data-order="7" class="clear container-barra container-order hidden">
                <p class="step">7. PARTICOLARI COSTRUTTIVI: BARRA</P>
                <div class="box barra-tonda order" data-type="barra" data-name="barra-tonda">
                    <p>Barra tonda con &#248; 
                        <select name="barra-tonda">
                            <option value=" ">0</option>
                            <option value="14">14 mm</option>
                            <option value="16">16 mm</option>
                            <!-- <option value="18">18 mm</option> -->
                        </select>     
                        Per indicare il diametro 18mm selezionarlo nella voce maggiorazioni.
                    </p>
                </div>
                <div class="box barra-quadrata order" data-type="barra" data-name="barra-quadrata">
                    <p>Barra quadrata con &#248; 
                        <select name="barra-quadrata">
                            <option value=" ">0</option>
                            <option value="14">14 mm</option>
                            <!-- <option value="16">16 mm</option> -->
                        </select>      
                        Per indicare il diametro 16mm selezionarlo nella voce maggiorazioni.
                    </p>
                </div>
                <div class="clear last"></div>
            </div>
            <div data-order="8" class="clear container-serratura container-order hidden">
                <p class="step">8. PARTICOLARI COSTRUTTIVI: SERRATURA</p>
                <div class="box nessuna order fissa" data-type="serratura" data-name="nessuna">
                    <p>
                       Nessuna
                    </p>
                </div>                
                <div class="box serratura-leva order no-fissa" data-type="serratura" data-name="leva">
                    <p>
                        A leva con maniglia
                        <span>a leva con maniglia standard in pvc nera</span>
                    </p>
                </div>
                <div class="box serratura-cilindro order no-fissa" data-type="serratura" data-name="cilindro">
                    <p>
                        Solo cilindro
                        <span>tipo cilindro europeo, telaio e anta in tubolare 40x30 mm</span>
                    </p>
                </div>
                <div class="clear last"></div>
            </div>
            <div  data-order="9" class="clear container-nodo container-order hidden">
                <p class="step">9. PARTICOLARI COSTRUTTIVI: NODO</p>
                <div class="box nodo-b order" data-type="nodo" data-name="B">
                    <p>Nodo B</p>
                </div>
                <div class="box nodo-d order" data-type="nodo" data-name="D">
                    <p>Nodo D</p>
                </div>
                <div class="box nodo-l order" data-type="nodo" data-name="L">
                    <p>Nodo L</p>
                </div>
                <div class="box nodo-g order" data-type="nodo" data-name="G">
                    <p>Nodo G</p>
                </div>
                <div class="box nodo-altro order" data-type="nodo" data-name="nessuno">
                    <p>Nessun nodo</p>
                </div>
                <div class="clear last"></div>
            </div>

            <div data-order="10" class="clear selezione-colore container-order hidden">
                <p class="step">10. PARTICOLARI COSTRUTTIVI: COLORE</p>
                <!-- SELEZIONE RAL -->
                <p class="descrizione">
                    <span class="title">RAL</span>
                    Colori preferenziali disponibili in tonalità lucida o opaca<br>
                    <strong>Se il RAL desiderato non è presente nel box sottostante, è necessario:<br>- Selezionare nel box sottostante "RAL PERSONALIZZATO".<br>- Scrivere il RAL voluto nella casella di testo che apparirà a fianco. <br>- Fare click sulla voce corrispondete nelle maggiorazioni.</strong>
                </p>
                <div class="ral-box selettore-box order">
                    <div class="selettore-show">
                        <div class="none" data-type="colore" data-name="none">seleziona RAL</div>
                    </div>
                    <div class="selettore-arrow"></div>
                </div>
                <div class="select-ral">
                    <div class="none" data-type="colore" data-name="none">seleziona RAL</div>
                    <div class="none personalizzato" data-type="colore" data-name="ral personalizzato">RAL PERSONALIZZATO</div>
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
                <div class="ral-personalizzato hidden">
                    <input type="text" name="ral-personalizzato" placeholder="RAL PERSONALIZZATO" value="" />
                </div>
                <div class="tipo-ral">
                    <input type="radio" name="tipo-ral-1" value="lucido" checked /><label>Lucido</label>
                    <input type="radio" name="tipo-ral-1" value="opaco" /><label>Opaco</label>
                </div>
                <div class="tipo-verniciatura tipo-verniciatura-ral">
                    <p class="descrizione"><span class="subtitle">Verniciatura</span></p>
                    <input type="radio" name="verniciatura-ral-1" value="cataforesi" checked /><label>Cataforesi (compresa nel prezzo)</label>
                    <div class="clear"></div>
                    <input type="radio" name="verniciatura-ral-1" value="zincatura" /><label>Zincatura (15% in più da listino)</label>
                    <div class="clear"></div>
                </div>
                <div class="clear last"></div>
                
                <!-- SELEZIONE MICACEI -->
                <p class="descrizione"><span class="title">MICACEI</span></p>

                <div class="micacei clear container-order">
                    <div class="box scuro-gr" data-type="colore" data-name="Grigio micaceo scuro gr">
                        <p>Grigio micaceo scuro gr</p>
                    </div>
                    <div class="box marmo" data-type="colore" data-name="Verde rangrizzato marmo">
                        <p>Verde rangrizzato marmo</p>
                    </div>
                    <div class="box nero-opaco" data-type="colore" data-name="Nero raggrinzato opaco">
                        <p>Nero raggrinzato opaco</p>
                    </div>
                    <div class="box nero-antracite" data-type="colore" data-name="Nero raggrinzato antracite">
                        <p>Nero raggrinzato antracite</p>
                    </div>
                    <div class="box marrone" data-type="colore" data-name="Marrone raggrinzato brown">
                        <p>Marrone raggrinzato brown</p>
                    </div>
                    <div class="box asfalto" data-type="colore" data-name="Grigio raggrinzato asfalto">
                        <p>Grigio raggrinzato asfalto</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="tipo-verniciatura tipo-verniciatura-micaceo">
                    <p class="descrizione"><span class="subtitle">Trattamento</span></p>
                    <input type="radio" name="verniciatura-micaceo-1" value="cataforesi" checked /><label>Cataforesi (compresa nel prezzo)</label>
                    <div class="clear"></div>
                    <input type="radio" name="verniciatura-micaceo-1" value="zincatura" /><label>Zincatura (15% in più da listino)</label>
                    <div class="clear"></div>
                </div>
                
                <p class="descrizione clear"><span class="title">ALTRO</span></p>
                <div class="box-zincatura clear container-order">
                    <div class="box zincatura" data-type="colore" data-name="solo zincatura">
                        <p>Solo zincatura</p>
                    </div>
                    <div class="clear"></div>
                </div>

                <input type="hidden" name="colore-scelto" value="" />


            </div>

            <div  data-order="11" class="clear selezione-cerniera container-order hidden">
                <p class="step">11. CERNIERA</p>
                <div class="box normale order fissa" data-type="cerniera" data-name="nessuna">
                    <p>Nessuna</p>
                </div>
                <div class="box normale order no-fissa" data-type="cerniera" data-name="normale">
                    <p>Normale<br><span style="font-size:0.7em">senza collo</span></p>
                </div>
                <div class="box collo-standard order no-fissa" data-type="cerniera" data-name="collo-standard-3cm">
                    <p>Collo standard 3 cm</p>
                </div>
                <div class="box collo-allungato order no-fissa" data-type="cerniera" data-name="collo-allungato">
                    <p>Collo allungato X = <input type="number" name="collo-allungato" step="0.1" min="0" value="0" /> cm</p>                   
                    <span class="help" title="Info"></span>
                    <div class="help-window help-cerniera hidden"><span class="close"></span></div>
                </div>
                <div class="clear"></div>
            </div>
            
            
            <!-- totale pulito infisso -->
            <input type="hidden" name="totale-infisso" value="" />
            
            <div  data-order="12" class="clear maggiorazioni container-order hidden">
                <p class="step">12. Aggiungi eventuali maggiorazioni</p>
                <?php
                    //trovo le maggiorazioni
                    $maggiorazioni = $this->tMaggiorazioni->getMaggiorazioni();
                    if(count($maggiorazioni) > 0){                    
                        foreach($maggiorazioni as $item){
                            $m = new Maggiorazione();
                            $m = $item;
                            
                            $uMisura = str_replace('E', '€', $m->getUnitaMisura());
                    ?>
                            <div class="box-2 order" data-type="maggiorazione" data-name="<?php echo $m->getID() ?>">
                                <div class="nome">
                                    <?php echo $m->getNome() ?>
                                </div>
                                <div class="valore">
                                    + <?php echo $m->getQuantita() ?> <?php echo $uMisura ?>
                                    <?php if($uMisura == '€'){echo ' netti a pezzo ';} ?>
                                    da listino
                                </div>
                                <div style="float:none; clear:both; width:100%;"></div>
                                <input type="hidden" name="maggiorazione-qt" value="<?php echo $m->getQuantita() ?>"/>
                                <input type="hidden" name="maggiorazione-um" value="<?php echo $uMisura ?> " />
                            </div>
                              
                    <?php
                        }
                    ?>
                       
                    <?php
                    }
                    else{
                    ?>
                        <p class="descrizione">Maggiorazioni non presenti</p>
                    <?php
                    }
                ?>
            </div>
            <div  data-order="12" class="numero-infissi clear hidden">
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
