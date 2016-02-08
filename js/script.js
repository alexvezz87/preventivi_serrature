/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($){
    
    $urlAjax = $('input[name=ajax-url').val();
    
    
    //Inizalizzo tutte le funzioni in una funzione init
    function init(){        
        selectInfisso();
        searchInfisso();
        selettoreRAL();
        selettoreMICACEI();
        selettoreMaggiorazioni();
        
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
        
        $('.selezione-cerniera ,box').click(function(){
            $('.maggiorazioni').show();
        });
    }
    
    init();
    checkSelected();
    
      
    //gestione evento sui .box
    function checkSelected() {
        $('.box').click(function(){
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
            checkSelected();

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
        $('input[name=ricerca-infissi').click(function(){
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

                        var $boxMisure = $(this).parent('.selezione-infissi').siblings('.selezione-misure');
                        $boxMisure.hide();
                        $boxMisure.siblings('.selezione-apertura').hide();
                        $boxMisure.html('');

                        //gestione selezioni
                        checkSelected();

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
                                            printPrezzo(data);
                                            $boxMisure.siblings('input[name=totale-infisso]').val(data);
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
    
    
    function printPrezzo(data){
        $(function(){
            //ottengo la somma presente
            var totale = $('.prezzo-preventivo').text();
            //totale = parseFloat(totale).toFixed(2);
            
            totale= parseFloat(data).toFixed(2);
            
            $('.prezzo-preventivo').html('');
            $('.prezzo-preventivo').append(totale);
        });
    }
    
    function updatePrezzo(start, data, type){
        $(function(){
            //ottengo la somma presente
            var totale = $('.prezzo-preventivo').text();
            //totale = parseFloat(totale).toFixed(2);
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
            
            $('.prezzo-preventivo').html('');
            $('.prezzo-preventivo').append(totale);
        });
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
        
        
        //console.log($boxMisure);
        
        $boxMisure.append(html);
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
                        
            $element.append(html);
            $button.show();
           
        });
    }
    
    function printInfissi(data, $infissi){
        $infissi.html('');
        $infissi.show();
        var html = '<p class="step">3. Selezione l\'articolo desiderato</p>';
        for(var i=0; i < data.length; i++){
            html+='<div class="box infisso" >';
            html+='<input type="hidden" name="infisso-id" value="'+data[i].ID+'" />';
            html+='<p style="font-size:14px">'+data[i].ID+'<br>'+data[i].nome+'</p>';           
            html+='</div>';
        }
        
        html+='<div style="clear:both; width:100%"></div>';
        
        $infissi.append(html);
    }
        
    //gestione del selettore dei ral
    function selettoreRAL(){
        $('.select-ral div').click(function(){
            $('.selettore-show').html('');
            $(this).clone().appendTo('.selettore-show');
            $('.select-ral').hide();

            //quando faccio click sul ral, devo de-selezionare il micaceo        
           $(this).parent('.select-ral').parent('.selezione-colore').find('.box').each(function(){
               $(this).removeClass('selected'); 
            });
            
            //mostro il punto successivo
            $('.selezione-cerniera').show();
        });
    }
    
    //gestione del selettore dei micacei
    function selettoreMICACEI(){
        $('.selezione-colore .box').click(function(){
            //se seleziono un box micaceo, devo de-selezionare il RAL
            $(this).siblings('.selettore-box').find('.selettore-show').html('<div class="none">seleziona RAL</div>');
        });

        $('.selettore-box').click(function(){
            $('.select-ral').slideToggle();
        });
    }
        
    //Gestione selettore delle maggiorazioni
    function selettoreMaggiorazioni(){
        $('.box-2').click(function(){
            $(this).toggleClass('selected');
            //calcolo la spesa complessiva in toggle
            var qt = $(this).find('input[name=maggiorazione-qt]').val();
            var um = $(this).find('input[name=maggiorazione-um]').val().trim();

            //prendo il valore di partenza dell'infisso da utilizzare per i prezzi in percentuale
            var st = $(this).parent('.maggiorazioni').siblings('input[name=totale-infisso]').val();

            if($(this).hasClass('selected')){
                //aggiungo la spesa
                updatePrezzo(st, qt, um);
            }
            else{
                //sottraggo la spesa
                qt = '-'+qt;
                updatePrezzo(st, qt, um);
            }
        });
    }
    
    
    //Gestione aggiungi infisso
    $('input[name=aggiungi-infisso]').click(function(){
       $('.selezione-container').first().clone().addClass('nuovo-infisso').appendTo('#container-infissi');
       init();
       //resetto le selezioni
       $('.nuovo-infisso .selected').removeClass('selected');
       //rimuovo il nuovo infisso
       $('.nuovo-infisso').removeClass('nuovo-infisso');
       
       checkSelected();
       
       
    });
    
});