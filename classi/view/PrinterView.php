<?php


/**
 * Description of PrinterView
 *
 * @author Alex
 */
class PrinterView {
    //put your code here
    
    function __construct() {
        
    }
    
    /**
     * Funzione che stampa secondo canoni bootstrap un input text
     * @param type $nameField
     * @param type $label
     */
    protected function printTextFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php  
    }
    
    /**
     * Funzione che stampa una input text disabilitata
     * @param type $nameField
     * @param type $label
     * @param type $value
     */
    protected function printDisabledTextFormField($nameField, $label, $value){       
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" disabled />
            </div>
        </div>
    <?php  
    }
       
    /**
     * Funzione che stampa secondo i canoni bootstrap una textarea
     * @param type $nameField
     * @param type $label
     */
    protected function printTextAreaFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <textarea class="form-control" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="" <?php echo $optRequired ?>><?php echo $value ?></textarea>           
        </div>
    <?php      
        
    }
    
    protected function printNumberFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="number" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php     
    }
    
    /**
     * Funzione che stampa secondo i canoni bootstrap una input email
     * @param type $nameField
     * @param type $label
     */
    protected function printEmailFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value==null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">            
                <input class="form-control" type="email" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php      
    }
    
    /**
     * Funzione che stampa a video una input hidden
     * @param type $nameField
     * @param type $value
     */
    protected function printHiddenFormField($nameField, $value){
    ?>
        <input type="hidden" name="<?php echo $nameField ?>" value="<?php echo $value ?>" />
    <?php
    }
    
    /**
     * Funzione che stampa secondo canoni bootstrap una select box
     * @param type $nameField
     * @param type $label
     * @param type $array
     */
    protected function printSelectFormField($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="col-sm-10">
                <select name="<?php echo $nameField ?>" id="<?php echo $nameField ?>" <?php echo $optRequired ?> />
                <!-- campo vuoto -->
                <option value=""></option>
                <?php
                    foreach($array as $k => $v){
                        if($value == $k){
                            echo '<option value="'.$k.'" selected >'.$v.'</option>';
                        }
                        else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }                        
                    }
                ?>
                </select>
            </div>
        </div>
    <?php      
    }
    
    protected function printSubmitFormField($nameField, $label){
    ?>
        <input name="<?php echo $nameField ?>" type="submit" class="btn btn-success" value="<?php echo $label ?>" />
    <?php
    }
    
    
    protected function printActionDettaglio($nameField){
    ?>
        <input name="update-<?php echo $nameField ?>" type="submit" class="btn btn-primary" value="Aggiorna" />
        <input name="delete-<?php echo $nameField ?>" type="submit" class="btn btn-danger" value="Cancella" />
    <?php
    }
    
    /**
     * Stampa un box di messaggio di errore
     * @param type $message
     */
    protected function printErrorBoxMessage($message){
    ?>
        <div class="alert alert-danger">
            <strong>Errore! </strong> <?php echo $message ?>
        </div>
    <?php    
    }
    
    /**
     * Stampa un box di messaggio di ok
     * @param type $message
     */
    protected function printOkBoxMessage($message){
    ?>
        <div class="alert alert-success">
            <strong>OK! </strong> <?php echo $message ?>
        </div>
    <?php    
    }
    
    /**
     * Stampa una tabella di Bootstrap con effetto hover
     * @param type $header
     * @param type $bodyTable
     */
    protected function printTableHover($header, $bodyTable){
        //bodytable è un html del corpo della tabella
        //è diverso per ogni oggetto e viene descritto nelle classi view corrispettive
    ?>
        <table class="table table-hover">
            <thead>
                <tr>
    <?php
            foreach($header as $h){
    ?>                
                    <th><?php echo $h ?></th>
    <?php
            }
    ?>
                </tr>
            </thead>
            <tbody>
                <?php echo $bodyTable ?>
            </tbody>
        </table>
    <?php    
    }
    
    protected function printBodyTable($array){
        
    }
    
    /**
     * Restituisce un campo text in due modalità: non editabile ed editabile
     * @param type $formField
     * @param type $text
     * @param type $edit
     * @return type
     */
    protected function printTextField($formField, $text, $edit=false){
        
        $result = "";
        if($edit == true){
            //campo editabile 
           $result = '<input type="text" name="'.$formField.'" value="'.$text.'" />';
        }
        else{
            $result = $text;
        }
        
        return $result;
    }
    
    /**
     * Restituisce l'html per un piccolo form di aggiornamento di un campo
     * @param type $id
     * @param type $fieldUPDATE
     * @return string
     */
    protected function printUpdateFieldForm($id, $nameField, $fieldUPDATE){
        $html = "";
        $html.= '<form action="'.curPageURL().'" method="POST">';
        $html.= '<input type="hidden" name="id" value="'.$id.'" />';
        $html.= $fieldUPDATE;
        $html.= '<input type="submit" name="update-'.$nameField.'" class="btn btn-primary" value="AGGIORNA">';
        $html.= '</form>';
        
        return $html;
    }
    
    /**
     * Restituisce l'html per un piccolo form di cancellazione di un record dal database
     * @param type $id
     * @return string
     */
    protected function printDeleteForm($id, $nameField){
        $html = "";
        $html.= '<form action="'.curPageURL().'" method="POST">';
        $html.= '<input type="hidden" name="id" value="'.$id.'" />';        
        $html.= '<input type="submit" name="delete-'.$nameField.'" class="btn btn-danger" value="CANCELLA">';
        $html.= '</form>';
        
        return $html;
    }

    
}
