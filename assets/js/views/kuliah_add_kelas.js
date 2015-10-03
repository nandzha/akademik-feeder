define([
	"apps",
	"views/modules/semester_search",
    "views/modules/window",
    "views/forms/frm_add_kelas",
    "views/modules/dataProcessing"
], function(apps,search, ui_window, form, handleProcessing){

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
    }
};

var btn_add_kelas ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
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
            var values        = $$("frm_add_kelas").getValues();
            var items         = values.nm_kls.split(",");
            var list_semester = $$("list_semester").getSelectedId();


            if (items){
                if (Array.isArray(items)) {
                    for (var i=0; i< items.length; i++){
                        values.id     = values.id_mk+"-"+i;
                        values.nm_kls = items[i];
                        values.id_smt = list_semester.id;
                        $$("grd_kelas").add(values);
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
        apps.setUiWindow = ui_window.ui("win_kelas", "KELAS KULIAH", {rows:[form, btn_submit_add_kelas]});
    	apps.webix();
    },
    onInit: function(){
        $$("grd_kelas").bind($$("list_semester"), "id_smt");

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
