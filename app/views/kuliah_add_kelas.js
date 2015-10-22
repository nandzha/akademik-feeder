define([
	"apps",
	"views/modules/semester_search",
    "views/modules/window",
    "views/modules/grid_mata_kuliah_semester",
    "views/modules/dataProcessing"
], function(apps,search, ui_window,grid_mata_kuliah_semester, handleProcessing){

var grd_kelas = {
	view:"datatable",
	id:"grd_kelas",
	columns:[
        { id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
        { id:"id_smt", width:60, header:"smt"},
        { id:"nm_kls", width:40,header:[{
            content:"serverFilter", placeholder:"Kls",
        }]},
        { id:"kode_mk", width:80,header:[{
            content:"serverFilter", placeholder:"Kode MK",
        }]},
        { id:"nm_mk", width:250,header:[{
            content:"serverFilter", placeholder:"Nama MK",
        }]},
        { id:"sks_mk", width:50,header:[{
            content:"serverFilter", placeholder:"sks",
        }]},
	],
	select:"row", editable:true, editaction:"dblclick",
	dataFeed : "./kelaslst",
    onClick:{
        trash:function(e,id,node){
            webix.confirm({
                text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
                callback:function(res){
                    if(res){
                        $$("grd_kelas").remove(id);
                    }
                }
            });
        },
    },
    on:{
        onAfterLoad:function(){
            $$("lbl_kls").define("template", "Jml Kelas: "+this.count() );
            $$('lbl_kls').refresh();
        }
    }
};

var btn_add_kelas ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {id:"lbl_kls", view :"label", width:200 },
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "ADD KELAS", width: 140, click:function(obj){
            var id = $$("list_semester").getSelectedId();
            if (id) {
                webix.$$("win_kelas").show();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_submit_add_kelas ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){
            /*var values        = $$("frm_add_kelas").getValues();
            var nm_kls         = values.nm_kls.split(",");
            var list_semester = $$("list_semester").getSelectedId();


            if (nm_kls){
                if (Array.isArray(nm_kls)) {
                    for (var i=0; i< nm_kls.length; i++){
                        values.id     = values.id_mk+"-"+i;
                        values.nm_kls = nm_kls[i];
                        values.id_smt = list_semester.id;
                        $$("grd_kelas").add(values);
                    }
                }
                this.getTopParentView().hide();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }*/

            var items    = $$("grid_mata_kuliah_semester").getSelectedItem();
            var list_smt = $$("list_semester").getSelectedId();

            if (items){
                if (Array.isArray(items)) {
                    var value = {};
                    for (var i=0; i< items.length; i++){
                        for (var j=1; j<= items[i].jml_kls; j++){
                            value.id               = items[i].id+"-"+j;
                            value.id_sms           = items[i].id_sms;
                            value.id_smt           = list_smt.id;
                            value.id_mk            = items[i].id_mk;
                            value.nm_kls           = "0"+j;
                            value.sks_mk           = items[i].sks_mk;
                            value.sks_tm           = items[i].sks_tm;
                            value.sks_prak         = items[i].sks_prak;
                            value.sks_prak_lap     = items[i].sks_prak_lap;
                            value.sks_sim          = items[i].sks_sim;
                            value.tgl_mulai_koas   = '2015-09-01'
                            value.tgl_selesai_koas = '2020-09-01'

                            $$("grd_kelas").add(value);
                        }
                    }
                }else{
                    var value = {};

                    for (var i=1; i<= items.jml_kls; i++){
                        value.id               = items.id+"-"+i;
                        value.id_sms           = items.id_sms;
                        value.id_smt           = list_smt.id;
                        value.id_mk            = items.id_mk;
                        value.nm_kls           = "0"+i;
                        value.sks_mk           = items.sks_mk;
                        value.sks_tm           = items.sks_tm;
                        value.sks_prak         = items.sks_prak;
                        value.sks_prak_lap     = items.sks_prak_lap;
                        value.sks_sim          = items.sks_sim;
                        value.tgl_mulai_koas   = '2015-09-01'
                        value.tgl_selesai_koas = '2020-09-01'


                        $$("grd_kelas").add(value);
                    }
                }

                this.getTopParentView().hide();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var ui_scheme = {
	type: "line",
	id:"ui_kursem",
    rows:[
    {
    	margin:10,
    	type: "material",
            cols:[
            search,
            {
                gravity: 2.2,
                rows:[
                    grd_kelas,
                    btn_add_kelas
                ]
            }
    	]
    }
    ],
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
        apps.setUiWindow = ui_window.ui("win_kelas", "MATA KULIAH KURIKULUM SEMESTER", {rows:[grid_mata_kuliah_semester, btn_submit_add_kelas]});
    	apps.webix();
    },
    onInit: function(){
        $$("grd_kelas").bind($$("list_semester"), "id_smt");
        $$("grid_mata_kuliah_semester").bind( $$("list_semester"), "id_smt_berlaku" );
        /*$$("grid_mata_kuliah_semester").bind( $$("list_semester"), "$data", function(data, source){
            if (data){
                this.load("./kurikulum/data?filter[id_sms_berlaku]="+data.id_sms);
            }
        });*/

        var dp = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_kelas"),
            url: "connector->./kelaslst",
            on: handleProcessing
        });
	}
};
});
