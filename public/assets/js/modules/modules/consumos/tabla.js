// Definimos la variable tabs la cual contendrá todo nuestro modulo.
var tablaConsumos = (function(){
    
    // Objeto al cual establecemos valores que vamos a usar mas adelante en este ambito.
    var st = {
        jqGrid: "#consumosGrid",
        jqPager: "#consumosPager",
        jqTogle: "#jqgrid-column-togleConsumos",
        noRecords: ".NoRecordsConsumos",
        optionsModal: "#optionsModal",
        searchBtn: "#searchBtn",
        clearBtn: "#clearBtn",
        segmento2: "consumos",
        
        //campos del formulario de busqueda
        iFechaDesde: "#fecha_desde",
        iFechaHasta: "#fecha_hasta",
        iColaborador: "#colaborador",
        iEstado: "#estado",
        iReferencia: "#referencia",
        iNumero: "#numero",
        iCentro: "#centro",
        inputsSearch: "#fecha_desde, #fecha_hasta, #colaborador, #estado, #referencia, #numero, #centro"
    };
   
    // Objeto vacío que guardará elementos que se manejan por HTML.
    var dom = {}

    // Función que llenará al objeto dom con los objetos HTML a través de jQuery ($).
    var catchDom = function(){
        dom.jqGrid = $(st.jqGrid);
        dom.optionsModal = $(st.optionsModal);
        dom.searchBtn = $(st.searchBtn);
        dom.clearBtn = $(st.clearBtn);
        dom.noRecords = $(st.noRecords);
        
        //campos del formulario de busqueda
        dom.iFechaDesde = $(st.iFechaDesde);
        dom.iFechaHasta = $(st.iFechaHasta);
        dom.iColaborador = $(st.iColaborador);
        dom.iEstado = $(st.iEstado);
        dom.iReferencia = $(st.iReferencia);
        dom.iNumero = $(st.iNumero);
        dom.iCentro = $(st.iCentro);
        dom.inputsSearch = $(st.inputsSearch);
    };

    // Función donde establecemos los eventos que tendrán cada elemento.
    var suscribeEvents = function(){
        dom.jqGrid.on("click", ".viewOptions", events.eMostrarModal);
        dom.searchBtn.bind('click', events.eSearchBtnHlr);
        dom.clearBtn.on('click', events.eClearBtn);
    };

    /* Objeto que guarda métodos que se van a usar en cada evento definido 
      en la función suscribeEvents. */
    var events = {
        eMostrarModal: function(e)
        {
            var self = $(this);
            
            e.preventDefault();
            e.returnValue=false;
            e.stopPropagation();
            
            var nombre = '';
            var uuid = self.attr("data-uuid");
            var rowINFO = dom.jqGrid.getRowData(uuid);
	    var options = rowINFO["options"];
            

            nombre = rowINFO["No. de consumo"];
	    //Init boton de opciones
            dom.optionsModal.find('.modal-title').empty().append('Opciones: '+ nombre);
            dom.optionsModal.find('.modal-body').empty().append(options);
            dom.optionsModal.find('.modal-footer').empty();
            dom.optionsModal.modal('show');
        },
        eSearchBtnHlr: function (e) {

            e.preventDefault();
            //actualizar chosens...
            //console.log(listarOrdenes.dom.chosens);
            dom.searchBtn.unbind('click', events.eSearchBtnHlr);

            var fecha_desde = dom.iFechaDesde.val();
            var fecha_hasta = dom.iFechaHasta.val();
            var colaborador = dom.iColaborador.val();
            var estado = dom.iEstado.val();
            var referencia = dom.iReferencia.val();
            var numero = dom.iNumero.val();
            var centro = dom.iCentro.val();

            if(fecha_desde != "" || fecha_hasta != "" || colaborador != ""  || estado != "" || referencia != "" || numero != "" || centro != "")
            {
                dom.jqGrid.setGridParam({
                    url: phost() + st.segmento2 +'/ajax-listar',
                    datatype: "json",
                    postData: {
                        fecha_desde: fecha_desde,
                        fecha_hasta: fecha_hasta,
                        colaborador: colaborador,
                        estado: estado,
                        referencia: referencia,
                        numero: numero,
                        centro: centro,
                        erptkn: tkn
                    }
                }).trigger('reloadGrid');

                dom.searchBtn.bind('click', events.eSearchBtnHlr);
            }else{
                dom.searchBtn.bind('click', events.eSearchBtnHlr);
            }
        },
        eClearBtn: function(e){
            e.preventDefault();
            dom.jqGrid.setGridParam({
                url: phost() + st.segmento2 +'/ajax-listar',
                datatype: "json",
                postData: {
                    fecha_desde: '',
                    fecha_hasta: '',
                    colaborador: '',
                    estado: '',
                    referencia: '',
                    numero: '',
                    centro: '',
                    erptkn: tkn
                }
            }).trigger('reloadGrid');

            //Reset Fields
            dom.inputsSearch.val('');
            
            //Reset Chosens
            dom.inputsSearch.trigger("chosen:updated");
	}
    };
    
    var muestra_tabla = function(){
        dom.jqGrid.jqGrid({
            url: phost() + st.segmento2 + '/ajax-listar',
            datatype: "json",
            colNames:[
                'No. de consumo',
                'Fecha',
                'Colaborador',
                'Referencia',
                'Centro contable',
                'Estado',
                'Acci&oacute;n',
                ''
            ],
            colModel:[
                {name:'No. de consumo', index:'numero', width:60},
                {name:'Fecha', index:'fecha', width:60,  sortable:false},
                {name:'Colaborador', index:'colaborador', width:60,  sortable:false},
                {name:'Referencia', index:'referencia', width:80,  sortable:false},
                {name:'Centro contable', index:'centro_contable', width:80,  sortable:false},
                {name:'Estado', index:'estado', width:60,  sortable:false},
                {name:'link', index:'link', width:60, align:"center", sortable:false, resizable:false, hidedlg:true},
                {name:'options', index:'options', hidedlg:true, hidden: true},
            ],
            mtype: "POST",
            postData: {
                erptkn: tkn,
            },
            height: "auto",
            autowidth: true,
            rowList: [10,20,50,100],
            rowNum: 10,
            page: 1,
            pager: st.jqPager,
            loadtext: '<p>Cargando...',
            pgtext : "Página {0} de {1}",
            hoverrows: false,
            viewrecords: true,
            refresh: true,
            gridview: true,
            multiselect: multiselect,
            sortname: 'numero',
            sortorder: "DESC",
            beforeProcessing: function(data, status, xhr){
                //Check Session
                if( $.isEmptyObject(data.session) == false){
                    window.location = phost() + "login?expired";
                }
            },
            loadBeforeSend: function () {//propiedadesGrid_cb
                $(this).closest("div.ui-jqgrid-view").find("table.ui-jqgrid-htable>thead>tr>th").css("text-align", "left");
                $(this).closest("div.ui-jqgrid-view").find(st.jqGrid + "_cb, #jqgh_" + st.jqGrid + "_link").css("text-align", "center");
            }, 
            beforeRequest: function(data, status, xhr){},
            loadComplete: function(data){

                //check if isset data
                if( data['total'] == 0 ){
                    $('#gbox_' + st.jqGrid).hide();
                    dom.noRecords.empty().append('No se encontraron Consumos.').css({"color":"#868686","padding":"30px 0 0"}).show();
                }
                else{
                    dom.noRecords.hide();
                    $('#gbox_' + st.jqGrid).show();
                }
                
                if(multiselect == true){
                    //---------
                    // Cargar plugin jquery Sticky Objects
                    //----------
                    //add class to headers
                    dom.jqGrid.closest("div.ui-jqgrid-view").find("div.ui-jqgrid-hdiv").attr("id","gridHeader");

                    //floating headers
                    $('#gridHeader').sticky({
                        getWidthFrom: '.ui-jqgrid-view', 
                        className:'jqgridHeader'
                    });

                    //Arreglar tamaño de TD de los checkboxes
                    $(st.jqGrid + "_cb").css("width","50px");
                    $(st.jqGrid + " tbody tr").children().first("td").css("width","50px");
                }

                


            },
            onSelectRow: function(id){
                $(this).find('tr#'+ id).removeClass('ui-state-highlight');
            },
        });
        
        dom.jqGrid.jqGrid('columnToggle');

        //-------------------------
        // Redimensioanr Grid al cambiar tamaño de la ventanas.
        //-------------------------
        $(window).resizeEnd(function() {
            $(".ui-jqgrid").each(function(){
                var w = parseInt( $(this).parent().width()) - 6;
                var tmpId = $(this).attr("id");
                var gId = tmpId.replace("gbox_","");
                $("#"+gId).setGridWidth(w);
            });
        });
    }
 
    // Función que inicializará los funciones decritas anteriormente.
    var initialize = function(){
        catchDom();
        suscribeEvents();
        muestra_tabla();
    };

    /* Retorna un objeto literal con el método init haciendo referencia a la 
       función initialize. */
    return{
        init:initialize
    }
})();

//verificar si la url actual es contactos
//de lo contrario no mostrar multiselect del jqgrid
var multiselect = window.location.pathname.match(/consumos/g) ? true : false;


// Ejecutando el método "init" del módulo tabs.
tablaConsumos.init();

 

