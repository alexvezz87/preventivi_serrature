<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//inclusioni delle classi generate
require_once 'model/Prezzo.php';
require_once 'model/Tabella.php';
require_once 'model/Maggiorazione.php';
require_once 'model/Preventivo.php';
require_once 'model/Infisso.php';

require_once 'DAO/TabellaPrezziDAO.php';
require_once 'DAO/MaggiorazioneDAO.php';
require_once 'DAO/PreventivoDAO.php';
require_once 'DAO/InfissoDAO.php';
require_once 'DAO/InfissoMaggiorazioneDAO.php';

require_once 'controller/TabellaPrezziController.php';
require_once 'controller/MaggiorazioneController.php';
require_once 'controller/PreventivoController.php';
require_once 'controller/PdfController.php';

require_once 'view/GestionePrezziView.php';
require_once 'view/CalcolaPreventivoView.php';
require_once 'view/GestioneMaggiorazioneView.php';
require_once 'view/GestionePreventivoView.php';


?>