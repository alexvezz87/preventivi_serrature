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
    protected function printTextFormField($nameField, $label, $required=false){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $value = "";
        if(isset($_POST[$nameField])){
            $value = $_POST[$nameField];
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
     * Funzione che stampa secondo i canoni bootstrap una textarea
     * @param type $nameField
     * @param type $label
     */
    protected function printTextAreaFormField($nameField, $label, $required=false){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $value = "";
        if(isset($_POST[$nameField])){
            $value = $_POST[$nameField];
        }
    ?>
        <div class="form-group">
            <label class="control-label" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <textarea class="form-control" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="" <?php echo $optRequired ?>><?php echo $value ?></textarea>           
        </div>
    <?php      
        
    }
    
    protected function printNumberFormField($nameField, $label, $required=false){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $value = "";
        if(isset($_POST[$nameField])){
            $value = $_POST[$nameField];
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
    protected function printEmailFormField($nameField, $label, $required=false){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $value = "";
        if(isset($_POST[$nameField])){
            $value = $_POST[$nameField];
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
     * Funzione che stampa secondo canoni bootstrap una select box
     * @param type $nameField
     * @param type $label
     * @param type $array
     */
    protected function printSelectFormField($nameField, $label, $array, $required=false){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $value = "";
        if(isset($_POST[$nameField])){
            $value = $_POST[$nameField];
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
        <button name="<?php echo $nameField ?>" type="submit" class="btn btn-default"><?php echo $label ?></button>
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
    protected function printTableHover($header, $bodyTable, $actions){
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

    
}
