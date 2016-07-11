<?php


/**
 * Description of TrasportoController
 *
 * @author Alex
 */
class TrasportoController {
    private $tDAO;
    
    function __construct() {
        $this->tDAO = new TrasportoDAO();
    }
    
    /**
     * La funzione salva un oggetto Trasporto
     * @param Trasporto $t
     * @return type
     */
    public function saveTrasporto(Trasporto $t){
        return $this->tDAO->saveTrasporto($t);
    }
    
    /**
     * La funzione restituisce un trasporto passando il suo ID
     * @param type $idTrasporto
     * @return type
     */
    public function getTrasporto($idTrasporto){
        return $this->tDAO->getTrasporto($idTrasporto);
    }
    
    /**
     * La funzione restituisce tutti i trasporti presenti
     * @return type
     */
    public function getTrasporti($mode){
        //mode == 1 --> return object
        //mode == 0 --> return array
        
        $temp = $this->tDAO->getTrasporti();
        if($mode == 1){
            return $temp;
        }
        else if($mode == 0){
            //devo convertire l'array di oggetti in array normale
            $results = array();
            foreach($temp as $item){
                $t = new Trasporto();
                $t = $item;
                $result[$t->getID()] = $t->getArea();
                
                array_push($results, $result);
            }            
            return $result;            
        }
    }
    
    /**
     * La funzione aggiorna un trasporto
     * @param Trasporto $t
     * @return type
     */
    public function updateTrasporto(Trasporto $t){
        return $this->tDAO->updateTrasporto($t);
    }
    
    /**
     * La funzione elimina un trasporto passato per ID
     * @param type $idTrasporto
     * @return type
     */
    public function deleteTrasporto($idTrasporto){
        return $this->tDAO->deleteTrasporto($idTrasporto);
    }
    
    
    
    
    

}
