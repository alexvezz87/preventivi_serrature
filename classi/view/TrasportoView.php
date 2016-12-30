<?php


/**
 * Description of TrasportoView
 *
 * @author Alex
 */
class TrasportoView extends PrinterView {
    private $cTrasporto;
    private $form, $label;
    
    function __construct() {
        parent::__construct();
        $this->cTrasporto = new TrasportoController();
        
        global $TRAS_FORM_SUBMIT, $TRAS_LABEL_SUBMIT, $FORM_AREA, $LABEL_AREA, $FORM_PREZZO, $LABEL_PREZZO, $TRAS_FORM_SELECT, $TRAS_LABEL_SELECT;
        
        $this->form['submit'] = $TRAS_FORM_SUBMIT;
        $this->form['area'] = $FORM_AREA;
        $this->form['prezzo'] = $FORM_PREZZO;
        $this->form['select'] = $TRAS_FORM_SELECT;
        
        $this->label['submit'] = $TRAS_LABEL_SUBMIT;
        $this->label['area'] = $LABEL_AREA;
        $this->label['prezzo'] = $LABEL_PREZZO;
        $this->label['select'] = $TRAS_LABEL_SELECT;
    }
    
    /**
     * Stampa il form di inserimento dei trasporti
     */
    public function printForm(){  
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="form-trasporto" method="POST" >
            <div class="col-xs-12 col-sm-6">
                <?php parent::printTextFormField($this->form['area'], $this->label['area'], true); ?>
                <?php parent::printTextFormField($this->form['prezzo'], $this->label['prezzo'], true); ?>

            </div>            
            <div class="clear"></div>
            <?php parent::printSubmitFormField($this->form['submit'], $this->label['submit']) ?>
            <div class="clear"></div>
        </form>
    <?php
        
    }
    
    /**
     * Ascoltatore del form dei trasporti
     */
    public function listenerForm(){
        
        //salva il trasporto
        if(isset($_POST[$this->form['submit']])){
            
            if(isset($_POST[$this->form['area']]) && $_POST[$this->form['area']] != '' && isset($_POST[$this->form['prezzo']]) && $_POST[$this->form['prezzo']] != ''){
                //creo l'oggetto trasporto
                $trasporto = new Trasporto();
                $trasporto->setArea($_POST[$this->form['area']]);
                $trasporto->setPrezzo($_POST[$this->form['prezzo']]);
                
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
        
        //cancella il trasporto
        if(isset($_POST['delete-trasporto'])){
           
            $idTrasporto = $_POST['id']; 
            if($this->cTrasporto->deleteTrasporto($idTrasporto) == false){
                parent::printErrorBoxMessage('Cancellazione non avvenuta!');
                unset($_POST);
            }
            else{
                parent::printOkBoxMessage('Trasporto cancellato con successo!');
                unset($_POST);
            }
            
        }
        
        
        //aggiorna trasporto
        if(isset($_POST['update-trasporto'])){
            $idTrasporto = $_POST['id'];
            //creo un oggetto trasporto nuovo
            $t = new Trasporto();
            $t = $this->cTrasporto->getTrasporto($idTrasporto);
            $t->setPrezzo($_POST[$this->form['prezzo']]);
            //Aggiorno
            if($this->cTrasporto->updateTrasporto($t)==false){
                parent::printErrorBoxMessage('E\' subentrato un errore nell\'aggiornamento del campo'.$this->label['prezzo']);
                unset($_POST);
            }
            else{
                parent::printOkBoxMessage('Campo '.$this->label['prezzo'].' aggiornato correttamente!');
                unset($_POST);
            }
            
        }
        
    }
    
    /**
     * Funzione che genera l'html specifco di Trasporti per la tabella di visualizzazione
     * @param type $array
     * @param type $actions
     * @return string
     */
    protected function printBodyTable($array, $actions=false) {
        parent::printBodyTable($array);
       
        $html = "";
        foreach($array as $item){
            $t = new Trasporto();
            $t = $item;
            $htmlActions = ""; 
            if($actions != false){
                $htmlActions = parent::printDeleteForm($t->getID(), 'trasporto');
            }
    
            $html.= '<tr>';           
            $html.= '<td>'.$t->getArea().'</td>';
            $html.= '<td>'.parent::printUpdateFieldForm($t->getID(), 'trasporto', parent::printTextField($this->form['prezzo'], $t->getPrezzo(), true)).'</td>';            
            $html.= '<td>'.$htmlActions.'</td>';
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

        //ottengo i trasporti
        $trasporti = $this->cTrasporto->getTrasporti(1);
                
        $header = array($this->label['area'], $this->label['prezzo'], 'Azioni');
        $bodyTable = $this->printBodyTable($trasporti, true);        
        
        parent::printTableHover($header, $bodyTable);  
        
    }
    
    /**
     * Stampa a video la select box di trasporti
     */
    public function printSelectTrasporti($value=null){       
        parent::printSelectFormField($this->form['select'], $this->label['select'], $this->cTrasporto->getTrasporti(0), true, $value);
    }
    
    
    function getForm() {
        return $this->form;
    }

    function getLabel() {
        return $this->label;
    }

    function setForm($form) {
        $this->form = $form;
    }

    function setLabel($label) {
        $this->label = $label;
    }


    

}
