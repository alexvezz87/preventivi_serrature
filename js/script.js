/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* global z */

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
        
        //ascoltatore dell'anta principale
        $(document).on('click', '.seleziona-anta', function(){            
            $(this).siblings().each(function(){
                $(this).removeClass('selected');
                $(this).removeClass('SX');
                $(this).removeClass('DX');
            });
            
            $(this).addClass('selected');   
            if($(this).siblings('input[name=posizione-serratura]').val() === 'S'){
                $(this).addClass('SX');
            }
            else if($(this).siblings('input[name=posizione-serratura]').val() === 'D'){
                $(this).addClass('DX');
            }
            
            //aggiorno l'input
            $(this).siblings('input[name=anta-principale]').val($(this).data('anta'));            
            
//            //rendo visibile lo step successivo
//            var order = parseInt($(this).parent('.container-seleziona-anta').parent('.container-order').data('order'));
//            $(this).parent('.container-seleziona-anta').parent('.container-order').siblings('.container-order').each(function(){
//                
//            });
        });
       
        //ascoltatore posizione serratura
        $(document).on('click', '.tipo.radio input', function(){    
            //aggiorno l'input
            var valore = $(this).val();
            $(this).parent('.tipo').siblings('input[name=posizione-serratura]').val(valore);
            
            $(this).parent('.tipo').siblings('.selected').removeClass('SX');
            $(this).parent('.tipo').siblings('.selected').removeClass('DX');
            if(valore === 'S'){
                $(this).parent('.tipo').siblings('.selected').addClass('SX');
            }
            else if(valore === 'D'){
                $(this).parent('.tipo').siblings('.selected').addClass('DX');
            }
            
        });         
         
    }
    
    //gestione evento ordine
    function checkOrder(){
        $(document).on('click', '.order', function(){
            //gli elementi compaiono in ordine di visualizzazione
            //è importante mantenere questo ordine e non visualizzare altre cose
            
            
            //se si stratta di un container-box visualizzo quello successivo
            var $container = $(this).parent('.container-order');
            var order = parseInt($container.data('order'));
            //alert(order);
            var next = order + 1;
            
            //resetto tutto quello che ha valore superiore a order
            $container.siblings('.container-order').each(function(){
                if(parseInt($(this).data('order')) > order ){
                    $(this).addClass('hidden');
                }
            });            
            
            $container.siblings('.hidden[data-order="'+next+'"]').removeClass('hidden');
            //alert($container.siblings(".hidden[data-order='" + next + "']").size();
        });
            
    }
    
    //Gestione delle finestre di help
    function checkHelp(){
        $(document).on('click', '.help', function(){    
            //aggiorno l'input
            $(this).siblings('.help-window').removeClass('hidden');
        });
        
        $(document).on('click', '.help-window .close', function(){    
            //aggiorno l'input
            $(this).parent('.help-window').addClass('hidden');
        });
    }
        
    
    //Evento su selezione infisso
    function selectInfisso(){
        //$('.porta, .finestra').click(function(){
        $(document).on('click', '.porta, .finestra', function(){    
            
            var $button = $(this).parent('.tipo-infisso').siblings('.ricerca');
            var $infissi = $(this).parent('.tipo-infisso').siblings('.selezione-infissi');
            var $boxMisure = $(this).parent('.tipo-infisso').siblings('.selezione-misure');

            //$infissi.hide();
            //$button.hide();
            //$boxMisure.hide();
            //$boxMisure.siblings('.selezione-apertura').hide();

            //azzero il parziale infisso e aggiorno il totale preventivo
            var $container = $(this).parent('.tipo-infisso');
            $container.siblings('input[name=totale-infisso]').val("");
            $container.siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val("");
            $container.siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text('0');
            aggiornoTotalePreventivo();


            //gestione selezioni
            //checkSelected();

            $(this).parent('.tipo-infisso').siblings('.selezione-ante').html('');        
            var $element = $(this).parent('.tipo-infisso').siblings('.selezione-ante');


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
                    printAnte(data, $element, $button, type);
                }            
            });

        });
    }   
    
    //Evento su ricerca infisso con ante
    function searchInfisso(){
        //$('input[name=ricerca-infissi').click(/*'.selezione-infissi',*/ function(){
        $(document).on('click', 'input[name=ricerca-infissi]', function(){ 
            
            var $infissi = $(this).siblings('.selezione-infissi');
            
            //nascondo tutti gli order superiori a questo
            var order = 2;
            $(this).siblings('.container-order').each(function(){
                if(parseInt($(this).data('order')) > order){
                    $(this).addClass('hidden');
                }
            });
            //azzero il parziale infisso e aggiorno il totale preventivo
            $(this).siblings('input[name=totale-infisso]').val("");
            $(this).siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val("");
            $(this).siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text('0');
            aggiornoTotalePreventivo();
            
            //$infissi.addClass('hidden');

            //Ottengo il type
            var type = 'N';
            if($(this).siblings('.tipo-infisso').find('.selected').attr('class').indexOf('porta') >= 0){
                type = 'P';
            }
            else{
                type = 'F';
            }
            //Ottengo il numero di ante
            var ante = $(this).siblings('.selezione-ante').find('select').val();
            
            //sapendo il numero di ante posso visualizzare o meno le cerniere e serrature
            var $cerniera = $(this).siblings('.selezione-cerniera');
            var $serratura = $(this).siblings('.container-serratura');
            
            checkInferriataFissa($cerniera, ante); 
            checkInferriataFissa($serratura, ante); 
            
            
            //sapendo il numero di ante posso visualizzare o meno le serrature
            
            var $antaPrincipale = $(this).siblings('.seleziona-anta-principale');
            
            var numInfisso = $(this).parent('.selezione-container').data('infisso');
            
            //Una volta ottenuti tipo e numero ante, vado a comporre l'html per la sezione 'seleziona-anta-principale'
            
            printAntaPrincipale(type, ante, $antaPrincipale, numInfisso);    

            $.ajax({
                type:'POST',
                dataType: 'json',
                url: $urlAjax,
                data: {id_type_2: type, ante : ante},
                success: function(data){
                    printInfissi(data, $infissi, type, ante);

                    //Evento click su un infisso
                    //$('.infisso').click(function(){
                    $(document).on('click', '.infisso', function(){     
                        //$(this).addClass('selected');
                        var $boxMisure = $(this).parent('.selezione-infissi').siblings('.selezione-misure');
                        
                        //$boxMisure.addClass('hidden');
                        //$boxMisure.siblings('.selezione-apertura').addClass('hidden');
                        $boxMisure.html('');
                        
                        //azzero il parziale e il totale preventivo
                        var $container = $(this).parent('.selezione-infissi');
                        $container.siblings('input[name=totale-infisso]').val("");
                        $container.siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val("");
                        $container.siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text('0');
                        aggiornoTotalePreventivo();
                        
                        

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
                                $(document).on('click', 'input[name=conferma-misure]', function(){
                                //$('input[name=conferma-misure]').click(function(){
                                    
                                    
                                    var $container = $(this).parent('.selezione-misure');
                                    
                                    var nInfissi = $container.siblings('.numero-infissi').find('input[name=numero-infissi]').val();
                                    //ottengo il valore del prezzo
                                    var row = $(this).siblings('.table-misure').find('input[name=infisso-altezza]').val();
                                    var col = $(this).siblings('.table-misure').find('input[name=infisso-lunghezza]').val();
                                    
                                    //sistemo il valore dell'attributo value
                                    $(this).siblings('.table-misure').find('input[name=infisso-altezza]').attr('value', row);
                                    $(this).siblings('.table-misure').find('input[name=infisso-lunghezza]').attr('value', col);
                                    
                                    if(row !== '' && row !== null && col !== '' && col !== null){

                                        //chiamata ajax per il prezzo
                                        $.ajax({
                                            type:'POST',
                                            dataType: 'json',
                                            url: $urlAjax,
                                            data: {id_tabella_2 : idTabella, row : row, col : col },
                                            success: function(data){
                                                //var totale = printPrezzo(data);
                                               
                                                //vado a beccare il numero degli infissi
                                                
                                                
                                                var totaleParziale = parseFloat(data).toFixed(2); 
                                                var totale = totaleParziale * nInfissi;
                                                totale = parseFloat(totale).toFixed(2);
                                                
                                                //inserisco il prezzo nel parziale
                                                $container.siblings('.spesa-parziale-infisso').find('input[name=spesa-parziale-infisso]').val(totaleParziale);
                                                //aggiorno il prezzo totale dell'infisso                                            
                                                $container.siblings('.spesa-parziale-infisso').find('span.spesa-infisso').text(totale);

                                                //inserisco il prezzo dell'infisso senza maggiorazioni
                                                $container.siblings('input[name=totale-infisso]').val(totaleParziale);

                                                //aggiorno il preventivo totale
                                                aggiornoTotalePreventivo();                                            
                                            }
                                        });
                                        
                                        //dal seleziona apertura devo capire se si stratta di inferriata fissa o no                                       
                                        var nAnte = $container.siblings('.selezione-ante').find('select').val();   
                                        $container.siblings('.selezione-apertura').find('.box').addClass('hidden'); 
                                        $container.siblings('.selezione-apertura').find('.separator').addClass('hidden'); 
                                        if(nAnte !== '0'){                                           
                                            $container.siblings('.selezione-apertura').find('.interna').removeClass('hidden');
                                            $container.siblings('.selezione-apertura').find('.esterna').removeClass('hidden');
                                            $container.siblings('.selezione-apertura').find('.separator').removeClass('hidden'); 
                                        }
                                        else{
                                            
                                            $container.siblings('.selezione-apertura').find('.fx').removeClass('hidden');                                            
                                        }
                                        
                                        $container.siblings('.selezione-apertura').removeClass('hidden');
                                    }
                                    else{
                                        alert('Le misure da inserire sono obbligatorie!');
                                    }
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
        $(document).on('focusout change', 'input[name=numero-infissi]', function(){
        //$('input[name=numero-infissi]').on('focusout change', function(){
            var nInfissi = $(this).val();
            $(this).attr('value', nInfissi);
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
    
    function printAntaPrincipale(type, ante, $box, numInfisso){
        var html = '';
        ante = parseInt(ante);
        var antaPrincipale = '';
        var posizioneSerratura = '';        
        /*
        var widthAnta = 0;
        if(type==='F'){
            widthAnta = 85;
        }
        else{
            widthAnta = 76;
        }
        
        var widthContainer = widthAnta*ante;  
        */
        var radioName = 'radio-posizione-serratura-'+numInfisso;
        var serratura = '<p class="descrizione">Indica dove posizionare la serratura sull\'anta di apertura</p><div class="tipo radio clear"><input type="radio" name="'+radioName+'" value="S" checked /><label>SINISTRA</label><input type="radio" name="'+radioName+'" value="D" /><label>DESTRA</label><div class="clear"></div></div>';
        var descrizione1 = '<p class="descrizione" style="margin-left:45px;">Per procedere, fai click sull\'anta di apertura</p>';
        var descrizione2 = '<p class="descrizione" style="margin-left:45px;">Per procedere, fai click sull\'anta principale</p>';
        
        //Dai valore di type (se porta o finestra) e il numero di ante si ottiene un diverso scenario
        if(ante === 0){
            //non ho bisogno di sapere quale sia l'anta principale
            antaPrincipale = 'C';
            html += '<p class="step">6. Seleziona l\'anta principale</p>';
            html+= descrizione2;
            html+='<div style="margin-left:45px;" class="order seleziona-anta '+type+'" data-anta="C"></div>';
            html+='<div class="clear"></div>';
            posizioneSerratura = 'N';
        }
        else if(ante === 1){
            //se il numero di ante è uguale a 1, devo sapere se la serratura va a destra o sinistra
            antaPrincipale = 'C';
            html += '<p class="step">6. Seleziona la posizione della serratura</p>';
            html+= descrizione1;
            html+='<div style="margin-left:45px;" class="order seleziona-anta '+type+'" data-anta="C"></div>';
            html+='<div class="clear"></div>';
            html += serratura;
            posizioneSerratura = 'S';
        }
        else if(ante === 2){
            //se il numero di ante è uguale a 2, devo poter indicare quale delle due è la principale
            html += '<p class="step">6. Seleziona l\'anta principale</p>';
            html+= descrizione1;
            //html+='<div class="container-seleziona-anta" style="width:'+widthContainer+'px">';
            html+='<div style="margin-left:45px;" class="order seleziona-anta '+type+'" data-anta="S"></div>';
            html+='<div class="order seleziona-anta '+type+'" data-anta="D"></div>';
            html+='<div class="clear"></div>';
            html += serratura;
            posizioneSerratura = 'S';
        }
        else if(ante === 3){
            //se il numero di ante è uguale a 3, devo poter indicare quale è l'anta principale e la posizione della serratura
            html += '<p class="step">6. Seleziona l\'anta principale e indica la posizione della serratura</p>';
            html+= descrizione1;
            //html+='<div class="container-seleziona-anta" style="width:'+widthContainer+'px">';
            html+='<div style="margin-left:45px;" class="order seleziona-anta '+type+'" data-anta="S"></div>';
            html+='<div class="order seleziona-anta '+type+'" data-anta="C"></div>';
            html+='<div class="order seleziona-anta '+type+'" data-anta="D"></div>';
            html+='<div class="clear"></div>';
            html += serratura;
            posizioneSerratura = 'S';
        }
        else if(ante === 4){
            html += '<p class="step">6. Seleziona l\'anta principale e indica la posizione della serratura</p>';
            html+= descrizione1;
            //html+='<div class="container-seleziona-anta" style="width:'+widthContainer+'px">';
            html+='<div style="margin-left:45px;" class="order seleziona-anta '+type+'" data-anta="S"></div>';
            html+='<div class="order seleziona-anta '+type+'" data-anta="SC"></div>';
            html+='<div class="order seleziona-anta '+type+'" data-anta="DC"></div>';
            html+='<div class="order seleziona-anta '+type+'" data-anta="D"></div>';
            html+='<div class="clear"></div>';
            html += serratura;
            posizioneSerratura = 'S';
        }
        
        html+='<p class="descrizione"><strong>NOTA BENE:</strong> Vista interna.</p>';
        
        html += '<input type="hidden" name="anta-principale" value="'+antaPrincipale+'" />';
        html += '<input type="hidden" name="posizione-serratura" value="'+posizioneSerratura+'" />';
        
        $box.html(html);
    }
    
    
    function printMisure(data, $boxMisure){
        
        var minH = parseInt(data.start_rows);
        //var maxH = parseInt(data.end_rows);
        //var stepH = parseInt(data.step_rows);
        var minL = parseInt(data.start_cols);
        //var maxL = parseInt(data.end_cols);
        //var stepL = parseInt(data.step_cols);
        
        var html = '<p class="step">4. Indica le misure in millimetri</p>';
        html += '<table class="table-misure">';
              
        html+= '<tr><td><label>Larghezza (mm)</label></td>';
        html+= '<td><input type="number" value="'+minL+'"  name="infisso-lunghezza" /></td></tr>';
        html += '<tr><td><label>Altezza (mm) </label></td>';
        html+= '<td><input type="number" value="'+minH+'"  name="infisso-altezza" /></td></tr>'; 
        html+='</table>';
        
        html+= '<p class="istruzioni">Le misure vanno prese da FORO MURO</p>';
        
        html+= '<input type="button" name="conferma-misure" value="conferma misure" /><br><br>';       
        
        $boxMisure.html(html);
        $boxMisure.removeClass('hidden');        
        
    }
    
    function printAnte(data, $element, $button, type){
        $(function(){
            
            var html = '<p class="step">2. Scegli il numero di ante</p>';
            html += '<select name="seleziona-ante">';
            for(var i=0; i< data.length; i++){
                if(data[i].ante === '0'){
                    html+= '<option value="'+data[i].ante+'">inferriata fissa</option>';
                }
                else{
                    html+= '<option value="'+data[i].ante+'">'+data[i].ante+'</option>';
                }
            }
            html += '</select>';
            
            if(type === 'F'){
                html+='<div id="show-img-infisso" class="f0"></div>';
            }
            else{
                html+='<div id="show-img-infisso" class="p1"></div>';
            }              
            html+='<div class="clear"></div>';            
            $element.html(html);
            $button.removeClass('hidden');
           
        });
    }
    
    function printInfissi(data, $infissi, type, ante){
        $infissi.html('');
        $infissi.removeClass('hidden');        
        var html = '<p class="step">3. Selezione l\'articolo desiderato</p>';
        for(var i=0; i < data.length; i++){
            var nuovaClasse = type+ante;
            if (data[i].nome.toLowerCase().indexOf("libro") >= 0){
                nuovaClasse+='L';
            }
            
            html+='<div class="box order infisso '+nuovaClasse+'" data-type="infisso" data-name="'+data[i].ID+'" >';
            html+='<input type="hidden" name="infisso-id" value="'+data[i].ID+'" />';
            html+='<p>'+data[i].nome+'</p>';           
            html+='</div>';
        }
        
        html+='<div style="clear:both; width:100%"></div>';
        
        $infissi.html(html);
    }
    
    //gestione automatizzata dei due tipi di colori
    function gestoreSelettoreColori(){
        
        //La gestione dei colori deve tenere conto di tre opzioni:
        //1. Se il selettore è puntato sul RAL allora MICACEI e ZINCATURA devono essere deselezionati
        //2. Se il selettore è puntato su MICACEI, RAL e ZINCATURA devono essere deselezionati
        //3. Se il selettore è puntato su ZINCATURA, RAL e MICACEI devono essere deselezionati
        //Quando avviene questa selezione/deselezione, al campo nascosto "colore-scelto" deve essere modificato il valore di conseguenza
        
        $(document).on('click', '.ral-box.selettore-box', function(){
        //$('.ral-box.selettore-box').click(function(){
            $(this).siblings('.select-ral').slideToggle();
        });
        
        $(document).on('click', '.select-ral > div', function(){
        //$('.select-ral > div').click(function(){
            //se faccio click sul selettore del ral, devo deselezionare gli altri
            $(this).siblings('.micacei').find('.box').removeClass('selected');
            $(this).siblings('.box-zincatura').find('.box').removeClass('selected');
        });
        
        //1. Se il selettore è puntato sul RAL allora MICACEI e ZINCATURA devono essere deselezionati
        $(document).on('click', '.select-ral div', function(){
        //$('.select-ral div').click(function(){
            var $box = $(this).parent('.select-ral').siblings('.ral-box');
            var $micacei = $(this).parent('.select-ral').siblings('.micacei');
            var $zincatura = $(this).parent('.select-ral').siblings('.box-zincatura');
            var $select = $(this).parent('.select-ral');
            var $colore = $(this).parent('.select-ral').siblings('input[name=colore-scelto]');           
            
            $box.find('.selettore-show').html('');
            $(this).clone().appendTo($box.find('.selettore-show'));
            $select.hide();    
            
            
            //se il selettore è il RAL personalizzato, mostro l'input per inserirlo
            if($(this).hasClass('personalizzato')){
                $box.siblings('.ral-personalizzato').removeClass('hidden');
            }
            else{
                $box.siblings('.ral-personalizzato').addClass('hidden');
                $box.siblings('.ral-personalizzato').find('input').val('');
            }
            
            //deseleziono i micacei e zincatura            
            $micacei.find('.box').removeClass('selected');
            $zincatura.find('.box').removeClass('selected');           
           
            //mostro il punto successivo se ho effettuato una scelta diversa da none           
            if($(this).data('name')!== 'none'){        
                $colore.val($(this).data('name'));
                $select.parent('.selezione-colore').siblings('.selezione-cerniera').removeClass('hidden');                
            }
            else{
                $select.parent('.selezione-colore').siblings('.selezione-cerniera').addClass('hidden');
                $select.parent('.selezione-colore').siblings('.selezione-cerniera').find('.box').removeClass('selected');
            }
            
        });      
        //2. Se il selettore è puntato su MICACEI, RAL e ZINCATURA devono essere deselezionati
        $(document).on('click', '.micacei div', function(){
        //$('.micacei div').click(function(){
           var $ral = $(this).parent('.micacei').siblings('.ral-box');
           var $zincatura = $(this).parent('.micacei').siblings('.box-zincatura');
           var $colore = $(this).parent('.micacei').siblings('input[name=colore-scelto]');
           
           //deseleziono il ral e zincatura
           $ral.find('.selettore-show').html('<div class="none" data-type="colore" data-name="none">selezione RAL</div>');
           $ral.siblings('.ral-personalizzato').addClass('hidden');
           $ral.siblings('.ral-personalizzato').find('input').val('');
            
            $zincatura.find('.box').removeClass('selected');
           
           
           //aggiorno il colore
           $colore.val($(this).data('name'));
           
           //tolgo la classe hidden alla cerniera
           $(this).parent('.micacei').parent('.selezione-colore').siblings('.selezione-cerniera').removeClass('hidden');
           
           
        });
        //3. Se il selettore è puntato su ZINCATURA, RAL e MICACEI devono essere deselezionati
        $(document).on('click', '.box-zincatura div', function(){
        //$('.box-zincatura div').click(function(){
            var $ral = $(this).parent('.box-zincatura').siblings('.ral-box');
            var $micacei = $(this).parent('.box-zincatura').siblings('.micacei');
            var $colore = $(this).parent('.box-zincatura').siblings('input[name=colore-scelto]');
            
            //deselziono il ral e micacei
            $ral.find('.selettore-show').html('<div class="none" data-type="colore" data-name="none">selezione RAL</div>');
            $ral.siblings('.ral-personalizzato').addClass('hidden');
            $ral.siblings('.ral-personalizzato').find('input').val('');
            $micacei.find('.box').removeClass('selected');
            
            //aggiorno il colore
            $colore.val($(this).data('name'));     
            
            //tolgo la classe hidden alla cerniera
            $(this).parent('.box-zincatura').parent('.selezione-colore').siblings('.selezione-cerniera').removeClass('hidden');
           
        });
        
        
    }    

    //gestione del selettore dei ral
//    function selettoreRAL(){        
//       gestoreSelettoreColori('ral');    
//    }
//    
//    //gestione del selettore dei micacei
//    function selettoreMICACEI(){
//        gestoreSelettoreColori('micacei');
//    }
        
    //Gestione selettore delle maggiorazioni
    function selettoreMaggiorazioni(){
        $(document).on('click', '.box-2', function(){
        //$('.box-2').click(function(){
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
            var parziale = 0;
            if($(this).val() !== ''){
                parziale = $(this).val();
            }
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
        checkOrder();
        checkHelp();
        selettoreMaggiorazioni();
        selectInfisso();
        searchInfisso();
        gestoreSelettoreColori();
        changeNumeroInfissi();
        
        uploadFoto();
        
        /*
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
        */
        
        $('.selezione-cerniera .box').click(function(){
            //$('.maggiorazioni').show();
            //$('.numero-infissi').show();
            $('input[name=aggiungi-infisso]').removeAttr('disabled');
            $('input[name=aggiungi-copia-infisso]').removeAttr('disabled');
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
        
    function uploadFoto(){
        $(function () {
            $('#fileupload').fileupload({
                dataType: 'json',
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                /*add: function (e, data) {
                    data.context = $('<button/>').text('Upload')
                        .appendTo($('div#description'))
                        .click(function () {
                            data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                            data.submit();
                        });
                },*/
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {                        
                        if(typeof(file.error) !== 'undefined'){
                            alert(file.error);
                        }else{
                            var counter = 0;
                            $('.upload-immagini input.input-immagine').each(function(){
                               if($(this).val() === ''){
                                   counter = $(this).data('img');
                                   return false;
                               } 
                            });                            
                            
                            if(counter !== 0){
                                var html = '<div id="img-'+counter+'"><label>'+file.name+'</label><input type="button" class="delete" data-img="'+counter+'" data-name="'+file.name+'" value="CANCELLA"></div>';
                                $(html).appendTo($('div#description'));
                                //$('<p/>').text(file.name).appendTo($('div#description'));
                                $('.upload-immagini input.input-immagine').each(function(){
                                    //alert($(this).val());
                                    if($(this).val() === ''){
                                        $(this).val(file.name);
                                        return false;
                                    }
                                });
                            }
                            else{
                                alert('qualcosa è andato storto');
                            }
                            
                        }
                    });
                    
                },
                error: function (e, data){
                    alert(data.error);
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .bar').css(
                        'width',
                        progress + '%'
                    );
                }
            });
            
            $(document).on('click', 'input.delete', function(){
                var idImg = $(this).data('img'); 
                $.ajax({
                    type:'POST',
                    dataType: 'json',
                    url: $urlAjax,
                    data: {nomeFile: $(this).data('name')},
                    success: function(data){                  
                        //rimuovo il div dell'immagine
                        
                    }
                });
                $('div#img-'+idImg).remove();
                $('#progress .bar').css('width', '0');
                //pulisco l'input hidden
                $('input[data-img="'+idImg+'"]').val('');
            });
            
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
    
    function reset(copia, ante, nInfissi){
        
        if(copia === false){
            //resetto le selezioni
            $('.nuovo-infisso .selected').removeClass('selected');

            //resetto i contenuti
            $('.nuovo-infisso .container-order').addClass('hidden');
            $('.nuovo-infisso .tipo-infisso').removeClass('hidden');
            $('.nuovo-infisso input[name=ral-personalizzato]').val('');
            $('.nuovo-infisso .micacei').removeClass('hidden');
            $('.nuovo-infisso .box-zincatura').removeClass('hidden');
            
            
            
            $('.nuovo-infisso .selezione-ante').html('');
            //$('.nuovo-infisso .selezione-ante').addClass('hidden');
            $('.nuovo-infisso .ricerca').addClass('hidden');
            $('.nuovo-infisso .selezione-infissi').html('');
            //$('.nuovo-infisso .selezione-infissi').addClass('hidden');
            $('.nuovo-infisso .selezione-misure').html('');
            //$('.nuovo-infisso .selezione-misure').addClass('hidden');
            //$('.nuovo-infisso .selezione-apertura').addClass('hidden');
            //$('.nuovo-infisso .particolari-costruttivi').addClass('hidden');
            //$('.nuovo-infisso .container-serratura').addClass('hidden');
            //$('.nuovo-infisso .container-nodo').addClass('hidden');
            //$('.nuovo-infisso .selezione-colore').addClass('hidden');
            $('.nuovo-infisso .selezione-colore .ral-box .selettore-show').html('<div class="none" data-type="colore" data-name="none">seleziona RAL</div>');
            //$('.nuovo-infisso .selezione-colore .micacei-box .selettore-show').html('<div class="none" data-type="colore" data-name="none">selezione Micacei</div>');
            //$('.nuovo-infisso .selezione-cerniera').addClass('hidden');
            $('.nuovo-infisso input[name=totale-infisso]').val('');
            //$('.nuovo-infisso .maggiorazioni').addClass('hidden');
            $('.nuovo-infisso .numero-infissi').addClass('hidden');
            $('.nuovo-infisso input[name=numero-infissi]').val(1);
            $('input[name=aggiungi-infisso]').prop('disabled', true);
            $('input[name=aggiungi-copia-infisso]').prop('disabled', true);

            $('.nuovo-infisso .spesa-parziale-infisso span').text('0');
            $('.nuovo-infisso input[spesa-parziale-infisso]').val('');
            
            $('.nuovo-infisso .selezione-anta-principale').html('');
        }
        else{            
            $('.nuovo-infisso .selezione-ante select').val(ante); 
            $('.nuovo-infisso input[name=numero-infissi]').val(nInfissi);
            
        }
        
        var nInfisso = $('.selezione-container').size();
        $('.nuovo-infisso').find('h3 span').text(nInfisso);
        
        //rimuovo il nuovo infisso
        $('.nuovo-infisso').removeClass('nuovo-infisso');
    }
        
    function cleanPreventivatore(){
        //La funzione elimina tutti gli infissi eccetto il primo
        $('.selezione-container').each(function(){
            if($(this).data('infisso') !== 1){
                //elimino tutti gli altri infissi
                $(this).hide();
                $(this).remove();
            }
            else{
                //resetto il primo infisso
                $(this).find('.selected').removeClass('selected');

                //resetto i contenuti
                $(this).find('.container-order').addClass('hidden');
                $(this).find('.tipo-infisso').removeClass('hidden');
                
                $(this).find('input[name=ral-personalizzato]').val('');
                $(this).find('.micacei').removeClass('hidden');
                $(this).find('.box-zincatura').removeClass('hidden');

                $(this).find('.selezione-ante').html('');
                
                $(this).find('.ricerca').addClass('hidden');
                $(this).find('.selezione-infissi').html('');               
                $(this).find('.selezione-misure').html('');
               
                $(this).find('.selezione-colore .ral-box .selettore-show').html('<div class="none" data-type="colore" data-name="none">seleziona RAL</div>');
                
                $(this).find('input[name=totale-infisso]').val('');
                
                $(this).find('.numero-infissi').addClass('hidden');
                $(this).find('input[name=numero-infissi]').val(1);
                $('input[name=aggiungi-infisso]').prop('disabled', true);
                $('input[name=aggiungi-copia-infisso]').prop('disabled', true);

                $(this).find('.spesa-parziale-infisso span').text('0');
                $(this).find('input[name=spesa-parziale-infisso]').val('');
                

                $(this).find('.selezione-anta-principale').html('');
                
                //pulisco i campi del preventivo
                $('input[name=nome-cliente]').val('');
                $('input[name=via-cliente]').val('');
                $('input[name=telefono-cliente]').val('');
                $('input[name=mail-cliente]').val('');
                $('input[name=cf]').val('');
                $('textarea[name=note]').val('');
                $('textarea[name=note]').text('');
                
                //pulisco le immagini caricate
                $('.upload-immagini #description > div').remove();
                $('#progress .bar').css('width', '0');
                $('.upload-immagini input.input-immagine').val('');
            }
            
            aggiornoTotalePreventivo();
        });
    }
    
    //Gestione aggiungi infisso
    function aggiungiInfisso(){
        
        $(document).on('click', 'input[name=aggiungi-infisso]', function(){  
        //$('input[name=aggiungi-infisso]').click(function(){
            //memorizzo l'ultimo infisso
            var $lastInfisso =  $('.selezione-container').last();      
            var valueSerratura = $lastInfisso.find('input[name=posizione-serratura]').val(); 
            var tipoRal = $lastInfisso.find('.tipo-ral input:checked').val();    
            var tipoVerniciaturaRal = $lastInfisso.find('.tipo-verniciatura-ral input:checked').val();
            var tipoVerniciaturaMicaeo = $lastInfisso.find('.tipo-verniciatura-micaceo input:checked').val();
            var numInfisso = parseInt($lastInfisso.data('infisso')) + 1;
            
            //console.log(numInfisso);
            
            //ho un problema nel mantenere la selezione nel input radio della posizione della serratura
            //faccio un work-around per cercare di tenere la selezione
            //NB. E' solo di carattere estetico. L'informazione è contenuta nell'input nascosta
                                               
            //clono
            $lastInfisso.clone(true, true).addClass('nuovo-infisso').appendTo('#container-infissi');
            //aumento il numero infisso
            $('.nuovo-infisso').data('infisso', numInfisso);
            $('.nuovo-infisso').attr('data-infisso', numInfisso);
            
            //sistemo l'input radio dell'ultimo elemento
            $lastInfisso.find('.tipo.radio input').removeAttr('checked');
            $('.nuovo-infisso').find('.tipo.radio input').removeAttr('checked');
            $('.nuovo-infisso').find('.tipo.radio input').attr('name', 'radio-posizione-serratura-'+numInfisso);
            
            $lastInfisso.find('.tipo.radio input').each(function(){
                 if($(this).val() === valueSerratura){
                     $(this).prop('checked', true);
                 }
             });
             
            //sistemo l'input radio del tipo di RAL
            fixRadioInput($lastInfisso, 'ral', numInfisso, tipoRal);
            //sistemo l'input radio del tipo Verniciatura RAL
            fixRadioInput($lastInfisso, 'verniciatura-ral', numInfisso, tipoVerniciaturaRal);
            //sistemo l'input radio del tipo Verniciatura Micaceo
            fixRadioInput($lastInfisso, 'verniciatura-micaceo', numInfisso, tipoVerniciaturaMicaeo);
            
            //aggiungo un pulsante di elimina infisso
            var html = '<input type="button" name="cancella-infisso" value="cancella infisso" />';
            $(html).appendTo('.nuovo-infisso');
            //resetto 
            reset(false, null);              
        });
        
        $(document).on('click', 'input[name=aggiungi-copia-infisso]', function(){  
        //$('input[name=aggiungi-copia-infisso]').click(function(){
            //memorizzo l'ultimo infisso
            var $lastInfisso =  $('.selezione-container').last();      
            //devo replicare il numero di ante nel select nuovo
            var nAnte = $lastInfisso.find('.selezione-ante select').val(); 
            var nInfissi = $lastInfisso.find('input[name=numero-infissi]').val();
            var tipoRal = $lastInfisso.find('.tipo-ral input:checked').val();   
            var tipoVerniciaturaRal = $lastInfisso.find('.tipo-verniciatura-ral input:checked').val();
            var tipoVerniciaturaMicaeo = $lastInfisso.find('.tipo-verniciatura-micaceo input:checked').val();
            var numInfisso = parseInt($lastInfisso.data('infisso')) + 1;
            var valueSerratura = $lastInfisso.find('input[name=posizione-serratura]').val(); 
            
            //$('.selezione-container').first().clone(true, true).addClass('nuovo-infisso').appendTo('#container-infissi');
            var htmlInfisso = $lastInfisso.html();          
            htmlInfisso = '<div class="selezione-container nuovo-infisso" data-infisso="'+numInfisso+'">'+htmlInfisso+'</div>';            
            var $newInfisso = $.parseHTML(htmlInfisso);
            $($newInfisso).appendTo('#container-infissi');
            
            
            //aumento il numero infisso
            //$('.nuovo-infisso').data('infisso', numInfisso);
            
            //sistemo l'input radio del primo elemento
            //rimuovo i radio checked
            //sistemo l'input radio dell'ultimo elemento
            $lastInfisso.find('.tipo.radio input').removeAttr('checked');
            $('.nuovo-infisso').find('.tipo.radio input').removeAttr('checked');
            $lastInfisso.find('.tipo.radio input').each(function(){
                 if($(this).val() === valueSerratura){
                     $(this).prop('checked', true);
                 }
             });
            $('.nuovo-infisso').find('.tipo.radio input').attr('name', 'radio-posizione-serratura-'+numInfisso);
            $('.nuovo-infisso').find('.tipo.radio input').each(function(){
                 if($(this).val() === valueSerratura){
                     $(this).prop('checked', true);
                 }
            });
            
            //sistemo l'input radio del tipo di RAL
            fixRadioInput($lastInfisso, 'ral', numInfisso, tipoRal);                        
            //sistemo l'input radio del tipo Verniciatura RAL
            fixRadioInput($lastInfisso, 'verniciatura-ral', numInfisso, tipoVerniciaturaRal);
            //sistemo l'input radio del tipo Verniciatura Micaceo
            fixRadioInput($lastInfisso, 'verniciatura-micaceo', numInfisso, tipoVerniciaturaMicaeo);
            
            //aggiungo un pulsante di elimina infisso
            var html = '<input type="button" name="cancella-infisso" value="cancella infisso" />';
            $(html).appendTo('.nuovo-infisso');  
            
            
            
            //resetto 
            reset(true, nAnte, nInfissi);  
            //essendo una copia, devo aggiornare anche il totale del preventivo
            aggiornoTotalePreventivo();
        });
        
        
    } 
    
    //La funzione serve a gestire le copie degli input radio
    function fixRadioInput($lastInfisso, tipoValore, numInfisso, valore){
        
        //var valore = $lastInfisso.find('.tipo-'+tipoValore+' input:checked').val();
        $lastInfisso.find('.tipo-'+tipoValore+' input').removeAttr('checked');
        $('.nuovo-infisso').find('.tipo-'+tipoValore+' input').removeAttr('checked');
        $('.nuovo-infisso').find('.tipo-'+tipoValore+' input').attr('name', 'tipo-'+tipoValore+'-'+numInfisso);

        $lastInfisso.find('.tipo-'+tipoValore+' input').each(function(){
             if($(this).val() === valore){
                 $(this).prop('checked', true);
             }
         });

         $('.nuovo-infisso').find('.tipo-'+tipoValore+' input').each(function(){
             if($(this).val() === valore){
                 $(this).prop('checked', true);
             }
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
           $('input[name=aggiungi-copia-infisso]').removeAttr('disabled');
        });
    }
    
    
    function inviaPreventivo(){
        $('input[name=invia-preventivo]').click(function(){
            
            $('.loading-container').removeClass('hidden');
            /*
            * Quando viene inviato un preventivo devo effettuare un controllo dei dati compilati:
            * 1. Data odierna
            * 2. Nome rivenditore/agente
            * 3. Nome cliente
            * 4. Via cliente
            * 5. Telefono cliente
            * 5a. Tipologia (preventivo/ordine)
            * 5b. Note
            * 5c. Tipo cliente
            * 5d. Email cliente
            * 5e. CF cliente
            * 5f. Foto
            * ciclo su tutti div selezione-container
            * controllo se sono stati compilati i diversi campi:
            * 6. Selezione su tipo di infisso (finestra o porta)
            * 7. Selezione sul numero di ante
            * 8. Selezione sullo specifico infisso
            * 9. Selezione della misura di altezza
            * 10. Selezione della misura di lunghezza
            * 11. Selezione sul tipo di apertura 
            * 11a. anta principale
            * 11b. posizione serratura
            * 12. Selezione sul tipo di barra
            * 13. Selezione sul tipo di serratura
            * 14. Selezione sul tipo di nodo
            * 15. Selezione sul colore
            * 15b. Seleziono il tipo di colore
            * 16. Selezione sul tipo di cerniera
            * 17. Selezione sulle maggiorazioni
            * 18. Selezione sul numero di infissi
            * 19. Spesa parziale infisso           
            * 
            */
           
            //1. Data odierna
            var preventivo = new Object();
            preventivo.data = $('input[name=data-odierna]').val();
            //2. Nome rivenditore/agente
            preventivo.rivenditore = $('input[name=rivenditore-agente]').val();
            preventivo.idUser = $('input[name=id-user]').val();
            //3. Nome cliente
            preventivo.clienteNome = $('input[name=nome-cliente]').val();
            //4. Via cliente
            preventivo.clienteVia = $('input[name=via-cliente]').val();
            //5. Telefono cliente
            preventivo.clienteTel = $('input[name=telefono-cliente]').val();
            //5a. Tipo
            preventivo.tipo = $('input[name=tipo]:checked').val();
            //5b. Note
            preventivo.note = $('textarea[name=note]').val();
            //5c. Tipo cliente
            preventivo.clienteTipo = $('input[name=tipo-cliente]:checked').val();
            //5d. Email cliente
            preventivo.clienteEmail = $('input[name=mail-cliente]').val();
            //5e. CF cliente
            preventivo.clienteCF = $('input[name=cf]').val();
            //5f. Foto
            var foto = new Array();
            var countFoto = 0;
            $('input.input-immagine').each(function(){
                if($(this).val() !== ''){                    
                    foto[countFoto] = $(this).val();
                    countFoto++;
                }
            });
            
            preventivo.foto = foto;
            
            
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
                if( typeof($(this).find('input[name=infisso-altezza]').val()) !== 'undefined'){
                    infisso['altezza'] = $(this).find('input[name=infisso-altezza]').val();
                }
                //10. Selezione della misura di lunghezza
                if(typeof($(this).find('input[name=infisso-lunghezza]').val()) !== 'undefined'){
                    infisso['lunghezza'] = $(this).find('input[name=infisso-lunghezza]').val();
                }
                
                //11a. Seleziono anta principale
                if(typeof($(this).find('input[name=anta-principale]').val()) !== 'undefined' && $(this).find('input[name=anta-principale]').val() !== '' ){
                    infisso['anta-principale'] = $(this).find('input[name=anta-principale]').val();
                }                
                //11b. Seleziono posizione serratura
                if(typeof($(this).find('input[name=posizione-serratura]').val()) !== 'undefined'){
                    infisso['posizione-serratura'] = $(this).find('input[name=posizione-serratura]').val();
                }
                
                //15. Selezione sul colore
                // 15.ral --> controllo i RAL
                if($(this).find('input[name=colore-scelto]').val() !== ''){
                    if($(this).find('.ral-box .selettore-show div').data('name') !== 'none'){                    
                        //15b --> se il RAL è selezionato, considero anche il tipo 
                        //di default inserisco anche il possibile valore di ral personalizzato che potrebbe esserci o meno
                        var ralPersonalizzato = $(this).find('input[name=ral-personalizzato]').val();

                        infisso['colore'] = $(this).find('input[name=colore-scelto]').val()+' '+ralPersonalizzato+' '+$(this).find('.tipo-ral input:checked').val();                   
                        infisso['verniciatura'] = $(this).find('.tipo-verniciatura-ral input:checked').val();
                    }
                    else{
                        //se il valore del RAL non è indicato allora si tratta di un micaceo o solo zincatura

                        var micaceo = false;
                        $(this).find('.micacei div').each(function(){
                            if($(this).hasClass('selected')){
                                //il colore è micaceo
                                micaceo = true;
                            }
                        });
                        if(micaceo){
                           infisso['verniciatura'] = $(this).find('.tipo-verniciatura-micaceo input:checked').val();
                        } 
                        else{
                            infisso['verniciatura'] = '';
                        }

                        infisso['colore'] = $(this).find('input[name=colore-scelto]').val();
                    }  
                }   
                
                //18. Selezione sul numero di infissi   
                if(typeof($(this).find('input[name=numero-infissi]').val()) !== 'undefined'){
                    infisso['num-infissi'] = $(this).find('input[name=numero-infissi]').val();
                }
                
                //19. Spesa parziale infisso
                infisso['spesa-parziale'] = $(this).find('input[name=spesa-parziale-infisso]').val();
                                
                //console.log(Object.size(infisso));
                for (var i in infisso) {
                    if (infisso[i] === null || infisso[i] === undefined) {
                    // test[i] === undefined is probably not very useful here
                      delete infisso[i];
                    }
}
                
                console.log(infisso);
                
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
                $('.loading-container').addClass('hidden');
                //I campi non sono stati compilati nel modo corretto
                
                //console.log('campi non soddisfatti');
                //console.log(check);
                var html = "";
               
                $.each(check, function(index, value){
                    if(index >= 0 && index < 7){
                        if(index >= 0 && index < 6){
                            if(typeof(value) !== 'undefined'){
                                html+= '<div class="punto">- '+value+'</div>';
                            }
                        }
                        else if(index === 6){
                            html += '<div class="punto infisso" >Nell\'infisso n."'+value+'" serve: </div>';
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
                            html+= '<h4>Il preventivo è stato correttamente registrato!</h4>'; 
                            if(data.pdf !== false){
                                html+='<div class="punto"><a target="_blank" href="'+data.pdf+'">Rivedi la tua richiesta di preventivo</a></div>';
                                
                                if(data.mail === true){
                                    html+='<div class="punto">Email inviata all\'amministrazione!</div>';
                                }
                                else{
                                    html+='<div class="punto">Errore nell\'invio dell\'email!</div>';
                                }
                            }
                            else{
                                html+='<div class="punto">Errore nel salvataggio del pdf!</div>';
                            }
                            
                        }
                        else{
                            //il preventivo non è stato salvato correttamente nel database
                            html+='<div class="punto">Ci sono stati dei problemi nella registrazione del preventivo!</div>';
                        }
                        $('.loading-container').addClass('hidden');
                        $('.ok-box .message').html(html);
                        $('.ok-box').show();
                        //Pulisco il preventivatore;
                        cleanPreventivatore();
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
        if(preventivo.rivenditore === '0'){
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
        
        if(typeof(preventivo.tipo) === 'undefined'){
            mancanti[5] = 'Indicare la tipologia (se preventivo o se ordine)';
        }
        
        //Controllo sui campi            
        for(var i=0; i < infissi.length; i++){
            if(typeof(infissi[i])!== 'undefined'){
                //console.log(infissi[i]);
                //controllo la dimensione dell'array
                //se è minore di 12, non sono stati compilati tutti i campi
                if(Object.size(infissi[i]) < 16){
                    
                    //il campo numero zero indica a quale infisso mancano i valori
                    mancanti[6] = i+1;                    
                    
                    //controllo i valori mancanti
                    if(!('tipo-infisso' in infissi[i])){
                        mancanti.push('Indicare il tipo di infisso');
                    }
                    if(!('numero-ante' in infissi[i])){
                        mancanti.push('Indicare il numero di ante dell\'infisso');
                    } 
                    if(!('infisso' in infissi[i])){
                        mancanti.push('Indicare l\'inifisso specifico');
                    } 
                    if(!('altezza' in infissi[i])){
                        mancanti.push('Indicare l\'altezza');
                    } 
                    if(!('lunghezza' in infissi[i])){
                        mancanti.push('Indicare la larghezza');
                    } 
                    if(!('apertura' in infissi[i])){
                        mancanti.push('Specifcare il tipo di apertura');
                    } 
                    if(!('anta-principale' in infissi[i])){
                        mancanti.push('Indicare l\'anta principale');
                    } 
                    if(!('posizione-serratura' in infissi[i])){
                        mancanti.push('Indicare la posizione serratura');
                    } 
                    if(!('barra' in infissi[i])){
                        mancanti.push('Indicare il tipo di barra');
                    }
                    if(!('serratura' in infissi[i])){
                        mancanti.push('Indicare il tipo di serratura');
                    } 
                    if(!('nodo' in infissi[i])){
                        mancanti.push('Indicare il tipo di nodo');
                    }  
                    if(!('colore' in infissi[i])){
                        mancanti.push('Selezionare il colore');
                    } 
                    if(!('cerniera' in infissi[i])){
                        mancanti.push('Indicare il tipo di cerniera');
                    }                    
                    if(!('num-infissi' in infissi[i])){
                        mancanti.push('Indicare il numero di infissi');
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
    
    
      
    
    //La funzione corregge le opzioni di visualizzazione nel caso venga scelta un'inferriata fissa o meno
    function checkInferriataFissa($element, ante){
        if(ante === '0'){
                //mostro solo il .fissa
                $element.find('.no-fissa').hide();
                $element.find('.fissa').show();
            }
            else{
                $element.find('.fissa').hide();
                $element.find('.no-fissa').show();                
            }
    }
    
    
    //funzione di conteggio dell'array
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    //funzione che cambia l'immagine dell'infisso a seconda delle ante selezionate
    $(document).on('change', 'select[name=seleziona-ante]', function(){    
        var tipo = $(this).parent('.selezione-ante').siblings('.tipo-infisso').find('.selected[data-type]').data('name');
        var ante = $(this).val();
        var newclass = "";
        
        if(tipo === 'Finestra'){
            if(ante == 0){
                newclass = "f0";
            }
            else if(ante == 1){
                newclass = "f1";
            }
            else if(ante == 2){
                newclass = "f2";
            }
            else if(ante == 3){
                newclass = "f3";
            }
            else if(ante == 4){
                newclass = "f4";
            }
        }
        else if(tipo === 'Portafinestra'){
            if(ante == 1){
                newclass = "p1";
            }
            else if(ante == 2){
                newclass = "p2";
            }
            else if(ante == 3){
                newclass = "p3";
            }
            else if(ante == 4){
                newclass = "p4";
            }
        }
        
        $(this).siblings('#show-img-infisso').removeClass();
        $(this).siblings('#show-img-infisso').addClass(newclass);
        
    });
    
    
});

