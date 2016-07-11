<?php


/**
 * Description of TrasportoView
 *
 * @author Alex
 */
class TrasportoView extends PrinterView {
    private $cTrasporto;
    
    function __construct() {
        parent::__construct();
        $this->cTrasporto = new TrasportoController();
    }
    
    /**
     * Stampa il form di inserimento dei trasporti
     * @global type $TRAS_FORM_SUBMIT
     * @global type $TRAS_LABEL_SUBMIT
     * @global type $FORM_AREA
     * @global type $LABEL_AREA
     * @global type $FORM_PREZZO
     * @global type $LABEL_PREZZO
     */
    public function printForm(){
        global $TRAS_FORM_SUBMIT, $TRAS_LABEL_SUBMIT, $FORM_AREA, $LABEL_AREA, $FORM_PREZZO, $LABEL_PREZZO;
        
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-trasporto" method="POST" >
            <div class="col-xs-12 col-sm-6">
                <?php parent::printTextFormField($FORM_AREA, $LABEL_AREA, true); ?>
                <?php parent::printTextFormField($FORM_PREZZO, $LABEL_PREZZO, true); ?>

            </div>            
            <div class="clear"></div>
            <?php parent::printSubmitFormField($TRAS_FORM_SUBMIT, $TRAS_LABEL_SUBMIT) ?>
            <div class="clear"></div>
        </form>
    <?php
        
    }
    
    /**
     * Ascoltatore del form sul
     * @global type $TRAS_FORM_SUBMIT
     * @global type $FORM_AREA
     * @global type $FORM_PREZZO
     */
    public function listenerForm(){
        global $TRAS_FORM_SUBMIT, $FORM_AREA, $FORM_PREZZO;
        
        if(isset($_POST[$TRAS_FORM_SUBMIT])){
            
            if(isset($_POST[$FORM_AREA]) && $_POST[$FORM_AREA] != '' && isset($_POST[$FORM_PREZZO]) && $_POST[$FORM_PREZZO] != ''){
                //creo l'oggetto trasporto
                $trasporto = new Trasporto();
                $trasporto->setArea($_POST[$FORM_AREA]);
                $trasporto->setPrezzo($_POST[$FORM_PREZZO]);
                
                //salvo il trasporto nel database
                if($this->cTrasporto->saveTrasporto($trasporto) == false){
                    //errore
                     parent::printErrorBoxMessage('Trasporto non salvato nel Sistema!');
                }
                else{
                    //salvato
                    parent::printOkBoxMessage('Trasporto salvato con successo!');
                    //in caso di successo, tolgo dal post i dati in memoria
                    unset($_POST);
                }
            }
            
            
        }        
    }
    
    /**
     * Funzione che genera l'html specifco di Trasporti per la tabella di visualizzazione
     * @param type $array
     * @param type $actions
     * @return string
     */
    protected function printBodyTable($array, $actions=null) {
        parent::printBodyTable($array);
        $html = "";
        foreach($array as $item){
            $t = new Trasporto();
            $t = $item;
    
            $html.= '<tr>';
            $html.= '<td>'.$t->getID().'</td>';
            $html.= '<td>'.$t->getArea().'</td>';
            $html.= '<td>'.$t->getPrezzo().'</td>';
            $html.= '</tr>';   
        }        
        return $html;
    }

    /**
     * Funzione che stampa la tabella dei trasporti
     * @global type $LABEL_AREA
     * @global type $LABEL_PREZZO
     */
    public function printTableTrasporti(){
        global $LABEL_AREA, $LABEL_PREZZO;

        //ottengo i trasporti
        $trasporti = $this->cTrasporto->getTrasporti(1);
                
        $header = array('ID', $LABEL_AREA, $LABEL_PREZZO);
        $bodyTable = $this->printBodyTable($trasporti);        
        
        parent::printTableHover($header, $bodyTable, null);  
        
    }
    
    
    public function printSelectTrasporti(){
        global $TRAS_FORM_SELECT, $TRAS_LABEL_SELECT;
        parent::printSelectFormField($TRAS_FORM_SELECT, $TRAS_LABEL_SELECT, $this->cTrasporto->getTrasporti(0), true);
    }
    
    
   
    

}
