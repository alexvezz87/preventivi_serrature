/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($){
    
    $urlAjax = $('input[name=ajax-url]').val();    
    
    init(); 
      
    //gestione evento sui .box
    function checkSelected() {
       $(document).on('click', '.box', function(){
            //console.log('clicked');
            $(this).siblings().each(function(){
                $(this).removeClass('selected');
            });
            $(this).addClass('selected');
        });
    }
        
    
    //Evento su selezione infisso
    function selectInfisso(){
        $('.porta, .finestra').click(function(){           
            
            var $button = $(this).siblings('.ricerca');
            var $infissi = $(this).siblings('.selezione-infissi');
            var $boxMisure = $(this).siblings('.selezione-misure');

            $infissi.hide();
            $button.hide();
            $boxMisure.hide();
            $boxMisure.siblings('.selezione-apertura').hide();

            //gestione selezioni
            //checkSelected();

            $(this).siblings('.selezione-ante').html('');        
            var $element = $(this).siblings('.selezione-ante');


            var type;
            if($(this).attr('class').indexOf('porta') >=0){
                type = 'P';
            }
            else{
                type = 'F';
            }

            $.ajax({
                type:'POST',
                dataType: 'json',
                url: $urlAjax,
                data: {id_type : type},
                success: function(data){
                    printAnte(data, $element, $button);
                }            
            });

        });
    }   
    
    //Evento su ricerca infisso con ante
    function searchInfisso(){
        $('input[name=ricerca-infissi').click('.selezione-infissi', function(){
            var $infissi = $(this).siblings('.selezione-infissi');
            $infissi.hide();

            //Ottengo il type
            var type = 'N';
            if($(this).siblings('.selected').attr('class').indexOf('porta') >= 0){
                type = 'P';
            }
            else{
                type = 'F';
            }
            //Ottengo il numero di ante
            var ante = $(this).siblings('.selezione-ante').find('select').val();

            $.ajax({
                type:'POST',
                dataType: 'json',
                url: $urlAjax,
                data: {id_type_2: type, ante : ante},
                success: function(data){
                    printInfissi(data, $infissi);

                    //Evento click su un infisso
                    $('.infisso').click(function(){                        
                        //$(this).addClass('selected');
                        var $boxMisure = $(this).parent('.selezione-infissi').siblings('.selezione-misure');
                        $boxMisure.hide();
                        $boxMisure.siblings('.selezione-apertura').hide();
                        $boxMisure.html('');

                        //gestione selezioni
                        //checkSelected();

                        var idTabella = $(this).find('input[name=infisso-id]').val();

                        $.ajax({
                            type:'POST',
                            dataType: 'json',
                            url: $urlAjax,
                            data: {id_tabella : idTabella },
                            success: function(data){
                                printMisure(data, $boxMisure);
                                //listener sulla modifica delle misure ed inserimento del prezzo
                                $('input[name=conferma-misure]').click(function(){

                                    //ottengo il valore del prezzo
                                    var row = $(this).siblings('.table-misure').find('select[name=infisso-altezza]').val();
                                    var col = $(this).siblings('.table-misure').find('select[name=infisso-lunghezza]').val();

                                    //chiamata ajax per il prezzo
                                    $.ajax({
                                        type:'POST',
                                        dataType: 'json',
                                        url: $urlAjax,
                                        data: {id_tabella_2 : idTabella, row : row, col : col },
                                        success: function(data){
                                            var totale = printPrezzo(data);
                                            //inserisco il prezzo nel parziale
                                            $boxMisure.siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val(totale);
                                            //aggiorno il prezzo totale dell'infisso                                            
                                            $boxMisure.siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text(totale);
                                            
                                            //inserisco il prezzo dell'infisso senza maggiorazioni
                                            $boxMisure.siblings('input[name=totale-infisso]').val(totale);
                                            
                                            
                                            
                                            //aggiorno il preventivo totale
                                            aggiornoTotalePreventivo();                                            
                                        }
                                    });

                                    $boxMisure.siblings('.selezione-apertura').show();
                                });

                            }

                        });

                    });
                }
            });        
        });
    }   
    
    //Evento su scelta numero infissi
    function changeNumeroInfissi(){
        $('input[name=numero-infissi]').on('focusout change', function(){
            var nInfissi = $(this).val();
            var totUnitario = $(this).parent('.numero-infissi').siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val();
            var totale = parseFloat(nInfissi*parseFloat(totUnitario)).toFixed(2);
            //Devo cambiare i prezzi in:
            //1. input del totale parziale infisso            
            $(this).parent('.numero-infissi').siblings('.spesa-parziale-infisso').find('span').text(totale);
            //2. il totale del preventivo
            aggiornoTotalePreventivo();
            
        });
    }
    
    
    function printPrezzo(data){       
        //ottengo la somma presente
        var totale = $('.prezzo-preventivo').text();
        totale= parseFloat(data).toFixed(2);        
        return totale;
    }
    
    function updatePrezzo(totale, start, data, type){      
        totale = parseFloat(totale);
        data = parseFloat(data);            

        if(type === '%'){
            //sommo la percentuale
            start = parseFloat(start);
            totale = totale + ((data * start)/100);
        }
        else{
            //sommo cifra fissa
            totale = totale + data;
        }

        totale = parseFloat(totale).toFixed(2);
        
        return totale;
    }
    
    function printMisure(data, $boxMisure){
        
        var minH = parseInt(data.start_rows);
        var maxH = parseInt(data.end_rows);
        var stepH = parseInt(data.step_rows);
        var minL = parseInt(data.start_cols);
        var maxL = parseInt(data.end_cols);
        var stepL = parseInt(data.step_cols);
        
        var html = '<p class="step">4. Seleziona le misure</p>';
        html += '<table class="table-misure"><tr><td><label>Seleziona altezza </label></td>';
        html+= '<td><select name="infisso-altezza">';        
        for(var i=minH; i <= maxH; i+=stepH){
            html+='<option value="'+i+'">'+i+'</option>';
        }        
        html+= '</select></td></tr>';
        html+= '<tr><td><label> Seleziona lunghezza</label></td>';
        html+= '<td><select name="infisso-lunghezza">';
        for(var j=minL; j <= maxL; j+=stepL){
            html+='<option value="'+j+'">'+j+'</option>';
        }
        html+= '</select></td></tr></table>';
        
        html+= '<input type="button" name="conferma-misure" value="conferma misure" /><br><br>';       
        
        $boxMisure.html(html);
        $boxMisure.show();        
        
    }
    
    function printAnte(data, $element, $button){
        $(function(){
            
            var html = '<p class="step">2. Scegli il numero di ante ';
            html += '<select name="seleziona-ante">';
            for(var i=0; i< data.length; i++){
                if(data[i].ante === '0'){
                    html+= '<option value="'+data[i].ante+'">inferriata fissa</option>';
                }
                else{
                    html+= '<option value="'+data[i].ante+'">'+data[i].ante+'</option>';
                }
            }
            html += '</select></p>';
                        
            $element.html(html);
            $button.show();
           
        });
    }
    
    function printInfissi(data, $infissi){
        $infissi.html('');
        $infissi.show();        
        var html = '<p class="step">3. Selezione l\'articolo desiderato</p>';
        for(var i=0; i < data.length; i++){
            html+='<div class="box infisso" data-type="infisso" data-name="'+data[i].ID+'" >';
            html+='<input type="hidden" name="infisso-id" value="'+data[i].ID+'" />';
            html+='<p style="font-size:14px">'+data[i].ID+'<br>'+data[i].nome+'</p>';           
            html+='</div>';
        }
        
        html+='<div style="clear:both; width:100%"></div>';
        
        $infissi.html(html);
    }
    
    //gestione automatizzata dei due tipi di colori
    function gestoreSelettoreColori(type){
        $('.'+type+'-box.selettore-box').click(function(){
            $(this).siblings('.select-'+type).slideToggle();
        });
        
        $('.select-'+type+' div').click(function(){
            var $box = $(this).parent('.select-'+type).siblings('.'+type+'-box');
            var $select = $(this).parent('.select-'+type);            
            $box.find('.selettore-show').html('');
            $(this).clone().appendTo($box.find('.selettore-show'));
            $select.hide();    
            
            //codice per de-selezionare il relativo colore
            if(type==='ral'){
                //deseleziono i micacei
                $box.siblings('.micacei-box').find('.selettore-show').html('<div class="none" data-type="colore" data-name="none">selezione Micacei</div>');
            }
            else{
                //deseleziono i ral
                $box.siblings('.ral-box').find('.selettore-show').html('<div class="none" data-type="colore" data-name="none">selezione RAL</div>');
            }
            
            //mostro il punto successivo se ho effettuato una scelta diversa da none           
            if($(this).data('name')!== 'none'){                
                $select.parent('.selezione-colore').siblings('.selezione-cerniera').show();
                
            }
            else{
                $select.parent('.selezione-colore').siblings('.selezione-cerniera').hide();
                $select.parent('.selezione-colore').siblings('.selezione-cerniera').find('.box').removeClass('selected');
            }
            
        });
        
        
    }    
        
        
    //gestione del selettore dei ral
    function selettoreRAL(){        
       gestoreSelettoreColori('ral');    
    }
    
    //gestione del selettore dei micacei
    function selettoreMICACEI(){
        gestoreSelettoreColori('micacei');
    }
        
    //Gestione selettore delle maggiorazioni
    function selettoreMaggiorazioni(){
        $('.box-2').click(function(){
            //console.log('clicked!') ;
            $(this).toggleClass('selected');
            //calcolo la spesa complessiva in toggle
            var qt = $(this).find('input[name=maggiorazione-qt]').val();
            var um = $(this).find('input[name=maggiorazione-um]').val().trim();

            //prendo il valore di partenza dell'infisso da utilizzare per i prezzi in percentuale
            var st = $(this).parent('.maggiorazioni').siblings('input[name=totale-infisso]').val();
            //prendo il totale parziale dell'infisso
            var totale = $(this).parent('.maggiorazioni').siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val();
            
            if($(this).hasClass('selected')){
                //aggiungo la spesa
                totale = updatePrezzo(totale, st, qt, um);
            }
            else{
                //sottraggo la spesa
                qt = '-'+qt;
                totale = updatePrezzo(totale, st, qt, um);
            }
            
            //aggiorno il prezzo totale infisso
            $(this).parent('.maggiorazioni').siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val(totale);
            //ottengo il numero degli infissi
            var nInfissi = $(this).parent('.maggiorazioni').siblings('.numero-infissi').find('input').val();
            var totale2 = parseFloat(nInfissi * totale).toFixed(2);            
            $(this).parent('.maggiorazioni').siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text(totale2);
            
            //aggiorno il totale del preventivo
            aggiornoTotalePreventivo();
            return;
            
        });
    }
    
    function aggiornoTotalePreventivo(){        
        //aggiorno il prezzo totale del preventivo
        var totalePreventivo = 0;
        $('input[name=spesa-parziale-infisso]').each(function(){
            var parziale = $(this).val();
            //vado a prendere il numero di infissi
            var nInfissi = $(this).parent('.spesa-parziale-infisso').siblings('.numero-infissi').find('input').val();
            
            totalePreventivo = totalePreventivo + (parseFloat(parziale)*nInfissi);
        });
        
        totalePreventivo = parseFloat(totalePreventivo).toFixed(2);        
        $('.totale-preventivo .prezzo-preventivo').html(totalePreventivo);
    }
    
    //Inizalizzo tutte le funzioni in una funzione init
    function init(){
        checkSelected();
        selettoreMaggiorazioni();
        selectInfisso();
        searchInfisso();
        selettoreRAL();
        selettoreMICACEI();        
        changeNumeroInfissi();
        
        $('.selezione-apertura .box').click(function(){
            $('.particolari-costruttivi').show();
        });
        
        $('.container-barra .box').click(function(){
            $('.container-serratura').show();
        });
        
        $('.container-serratura .box').click(function(){
            $('.container-nodo').show();
        });
        
        $('.container-nodo .box').click(function(){
           $('.selezione-colore').show(); 
        });
        
        $('.selezione-colore .box').click(function(){
            $('.selezione-cerniera').show();
        });
        
        $('.selezione-cerniera .box').click(function(){
            $('.maggiorazioni').show();
            $('.numero-infissi').show();
            $('input[name=aggiungi-infisso]').removeAttr('disabled');
        });
        
        //LISTENER SU SELEZIONA NUMERO ANTE        
        listenerNumeroAnte();
        
        aggiungiInfisso();
        eliminaInfisso();     
        inviaPreventivo();
        
        $('input[name=close-box]').click(function(){
            $(this).parent('.message-box').hide();
        });
        
    }    
    
    /**
     * Listner che se in etinere della composizione di un infisso vengono modificati
     * i dati riferiti al numero di ante, vengono resettati i parametri di successivo sviluppo
     * e azzerata la spesa dell'infisso
     * @returns {undefined}
     */
    function listenerNumeroAnte(){
        //cambiando il numero di ante, va resettata la visualizzazione successiva
        $(document).on('change', 'select[name=seleziona-ante]', function(){     
            $(this).parent('.step').parent('.selezione-ante').siblings('.selezione-infissi').html('');
            $(this).parent('.step').parent('.selezione-ante').siblings('.selezione-misure').html('');
            
            //deseleziono le maggiorazioni
            $(this).parent('.step').parent('.selezione-ante').siblings('.maggiorazioni').find('.box-2').each(function(){
                $(this).removeClass('selected');
            });
            //cambio il totale parziale dell'infisso
            $(this).parent('.step').parent('.selezione-ante').siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val(0);
            $(this).parent('.step').parent('.selezione-ante').siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text('0');
            $(this).parent('.step').parent('.selezione-ante').siblings('input[name=totale-infisso]').val(0);
            
            aggiornoTotalePreventivo();
        });
    }
    
    function reset(){
        //resetto le selezioni
        $('.nuovo-infisso .selected').removeClass('selected');
        
        //resetto i contenuti
        $('.nuovo-infisso .selezione-ante').html('');
        $('.nuovo-infisso .ricerca').hide();
        $('.nuovo-infisso .selezione-infissi').html('');
        $('.nuovo-infisso .selezione-misure').html('');
        $('.nuovo-infisso .selezione-apertura').hide();
        $('.nuovo-infisso .particolari-costruttivi').hide();
        $('.nuovo-infisso .container-serratura').hide();
        $('.nuovo-infisso .container-nodo').hide();
        $('.nuovo-infisso .selezione-colore').hide();
        $('.nuovo-infisso .selezione-colore .ral-box .selettore-show').html('<div class="none" data-type="colore" data-name="none">seleziona RAL</div>');
        $('.nuovo-infisso .selezione-colore .micacei-box .selettore-show').html('<div class="none" data-type="colore" data-name="none">selezione Micacei</div>');
        $('.nuovo-infisso .selezione-cerniera').hide();
        $('.nuovo-infisso input[name=totale-infisso]').val('');
        $('.nuovo-infisso .maggiorazioni').hide();
        $('.nuovo-infisso .numero-infissi').hide();
        $('.nuovo-infisso input[name=numero-infissi]').val(1);
        $('input[name=aggiungi-infisso]').prop('disabled', true);
        $('.nuovo-infisso .spesa-parziale-infisso span').text('0');
        $('.nuovo-infisso input[spesa-parziale-infisso]').val('');
        
        var nInfisso = $('.selezione-container').size();
        $('.nuovo-infisso').find('h3 span').text(nInfisso);
        
        //rimuovo il nuovo infisso
        $('.nuovo-infisso').removeClass('nuovo-infisso');
    }
    
    //Gestione aggiungi infisso
    function aggiungiInfisso(){
        $('input[name=aggiungi-infisso]').click(function(){
           $('.selezione-container').first().clone(true, true).addClass('nuovo-infisso').appendTo('#container-infissi');
           //aggiungo un pulsante di elimina infisso
           var html = '<input type="button" name="cancella-infisso" value="cancella infisso" />';
           $(html).appendTo('.nuovo-infisso');

           //resetto 
           reset();  
        });
    }
    
    //Gestione elimina infisso
    function eliminaInfisso(){
        $(document).on('click', 'input[name=cancella-infisso]', function(){       
           //all'eliminazione dell'infisso devo:          
           //1. eliminare dal dom il div dell'infisso
           //2. aggiornare il totale
           
           $(this).parent('.selezione-container').remove();
           aggiornoTotalePreventivo();    
           $('input[name=aggiungi-infisso]').removeAttr('disabled');
        });
    }
    
    
    function inviaPreventivo(){
        $('input[name=invia-preventivo]').click(function(){
            /*
            * Quando viene inviato un preventivo devo effettuare un controllo dei dati compilati:
            * 1. Data odierna
            * 2. Nome rivenditore/agente
            * 3. Nome cliente
            * 4. Via cliente
            * 5. Telefono cliente
            * ciclo su tutti div selezione-container
            * controllo se sono stati compilati i diversi campi:
            * 6. Selezione su tipo di infisso (finestra o porta)
            * 7. Selezione sul numero di ante
            * 8. Selezione sullo specifico infisso
            * 9. Selezione della misura di altezza
            * 10. Selezione della misura di lunghezza
            * 11. Selezione sul tipo di apertura 
            * 12. Selezione sul tipo di barra
            * 13. Selezione sul tipo di serratura
            * 14. Selezione sul tipo di nodo
            * 15. Selezione sul colore
            * 16. Selezione sul tipo di cerniera
            * 17. Selezione sulle maggiorazioni
            * 18. Selezione sul numero di infissi
            * 19. Spesa parziale infisso
            */
           
            //1. Data odierna
            var preventivo = new Object();
            preventivo.data = $('input[name=data-odierna]').val();
            //2. Nome rivenditore/agente
            preventivo.rivenditore = $('input[name=rivenditore-agente]').val();
            //3. Nome cliente
            preventivo.clienteNome = $('input[name=nome-cliente]').val();
            //4. Via cliente
            preventivo.clienteVia = $('input[name=via-cliente]').val();
            //5. Telefono cliente
            preventivo.clienteTel = $('input[name=telefono-cliente]').val();
            
            
            //creo l'array che conterrà i dati            
            var infissi = new Array($('.selezione-container').size());
            var count = 0;
            
            //console.log('infissi:' + $('.selezione-container').size());
            //Ciclo sui container
            $('.selezione-container').each(function(){
                infissi[count];
                var infisso = new Object();;                
               
               if($(this).find('.box-2.selected').size() > 0){
                   infisso.maggiorazione = new Array();
               }
               
                //ciclo sui selezionati
                $(this).find('.selected').each(function(){
                    //Soddisfo i punti 6, 8, 11, 12, 13, 14, 16, 17
                    
                    if($(this).data('type') === 'barra'){
                        //Punto 12
                        infisso[$(this).data('type')] = $(this).data('name')+'-'+$(this).find('select').val();
                    } 
                    else if($(this).data('type') === 'cerniera' && $(this).data('name') === 'collo-allungato'){
                        //Punto 16
                        infisso[$(this).data('type')] = $(this).data('name')+'-'+$(this).find('input').val()+'cm';
                    }
                    else if($(this).hasClass('box-2')){
                        //Punto 17
                        infisso['maggiorazione'].push($(this).data('name'));
                    }
                    else{
                        infisso[$(this).data('type')] = $(this).data('name');
                    }                                        
                    
                    
                });
                //fine ciclo sui selezioanti
                
                //7. Selezione sul numero di ante                
                if(typeof($(this).find('select[name=seleziona-ante]').val())!== 'undefined'){
                    infisso['numero-ante'] = $(this).find('select[name=seleziona-ante]').val();
                }
                
                //9. Selezione della misura di altezza
                if( typeof($(this).find('select[name=infisso-altezza]').val()) !== 'undefined'){
                    infisso['altezza'] = $(this).find('select[name=infisso-altezza]').val();
                }
                //10. Selezione della misura di lunghezza
                if(typeof($(this).find('select[name=infisso-lunghezza]').val()) !== 'undefined'){
                    infisso['lunghezza'] = $(this).find('select[name=infisso-lunghezza]').val();
                }
                //15. Selezione sul colore
                // 15.ral --> controllo i RAL
                if($(this).find('.ral-box .selettore-show div').data('name') !== 'none'){
                    infisso['colore'] = $(this).find('.ral-box .selettore-show div').data('name');
                }
                // 15.micacei --> controllo i micacei
                if($(this).find('.micacei-box .selettore-show div').data('name') !== 'none'){
                    infisso['colore'] = $(this).find('.micacei-box .selettore-show div').data('name');
                } 
                
                //18. Selezione sul numero di infissi   
                if(typeof($(this).find('input[name=numero-infissi]').val()) !== 'undefined'){
                    infisso['num-infissi'] = $(this).find('input[name=numero-infissi]').val();
                }
                
                //19. Spesa parziale infisso
                infisso['spesa-parziale'] = $(this).find('input[name=spesa-parziale-infisso]').val();
                
                //console.log(Object.size(infisso));
                
                infissi.push(infisso);
                count++;
            });
            //fine ciclo sui container
            
            //scremo gli infissi dagli undefined
            infissi = $.grep(infissi, function(value){
               return typeof(value) !== 'undefined'; 
            });
            
            //console.log(infissi);
            
            //controllo i campi
            var check = checkFields(infissi, preventivo);
            if(check !== true){
                //I campi non sono stati compilati nel modo corretto
                
                //console.log('campi non soddisfatti');
                //console.log(check);
                var html = "";
               
                $.each(check, function(index, value){
                    if(index >= 0 && index < 6){
                        if(index >= 0 && index < 5){
                            if(typeof(value) !== 'undefined'){
                                html+= '<div class="punto">- '+value+'</div>';
                            }
                        }
                        else if(index === 5){
                            html += "Nell'infisso n."+value+" serve: <br>";
                        }
                    }
                    else{
                        html+= '<div class="punto">- '+value+'</div>';
                    }
                    
                });
                
                $('.error-box p').html(html);
                $('.error-box').show();
            }
            else{
                              
                preventivo.infissi = infissi;
                preventivo.totale = $('.totale-preventivo .prezzo-preventivo').text();
                //I campi sono stati compilati nel modo corretto ora devo:
                //1. salvare i dati nel database
                //2. visualizzare i dati nello storico di admin e utente
                //3. comporre il pdf 
                //4. creare la mail e allegarci il pdf
                //console.log(preventivo);  
              
                //1. Chiamata ajax per salavare i dati
                $.ajax({
                    type:'POST',
                    dataType: 'json',
                    url: $urlAjax,
                    data: {preventivo: preventivo},
                    success: function(data){
                        var html = "";
                        if(data.salvato === true){
                            //il preventivo è stato salvato correttamente nel database
                            html+= 'Il preventivo è stato correttamente registrato!'; 
                            if(data.pdf !== false){
                                html+='<br><a target="_blank" href="'+data.pdf+'">Rivedi la tua richiesta di preventivo</a>';
                                
                                if(data.mail === true){
                                    html+='<br>Email inviata all\'amministrazione!';
                                }
                                else{
                                    html+='<br>Errore nell\'invio dell\'email!';
                                }
                            }
                            else{
                                html+='<br>Errore nel salvataggio del pdf!';
                            }
                            
                        }
                        else{
                            //il preventivo non è stato salvato correttamente nel database
                            html+='Ci sono stati dei problemi nella registrazione del preventivo!'
                        }
                        $('.ok-box p').html(html);
                        $('.ok-box').show();
                    }
                });
                
                
                
            }
            
            
        });
    }
    
    //funzione che esegue un controllo sui campi
    function checkFields(infissi, preventivo){
        var mancanti = new Array();
        //Controllo sui preventivi
        
        if(preventivo.data === ''){
            mancanti[0] = 'Indicare la data del preventivo';
        }
        if(preventivo.rivenditore === ''){
            mancanti[1] = 'Non è stato effettuato l\'accesso come utente rivenditore/agente';
        }
        if(preventivo.clienteNome === ''){
            mancanti[2] = 'Indicare il nome del cliente';
        }
        if(preventivo.clienteVia === ''){
            mancanti[3] = 'Indicare l\'indirizzo del cliente';
        }
        if(preventivo.clienteTel === ''){
            mancanti[4]= 'Indicare il recapito telefonico del cliente';
        }
        
        //Controllo sui campi            
        for(var i=0; i < infissi.length; i++){
            if(typeof(infissi[i])!== 'undefined'){
                //console.log(infissi[i]);
                //controllo la dimensione dell'array
                //se è minore di 12, non sono stati compilati tutti i campi
                if(Object.size(infissi[i]) < 12){
                    
                    //il campo numero zero indica a quale infisso mancano i valori
                    mancanti[5] = i+1;                    
                    
                    //controllo i valori mancanti
                    if(!('altezza' in infissi[i])){
                        mancanti.push('Indicare l\'altezza dell\'infisso');
                    } 
                    if(!('apertura' in infissi[i])){
                        mancanti.push('Specifcare il tipo di apertura');
                    } 
                    if(!('barra' in infissi[i])){
                        mancanti.push('Indicare il tipo di barra');
                    } 
                    if(!('cerniera' in infissi[i])){
                        mancanti.push('Indicare il tipo di cerniera');
                    } 
                    if(!('colore' in infissi[i])){
                        mancanti.push('Selezionare il colore');
                    } 
                    if(!('infisso' in infissi[i])){
                        mancanti.push('Indicare l\'inifsso specifico');
                    } 
                    if(!('lunghezza' in infissi[i])){
                        mancanti.push('Indicare la lunghezza');
                    } 
                    if(!('num-infissi' in infissi[i])){
                        mancanti.push('Indicare il numero di infissi');
                    } 
                    if(!('nodo' in infissi[i])){
                        mancanti.push('Indicare il tipo di nodo');
                    }                     
                    if(!('numero-ante' in infissi[i])){
                        mancanti.push('Indicare il numero di ante dell\'infisso');
                    } 
                    if(!('serratura' in infissi[i])){
                        mancanti.push('Indicare il tipo di serratura');
                    } 
                    if(!('tipo-infisso' in infissi[i])){
                        mancanti.push('Indicare il tipo di infisso');
                    } 
                    
                    //return mancanti;
                }               
            }
        }
        
        if(mancanti.length > 0){
            return mancanti;
        }
        
        return true;
    }
    
    
    //funzione di conteggio dell'array
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    
    
    
});

