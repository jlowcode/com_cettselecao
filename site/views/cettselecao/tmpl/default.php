<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cettselecao
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<style>
    .divFiltros {
        display: none;
    }
    .divFiltros input, .divFiltros select {
        background: #fafafa;
    }
</style>

<div class="divFiltros">
    <h1>Lista de Inscritos</h1>
    <div class="row" style="background: #d0d0d0">
        <div class="col-md-12">
            <b style="color: #000">Filtros</b>
        </div>
        <div class="col-md-4">
            <label for="filtroNome">Nome</label>
            <input type="text" id="filtroNome" class="form-control" style="height: 33px;"/>
        </div>
        <div class="col-md-2">
            <label for="filtroCpf">Cpf</label>
            <input type="text" id="filtroCpf" class="form-control" style="height: 33px;"/>
        </div>
        <div class="col-md-2">
            <label for="filtroEmail">Email</label>
            <input type="text" id="filtroEmail" class="form-control" style="height: 33px;"/>
        </div>
        <div class="col-md-4">
            <label for="filtroCurso">Curso</label>
            <input type="text" id="filtroCurso" class="form-control" style="height: 33px;"/>
        </div>
        <div class="col-md-2">
            <label for="filtroRede">Rede</label>
            <select id="filtroRede" style="width: 100%">
                <option selected>Todas</option>
                <option value="EFG">EFG</option>
                <option value="COTEC">COTEC</option>
                <option value="GOIASTEC">GOIASTEC</option>
                <option value="JUVENTUDE">JUVENTUDE</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="filtroEscola">Escola</label>
            <select id="filtroEscola" style="width: 100%">
                <option selected>Todas</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="filtroEdital">Edital</label>
            <select id="filtroEdital" style="width: 100%">
            </select>
        </div>
        <div class="col-md-2">
            <label for="filtroTurno">Turno</label>
            <select id="filtroTurno" style="width: 100%">
                <option value="" selected>Todos</option>
                <option value="EAD">EAD</option>
                <option value="Integral">Integral</option>
                <option value="Matutino">Matutino</option>
                <option value="Noturno">Noturno</option>
                <option value="Vespertino">Vespertino</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filtroModalidade">Modalidade</label>
            <select id="filtroModalidade" style="width: 100%">
                <option value="" selected>Todas</option>
                <option value="EAD">EAD</option>
                <option value="Online">Online</option>
                <option value="Presencial">Presencial</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filtroTipo">Tipo</label>
            <select id="filtroTipo" style="width: 100%">
                <option value="" selected>Todos</option>
                <option value="Capacitacao">Capacitação</option>
                <option value="Qualificacao">Qualificação</option>
                <option value="Selecao">Seleção</option>
                <option value="Superior">Superior</option>
                <option value="Tecnico">Técnico</option>
            </select>
        </div>
        <div class="col-md-2">
            
            <label for="filtroPeriodoIni">Periodo: (Inicial e Final)</label>
            <input type="text" id="filtroPeriodoIni" style="width: 120px; height: 31px" placeholder="___/___/____" value="">
            <input type="text" id="filtroPeriodoFim" style="width: 120px; height: 31px" placeholder="___/___/____" value=""/>
        </div>
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <a href="javascript: limparFiltros();" class="btn btn-info" style="display: none; color: #fff; font-weight: bold">Limpar Filtros</a>
            <a href="javascript: aplicarFiltros();" class="btn btn-primary">Fitlrar</a>
            <a href="javascript: exportarCsv();" class="btn btn-warning" style="color: #fff; font-weight: bold">Exporta CSV</a>
            <br/>
            &nbsp;
        </div>
    </div>
</div>
&nbsp;
<br/>
<div class="divGrid">
    <table id="grid_inscritos" class="display table table-bordered table-striped hidden-xs hidden-sm">
        <thead>
            <tr>
                <th>Inscrição</th>
                <th>Nome</th>
                <th>Escola</th>
                <th>Curso</th>
                <th>Data/Hora</th>
                <th>Situação</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="hidden-lg hidden-md d-block d-md-none d-lg-none d-xl-none" id="responsive_grid_inscritos"></div>
</div>

