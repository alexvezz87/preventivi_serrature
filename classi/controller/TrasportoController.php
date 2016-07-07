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
    public function getTrasporti(){
        return $this->tDAO->getTrasporti();
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
