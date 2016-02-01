<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

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
        
        //installo le altre tabelle
        
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
                ante INT NOT NULL
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

function dropDB(){
    global $wpdb;
    $wpdb->prefix = 'pps_';
    try{
        
        //cancello le tabelle dei prezzi
        dropTabellaArticoli($wpdb);
        dropTabellaPrezzi($wpdb);    
       
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
