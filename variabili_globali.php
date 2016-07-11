<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


//definizione dei percorsi da utilizzare
//NB. bisogna cambiare il senso degli slash quando si passa da locale a web server
global $DIR_PDF;
global $URL_PDF;
global $URL_IMG;
global $URL_IMG_PREVENTIVI;
global $URL_IMG_PREVENTIVI_THUMB;
global $DIR_IMG_PREVENTIVI; 
global $DIR_IMG_PREVENTIVI_THUMB;
global $DIR_TEMP_IMG_PREVENTIVI;
global $DIR_TEMP_IMG_PREVENTIVI_THUMB;
global $SENT_EMAIL;

$DIR_PDF = plugin_dir_path(__FILE__).'\\preventivi_pdf\\';
$DIR_IMG_PREVENTIVI = plugin_dir_path(__FILE__).'\\preventivi_immagini\\';
$DIR_IMG_PREVENTIVI_THUMB = $DIR_IMG_PREVENTIVI.'thumbnail\\';
$DIR_TEMP_IMG_PREVENTIVI = plugin_dir_path(__FILE__).'\\files\\';
$DIR_TEMP_IMG_PREVENTIVI_THUMB = $DIR_TEMP_IMG_PREVENTIVI.'thumbnail\\';

$URL_PDF = plugins_url().'/preventivi_serrature/preventivi_pdf/';
$URL_IMG = plugins_url().'/preventivi_serrature/images/';
$URL_IMG_PREVENTIVI = plugins_url().'/preventivi_serrature/preventivi_immagini/';
$URL_IMG_PREVENTIVI_THUMB = $URL_IMG_PREVENTIVI.'thumbnail/';

$SENT_EMAIL = 'info@alexsoluzioniweb.it';



//VARIABILI GLOBALI ANAGRAFICA
global $FORM_PARTITA_IVA,  $LABEL_PARTITA_IVA;
global $FORM_TELEFONO, $LABEL_TELEFONO;
global $FORM_EMAIL, $LABEL_EMAIL;

$FORM_PARTITA_IVA = 'partita-iva';
$LABEL_PARTITA_IVA = 'Partita IVA';
$FORM_TELEFONO = 'telefono';
$LABEL_TELEFONO = 'Telefono';
$FORM_EMAIL = 'email-utente';
$LABEL_EMAIL = 'Email';


//Indirizzo
global $FORM_INDIRIZZO, $LABEL_INDIRIZZO, $FORM_CIVICO, $LABEL_CIVICO, $FORM_CAP, $LABEL_CAP, $FORM_CITTA, $LABEL_CITTA, $FORM_PROV, $LABEL_PROV;

$FORM_INDIRIZZO = 'indirizzo';
$LABEL_INDIRIZZO = 'Piazza/Via';
$FORM_CIVICO = 'civico';
$LABEL_CIVICO = 'N°';
$FORM_CAP = 'cap';
$LABEL_CAP = 'CAP';
$FORM_CITTA = 'citta';
$LABEL_CITTA = 'Città';
$FORM_PROV = 'prov';
$LABEL_PROV = 'Provincia';


//Rivenditore
global $RIV_FORM_SUBMIT, $RIV_LABEL_SUBMIT, $FORM_NOMINATIVO, $LABEL_NOMINATIVO, $FORM_SCONTO, $LABEL_SCONTO, $FORM_CODICE, $LABEL_CODICE, $FORM_CON_VEN, $LABEL_CON_VEN, $FORM_PAG, $LABEL_PAG;

$RIV_FORM_SUBMIT = 'salva-rivenditore';
$RIV_LABEL_SUBMIT = 'Salva Rivenditore';
$FORM_NOMINATIVO = 'nominativo';
$LABEL_NOMINATIVO = 'Nominativo';
$FORM_SCONTO = 'sconto';
$LABEL_SCONTO = 'Sconto';
$FORM_CODICE = 'codice';
$LABEL_CODICE = 'Codice';
$FORM_CON_VEN = 'con-ven';
$LABEL_CON_VEN = 'Condizioni di vendita';
$FORM_PAG = 'pagamento';
$LABEL_PAG = 'Pagamento';


//Trasporto
global $TRAS_FORM_SUBMIT, $TRAS_LABEL_SUBMIT, $FORM_AREA, $LABEL_AREA, $FORM_PREZZO, $LABEL_PREZZO;
global $TRAS_FORM_SELECT, $TRAS_LABEL_SELECT;

$TRAS_FORM_SUBMIT = 'salva-trasporto';
$TRAS_LABEL_SUBMIT = 'Salva Trasporto';
$FORM_AREA = 'area';
$LABEL_AREA = 'Area';
$FORM_PREZZO = 'prezzo';
$LABEL_PREZZO = 'Prezzo (€)';

$TRAS_FORM_SELECT = 'select-trasporti';
$TRAS_LABEL_SELECT = 'Trasporto';



?>