<script>
    var data = [];
    var gmInscritos = null;
    
    jQuery(function(){
        jQuery('table,h1,label,input,select').css('color','#000');
        jQuery('#grid_inscritos').css('fontSize','13px');
        jQuery('div[class="dataTables_wrapper"').css('fontSize','13px');

        gmInscritos = jQuery('#grid_inscritos').dataTable( {
            "paging": true,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,

            "language": {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            
            "bJQueryUI": true,
            "bProcessing": true,
            "bDestroy": true,
            "bServerSide": true,
            "iDisplayLength": 10,
            "sPaginationType": "full",
            "sAjaxSource": "index.php?option=com_cettselecao&view=cettselecao",
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "task", "value": "inscricoes.filter" } );
                aoData.push( { "name": "format", "value": "json" } );
                aoData.push( { "name": "filtroNome", "value": jQuery('#filtroNome').val() } );
                aoData.push( { "name": "filtroCpf", "value": jQuery('#filtroCpf').val() } );
                aoData.push( { "name": "filtroEmail", "value": jQuery('#filtroEmail').val() } );
                aoData.push( { "name": "filtroCurso", "value": jQuery('#filtroCurso').val() } );                
                aoData.push( { "name": "filtroRede", "value": jQuery('#filtroRede').val() } );
                aoData.push( { "name": "filtroEscola", "value": jQuery('#filtroEscola').val() } );
                aoData.push( { "name": "filtroEdital", "value": jQuery('#filtroEdital').val() } );
                aoData.push( { "name": "filtroTurno", "value": jQuery('#filtroTurno').val() } );
                aoData.push( { "name": "filtroTipo", "value": jQuery('#filtroTipo').val() } );
                aoData.push( { "name": "filtroModalidade", "value": jQuery('#filtroModalidade').val() } );
                aoData.push( { "name": "filtroPeriodoIni", "value": jQuery('#filtroPeriodoIni').val() } );
                aoData.push( { "name": "filtroPeriodoFim", "value": jQuery('#filtroPeriodoFim').val() } );
            },
            "aaSorting": [[ 1, "desc" ]],
            "aoColumns": [ 
                { "sWidth": "5%", "bSortable": true },
                { "sWidth": "25%", "bSortable": true },
                { "sWidth": "15%", "bSortable": true },
                { "sWidth": "35%", "bSortable": true },
                { "sWidth": "10%", "bSortable": true },
                { "sWidth": "5%", "bSortable": true },
                { "sWidth": "5%", "bSortable": false }
            ],
            "fnDrawCallback": function(oSettings, json) {
                //Executa evento após carregar os itens no grid
                _gResponsive(
                    oSettings['aoData'],
                    'responsive_grid_inscritos',
                    [
                        'Inscrição:',
                        'Nome:',
                        'Escola:',
                        'Curso:',
                        'Data/Hora:',
                        'Situação:',
                        '&nbsp;'
                    ]
                 );

                 _montarBotoesAcao(oSettings['aoData']);
            }
        } );

        //Mascara CPF
		jQuery('#filtroCpf').keyup(function(){
			var valor = jQuery(this).val();
			valor = valor.replace(/\D/g, "");
			valor = valor.replace(/(\d{3})?(\d{3})?(\d{3})?(\d{2})/, "$1.$2.$3-$4");
			jQuery(this).val(valor);
		});

        //Mascara Periodo
		jQuery('#filtroPeriodoIni,#filtroPeriodoFim').keyup(function(){
			var valor = jQuery(this).val();
			valor = valor.replace(/\D/g, "");
			if(valor.length <= 2)
				valor = valor.replace(/(\d{2})/, "$1");
			else if(valor.length <= 5 && valor.length > 2)
				valor = valor.replace(/(\d{2})?(\d{2})/, "$1/$2");
			else
				valor = valor.replace(/(\d{2})?(\d{2})?(\d{4})/, "$1/$2/$3");
			jQuery(this).val(valor);
		});

        jQuery('input[id^="filtro"]').keypress(function(evt){
            if(evt.keyCode == 13) {
                aplicarFiltros();
            }
        });

        jQuery('select[id^="filtro"]').change(function(){
            aplicarFiltros();
        });

        jQuery('#filtroRede').change(function(){
            jQuery('#filtroEscola').html('<option value="">Aguarde! Carregando...</option>');
            jQuery('#filtroEdital').html('<option value="">Selecione uma Escola</option>');
            getEscolas();
        });

        jQuery('#filtroEscola').change(function(){
            getEditais();
        });

        verificarPermissoes();
    });

    //Verificar permissões
    function verificarPermissoes() {
        jQuery.ajax({
            url: 'index.php?option=com_cettselecao&view=cettselecao',
            method: 'post',
            async: false,
            data: { task: "inscricoes.access", format: "json" },
            success: function(result, status, xhr) { definirPermissoes(result); },
            error: function() { console.log('ajax call failed'); },
        });
    }

    var elemFiltro = [];
    function definirPermissoes(obj) {
        jQuery('input[id^="filtro"], select[id^="filtro"]').map(function(i,elem){
            elemFiltro.push(jQuery(elem).attr('id'));
        });

        tratamentoAcessoInicial(obj);
        if(obj['access']['admin'] !== true) {
            tratamentoAcessoInicial(obj);
            if(obj['access']['efg'] == true)
                aplicarAcessoEFG(obj['id_user']);
            if(obj['access']['cotec'] == true)
                aplicarAcessoCOTEC(obj['id_user']);
            if(obj['access']['goiastec'] == true)
                aplicarAcessoGOIASTEC(obj['id_user']);
            if(obj['access']['juventude'] == true)
                aplicarAcessoJUVENTUDE(obj['id_user']);
        }

        jQuery('.divFiltros').show();
    }

    function tratamentoAcessoInicial(obj) {
        limparFiltros();

        jQuery(elemFiltro).map(function(i,elem){
            jQuery('#'+elem).parent().show();
        });


        //Rede
        var rede = ['Todas','EFG','COTEC','GOIASTEC','JUVENTUDE'];
        var options = [];
        jQuery(rede).map(function(i,val){
            if(val == 'Todas')
                options.push('<option value="">'+val+'</option>');
            else
                options.push('<option value="'+val+'">'+val+'</option>');
        });
        jQuery('#filtroRede').html(options.join(''));      

        getEscolas();

        jQuery('#filtroEdital').html('<option value="">Selecione uma escola</option>');  
    }

    var arrEscolas = [];
    function getEscolas() {
        arrEscolas = [];
        //Pega as escolas vinculadas ao usuário
        jQuery.ajax({
            url: 'index.php?option=com_cettselecao&view=cettselecao',
            method: 'post',
            async: false,
            data: { task: "inscricoes.escolas", format: "json", params: {'rede': jQuery('#filtroRede').val()}},
            success: function(result, status, xhr) { 
                if(result.length > 0) {
                    var options = [];
                    arrEscolas = result;
                    options.push('<option value="">Todas</option>');
                    jQuery(arrEscolas).map(function(i,val){
                        if(val['rede'] == jQuery('#filtroRede').val() || jQuery('#filtroRede').val() == '')
                        {
                            options.push('<option value="'+val['id']+'">'+val['rede']+' - '+val['nome']+'</option>');
                        }
                    });

                    jQuery('#filtroEscola').html(options.join(''));
                }
             },
            error: function() { jQuery('#filtroEscola').html('<option value="">Nenhuma Escola encontrada.</option>'); },
        });
    }

    function getEditais() {
        jQuery('#filtroEdital').html('<option value="">Carregando...</option>');
        //Pega as escolas vinculadas ao usuário
        jQuery.ajax({
            url: 'index.php?option=com_cettselecao&view=cettselecao',
            method: 'post',
            async: false,
            data: { task: "inscricoes.editais", format: "json", params: {'escola': jQuery('#filtroEscola').val()}},
            success: function(result, status, xhr) { 
                if(result.length > 0) {
                    var options = [];
                    options.push('<option value="">Todos</option>');
                    jQuery(result).map(function(i,val){
                        options.push('<option value="'+val['id']+'">'+val['nome']+'</option>');
                    });

                    jQuery('#filtroEdital').html(options.join(''));
                }
             },
            error: function() { jQuery('#filtroEdital').html('<option value="">Nenhum Edital Vinculado.</option>'); },
        });
    }

    function aplicarAcessoEFG(id_user) {
        jQuery('#filtroRede').parent().hide().html('<option value="EFG">EFG</option>');
        jQuery('#filtroEscola').html('<option value="">Carregando...</option>');

        var options = [];
        jQuery(arrEscolas).map(function(i,val){
            if(val['administrador'] == id_user){
                options.push('<option value="'+val['id']+'">'+val['rede']+' - '+val['nome']+'</option>');
            }
        });
        jQuery('#filtroEscola').html(options.join('')).change();
    }

    function aplicarAcessoCOTEC(id_user) {
        jQuery('#filtroRede').parent().hide().html('<option value="COTEC">COTEC</option>');
        jQuery('#filtroEscola').html('<option value="">Carregando...</option>');

        var options = [];
        jQuery(arrEscolas).map(function(i,val){
            if(val['administrador'] == id_user){
                options.push('<option value="'+val['id']+'">'+val['rede']+' - '+val['nome']+'</option>');
            }
        });
        jQuery('#filtroEscola').html(options.join('')).change();
    }

    function aplicarAcessoGOIASTEC(id_user) {
        jQuery('#filtroRede').parent().hide().html('<option value="GOIASTEC">GOIASTEC</option>');
        jQuery('#filtroEscola').html('<option value="">Carregando...</option>');

        var options = [];
        jQuery(arrEscolas).map(function(i,val){
            if(val['administrador'] == id_user){
                options.push('<option value="'+val['id']+'">'+val['rede']+' - '+val['nome']+'</option>');
            }
        });
        jQuery('#filtroEscola').html(options.join('')).change();
    }

    function aplicarAcessoJUVENTUDE(id_user) {
        jQuery('#filtroRede').parent().hide().html('<option value="JUVENTUDE">JUVENTUDE</option>');
        jQuery('#filtroEscola').html('<option value="">Carregando...</option>');

        var options = [];
        jQuery(arrEscolas).map(function(i,val){
            if(val['administrador'] == id_user){
                options.push('<option value="'+val['id']+'">'+val['rede']+' - '+val['nome']+'</option>');
            }
        });
        jQuery('#filtroEscola').html(options.join('')).change();
    }

    function limparFiltros() {
        jQuery('#filtroNome').val('');
        jQuery('#filtroCpf').val('');
        jQuery('#filtroEmail').val('');
        jQuery('#filtroCurso').val('');        
        jQuery('#filtroRede').val('');
        jQuery('#filtroEscola').val('');
        jQuery('#filtroEdital').val('');
        jQuery('#filtroTuno').val('');
        jQuery('#filtroTipo').val('');
        jQuery('#filtroModalidade').val('');
        jQuery('#filtroPeriodoIni').val('');
        jQuery('#filtroPeriodoFim').val('');
        aplicarFiltros();
    }

    function aplicarFiltros() {
        var oTable = jQuery('#grid_inscritos').dataTable();
        oTable.fnReloadAjax('index.php?option=com_cettselecao&view=cettselecao');
        //gmInscritos.fnReloadAjax();
    }

    function exportarCsv() {
        var params = {};
        params.nome = jQuery('#filtroNome').val();
        params.cpf = jQuery('#filtroCpf').val();
        params.email = jQuery('#filtroEmail').val();
        params.curso = jQuery('#filtroCurso').val();        
        params.rede = jQuery('#filtroRede').val();
        params.escola = jQuery('#filtroEscola').val();
        params.edital = jQuery('#filtroEdital').val();
        params.turno = jQuery('#filtroTurno').val();
        params.modalidade = jQuery('#filtroModalidade').val();
        params.tipo = jQuery('#filtroTipo').val();
        params.pIni = jQuery('#filtroPeriodoIni').val();
        params.pFim = jQuery('#filtroPeriodoFim').val();
        
        /*jQuery.ajax({
            url: 'index.php?option=com_cettselecao&view=cettselecao',
            method: 'post',
            async: false,
            data: { task: "inscricoes.csv", format: "csv", params: params },
            success: function(result, status, xhr) { console.log(result); },
            error: function() { console.log('ajax call failed'); },
        });*/

        jQuery.ajax({
            url: 'index.php?option=com_cettselecao&view=cettselecao',
            method: 'post',
            async: false,
            data: { task: "inscricoes.csv", format: "csv", params: params },
            success: function(data) {
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);

                if(params.rede == '')
                    params.rede = 'todas-as-redes';

                link.download="download-inscricoes-"+params.rede+".csv";
                link.click();
            }
        });
    }

    function getData() {
        var params = {};
        params.nome = jQuery('#filtroNome').val();
        params.cpf = jQuery('#filtroCpf').val();
        params.email = jQuery('#filtroEmail').val();
        params.curso = jQuery('#filtroCurso').val();        
        params.rede = jQuery('#filtroRede').val();
        params.escola = jQuery('#filtroEscola').val();
        params.edital = jQuery('#filtroEdital').val();
        params.turno = jQuery('#filtroTurno').val();
        params.modalidade = jQuery('#filtroModalidade').val();
        params.tipo = jQuery('#filtroTipo').val();
        params.pIni = jQuery('#filtroPeriodoIni').val();
        params.pFim = jQuery('#filtroPeriodoFim').val();
        
        jQuery.ajax({
            url: 'index.php?option=com_cettselecao&view=cettselecao',
            method: 'post',
            async: false,
            data: { task: "inscricoes.filter", format: "json", params: params },
            success: function(result, status, xhr) { montarGridDados(result); },
            error: function() { console.log('ajax call failed'); },
        });
    }

    function montarGridDados(result) {
        data = [];
        if(result['data']){
            jQuery.map(result['data'], function(val,i){
                var btn = '<div class="dropdown show">' +
                                '<a class="btn btn-default dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                'Ações' +
                                '</a>' +

                                '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">' +
                                '<a class="dropdown-item" href="#">Detalhes</a>' +
                                '<a class="dropdown-item" href="#">Alterar</a>' +
                                '<a class="dropdown-item" href="#">Excluir</a>' +
                                '</div>' +
                            '</div>';
                data.push([val['id'],val['nome_completo'],val['escola']||'',val['curso']||'',val['data_hora'],'',btn]);
            });

            let table = jQuery('#grid_inscritos').DataTable();
            table.clear();
            table.rows.add( data ).draw();
        }
    }

    jQuery.fn.dataTableExt.oApi.fnReloadAjax = function (oSettings, sNewSource, fnCallback, bStandingRedraw) {
        // DataTables 1.10 compatibility - if 1.10 then `versionCheck` exists.
        // 1.10's API has ajax reloading built in, so we use those abilities
        // directly.
        if (jQuery.fn.dataTable.versionCheck) {
            var api = new jQuery.fn.dataTable.Api(oSettings);

            if (sNewSource) {
                api.ajax.url(sNewSource).load(fnCallback, !bStandingRedraw);
            } else {
                api.ajax.reload(fnCallback, !bStandingRedraw);
            }
            return;
        }

        if (sNewSource !== undefined && sNewSource !== null) {
            oSettings.sAjaxSource = sNewSource;
        }

        // Server-side processing should just call fnDraw
        if (oSettings.oFeatures.bServerSide) {
            this.fnDraw();
            return;
        }

        this.oApi._fnProcessingDisplay(oSettings, true);
        var that = this;
        var iStart = oSettings._iDisplayStart;
        var aData = [];

        this.oApi._fnServerParams(oSettings, aData);

        oSettings.fnServerData.call(oSettings.oInstance, oSettings.sAjaxSource, aData, function (json) {
            /* Clear the old information from the table */
            that.oApi._fnClearTable(oSettings);

            /* Got the data - add it to the table */
            var aData = (oSettings.sAjaxDataProp !== "") ?
                that.oApi._fnGetObjectDataFn(oSettings.sAjaxDataProp)(json) : json;

            for (var i = 0; i < aData.length; i++) {
                that.oApi._fnAddData(oSettings, aData[i]);
            }

            oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

            that.fnDraw();

            if (bStandingRedraw === true) {
                oSettings._iDisplayStart = iStart;
                that.oApi._fnCalculateEnd(oSettings);
                that.fnDraw(false);
            }

            that.oApi._fnProcessingDisplay(oSettings, false);

            /* Callback user function - for event handlers etc */
            if (typeof fnCallback == 'function' && fnCallback !== null) {
                fnCallback(oSettings);
            }
        }, oSettings);
    };

    function _gResponsive(data, id, cols) {
        //Exemplo de uso da Div: <div class="hidden-lg hidden-md" id="responsivel_nome_do_modulo"></div>
        //obs.: Adicionar logo após o table
        if (data.length > 0) {
            var itens = [];
            jQuery.map(data, function (val, i) {
                //Montar a div responsive
                itens.push(
                    '<div class="row">' +
                    '<div class="col-md-12">' +
                    '<div class="col-md-12 img-rounded" style="border: 1px solid #ccc; padding: 10px; background: #fcfcfc">'
                );

                jQuery.map(cols, function (vCol, vI) {
                    itens.push('<div><b>' + vCol + '</b></div><div>' + str_replace('pull-right', 'w-100', val['_aData'][vI]) + '</div><span style="font-size: 4px">&nbsp;</span>');
                });

                itens.push(
                    '</div>' +
                    '<div class="col-md-12">&nbsp;</div>' +
                    '</div>' +
                    '</div>'
                );
            });
            jQuery('#' + id).html(itens.join(''));
        } else {
            jQuery('#' + id).html('<div class="row"><div class="col-md-12"><div class="col-md-12">Nenhum registro encontrado.</div></div></div>');
        }
    }

    function _montarBotoesAcao(data) {
        if (data.length > 0) {
            var itens = [];
            jQuery.map(data, function (val, i) {
                console.log('.btn_acoes_' + val['_aData'][0]);
                jQuery('.btn_acoes_' + val[0]).html('testes');
            });
        }
    }

    function str_replace (search, replace, subject, count) {
    // From: http://phpjs.org/functions
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order
    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'
    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,
        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = Object.prototype.toString.call(r) === '[object Array]',
        sa = Object.prototype.toString.call(s) === '[object Array]';
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
        continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
        temp = s[i] + '';
        repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
        s[i] = (temp).split(f[j]).join(repl);
        if (count && s[i] !== temp) {
            this.window[count] += (temp.length - s[i].length) / f[j].length;
        }
        }
    }
    return sa ? s : s[0];
    }


</script>