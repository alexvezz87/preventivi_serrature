<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/


/********************* CREATE TABLES *******************/

function install_preventivi(){
    //La funzione installa due tabelle:
    //1. Tabella che identifica le tabelle di prezzi
    //2. Tabella che identifica le celle relative alle tabelle
    
    try{
        global $wpdb;
        $charset_collate = "";
        //prefisso --> pps = plugin preventivi serrature
        $wpdb->prefix = 'pps_';
        if (!empty ($wpdb->charset)){
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty ($wpdb->collate)){
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }
        
        //installo le tabelle dei prezzi
        install_tabella_articoli($wpdb, $charset_collate);
        install_tabella_prezzi($wpdb, $charset_collate);  
        
        //installo la tabella delle maggiorazioni
        install_tabella_maggiorazioni($wpdb, $charset_collate);
        
        //installo la tabella degli infissi
        install_tabella_infissi($wpdb, $charset_collate);
        install_tabella_infissi_maggiorazioni($wpdb, $charset_collate);
        
        //installo la tabelal dei preventivi
        install_tabella_preventivo($wpdb, $charset_collate);
        
        //installo la tabella delle foto
        install_tabella_foto($wpdb, $charset_collate);
        
       return true;
        
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
}


//installo tabella delle tabelle 
function install_tabella_articoli($wpdb, $charset_collate) {
    $table = $wpdb->prefix.'tabelle';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                start_rows INT NOT NULL,
                end_rows INT NOT NULL,
                step_rows INT NOT NULL,
                start_cols INT NOT NULL,
                end_cols INT NOT NULL,
                step_cols INT NOT NULL,
                ante INT NOT NULL,
                prezzo_iniziale DECIMAL(8,2) NOT NULL,
                incremento DECIMAL(8,2) NOT NULL
             );{$charset_collate}";   
    try{
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($query);   
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }         
}

//installo tabella dei prezzi
function install_tabella_prezzi($wpdb, $charset_collate){
    $table = $wpdb->prefix.'prezzi';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,                
                id_tabella INT NOT NULL,
                val_row INT NOT NULL,
                val_col INT NOT NULL,
                prezzo DECIMAL(8,2)                
             );{$charset_collate}";   
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    } 
}

//installo tabella delle maggiorazioni
function install_tabella_maggiorazioni($wpdb, $charset_collate){
    $table = $wpdb->prefix.'maggiorazioni';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,
                nome TEXT NOT NULL,
                quantita DECIMAL(8,2) NOT NULL,
                unita_misura VARCHAR(1) NOT NULL
              );{$charset_collate}";
    
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }      
}

//installo tabella infisso
function install_tabella_infissi($wpdb, $charset_collate){
    $table = $wpdb->prefix.'infissi';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,
                id_preventivo INT NOT NULL,
                tipo VARCHAR(50) NOT NULL,
                n_ante INT NOT NULL,
                id_infisso INT NOT NULL,
                altezza INT NOT NULL,
                lunghezza INT NOT NULL,
                apertura VARCHAR(100) NOT NULL,
                barra VARCHAR(100) NOT NULL,
                serratura VARCHAR(100) NOT NULL,
                nodo VARCHAR(100) NOT NULL,
                colore VARCHAR(100) NOT NULL,
                cerniera VARCHAR(100) NOT NULL,
                n_infisso INT NOT NULL,
                spesa_infisso DECIMAL(15,2),
                anta_principale VARCHAR(2) NOT NULL,
                posizione_serratura VARCHAR(1) NOT NULL,
                verniciatura VARCHAR(20)
              );{$charset_collate}";
    
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }                
}

//installo tabella infisso_maggiorazione
function install_tabella_infissi_maggiorazioni($wpdb, $charset_collate){
    $table = $wpdb->prefix.'infissi_maggiorazioni';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,
                id_infisso INT NOT NULL,
                id_maggiorazione INT NOT NULL
              );{$charset_collate}";
    
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }      
}

//Tabella del preventivo
function install_tabella_preventivo($wpdb, $charset_collate){
    $table = $wpdb->prefix.'preventivi';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,
                data TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
                data_visionato TIMESTAMP,
                id_utente INT NOT NULL,
                nome_rivenditore TEXT NOT NULL,
                cliente_tipo VARCHAR(20) NOT NULL,
                cliente_nome VARCHAR(100) NOT NULL,
                cliente_via TEXT NOT NULL,
                cliente_tel VARCHAR(100) NOT NULL,
                cliente_email TEXT, 
                cliente_cf TEXT,
                spesa_totale DECIMAL(15,2),
                visionato INT NOT NULL DEFAULT 0,
                pdf TEXT,
                note TEXT,
                tipo INT NOT NULL
              );{$charset_collate}";    
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }      
}

//installo tabella foto
function install_tabella_foto($wpdb, $charset_collate){
    $table = $wpdb->prefix.'foto';
    $query = "CREATE TABLE IF NOT EXISTS $table (
                ID INT NOT NULL auto_increment PRIMARY KEY,
                id_preventivo INT NOT NULL,
                nome_foto TEXT NOT NULL,
                url_foto TEXT NOT NULL, 
                url_thumb_foto TEXT NOT NULL
              );{$charset_collate}";
    
    try{
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);   
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }      
}


/********************* DROP TABLES *******************/

function dropDB(){
    global $wpdb;
    $wpdb->prefix = 'pps_';
    try{
        
        //cancello le tabelle dei prezzi
        dropTabellaArticoli($wpdb);
        dropTabellaPrezzi($wpdb);    
        dropTabellaMaggiorazioni($wpdb);
        dropTabellaInfissi($wpdb);
        dropTabellaInfissiMaggiorazioni($wpdb);
        dropTabellaPreventivi($wpdb);       
        dropTabellaFoto($wpdb);
    }
    catch(Exception $ex){
        _e($ex);        
        return false;
    }
   
}

function dropTabellaArticoli($wpdb){
    try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."tabelle;";
            $wpdb->query($query);
            return true;
        }
    catch(Exception $e){
        _e($e);
        return false;
    }
}

function dropTabellaPrezzi($wpdb){
    try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."prezzi;";
            $wpdb->query($query);
            return true;
        }
    catch(Exception $e){
        _e($e);
        return false;
    }
}

function dropTabellaMaggiorazioni($wpdb){
   try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."maggiorazioni;";
            $wpdb->query($query);
            return true;
        }
    catch(Exception $e){
        _e($e);
        return false;
    }
}

function dropTabellaInfissi($wpdb){
    try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."infissi;";
            $wpdb->query($query);
            return true;
        }
    catch(Exception $e){
        _e($e);
        return false;
    }
}

function dropTabellaInfissiMaggiorazioni($wpdb){
    try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."infissi_maggiorazioni;";
            $wpdb->query($query);
            return true;
        }
    catch(Exception $e){
        _e($e);
        return false;
    }
}

function dropTabellaPreventivi($wpdb){
    try{
            $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."preventivi;";
            $wpdb->query($query);
            return true;
        }
    catch(Exception $e){
        _e($e);
        return false;
    }
}

function dropTabellaFoto($wpdb){
    try{
        $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."foto;";
        $wpdb->query($query);
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
}
