define([
	"apps",
	"views/modules/kurikulum_search",
    "views/modules/window",
    "views/forms/frm_kursem",
    "views/modules/grid_mata_kuliah",
    "views/modules/dataProcessing"
], function(apps,search, ui_window, form, grid_mata_kuliah, handleProcessing){

var grd_kursem = {
	view:"datatable",
	id:"grd_kursem",
	columns:[
        { id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
        { id:"smt",width:80, editor:"select", options:[1,2,3,4,5,6,7,8], header:[{
            content:"serverFilter", placeholder:"SMT"
        }]},
        { id:"a_wajib",  checkValue:'1', uncheckValue:'0', template:"{common.checkbox()}"},
        { id:"kode_mk", width:100, header:[{
            content:"serverFilter", placeholder:"Kode MK",
        }]},
        { id:"nm_mk", width:250, header:[{
            content:"serverFilter", placeholder:"Nama MK",
        }]},
        { id:"sks_mk", header: "sks_mk", width:80, editor:"text"},
        { id:"sks_tm", header: "sks_tm", width:80, editor:"text"},
        { id:"sks_prak", header: "sks_prak", width:80, editor:"text"},
        { id:"sks_prak_lap", header: "sks_prak_lap", width:80, editor:"text"},
        { id:"sks_sim", header: "sks_sim", width:80, editor:"text"},
	],
	select:"row", editable:true,
	dataFeed : "./kurikulum/data",
    onClick:{
        trash:function(e,id,node){
            webix.confirm({
                text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
                callback:function(res){
                    if(res){
                        $$("grd_kursem").remove(id);
                    }
                }
            });
        },
    },
    on:{
        onAfterLoad:function(){
            $$("lbl_mk").define("template", "Jml Mata Kuliah: "+this.count() );
            $$('lbl_mk').refresh();
        }
    }
};

var btn_add_list ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "New", width: 100, click:function(obj){
            webix.$$("win_list").show();
        }}
    ]
};

var btn_add_detail ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {id:"lbl_mk", view :"label", width:200 },
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "ADD MATAKULIAH", width: 180, click:function(obj){
            var id = $$("list_kurikulum").getSelectedId();
            if (id) {
                webix.$$("win_detail").show();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_submit_list ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){
            var form = $$('frm_kursem');
            // var values    = form.getValues();

            if(form.isDirty()){
                if(!form.validate())
                    return false;

                form.save();
                this.getTopParentView().hide(); //hiteme window
            };
        }}
    ]
};

var btn_submit_mk_smt ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){
            var items          = $$("grd_mata_kuliah").getSelectedItem();
            var list_kurikulum = $$("list_kurikulum").getSelectedId();

            if (items){
                if (Array.isArray(items)) {
                    for (var i=0; i< items.length; i++){
                        items[i].id_kurikulum_sp = list_kurikulum.id;
                        items[i].id_mk = items[i].id;
                        $$("grd_kursem").add(items[i]);
                    }
                }else{
                    items.id_mk = items.id;
                    items.id_kurikulum_sp = list_kurikulum.id;
                    $$("grd_kursem").add(items);
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
            {
        		rows:[
                    search,
                    btn_add_list
                ]
            },
            {
                gravity: 2.2,
                rows:[
                    grd_kursem,
                    btn_add_detail
                ]
            }
    	]
    }
    ],
};

var win_list = ui_window.ui("win_list", "KURIKULUM SEMESTER", {rows:[form, btn_submit_list]});
var win_detail = ui_window.ui("win_detail", "MATAKULIAH KURIKULUM", {rows:[grid_mata_kuliah, btn_submit_mk_smt]});

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix({win_list, win_detail});
    },
    onInit: function(){
        $$("grd_kursem").bind($$("list_kurikulum"), "id_kurikulum_sp");
        $$("grd_mata_kuliah").bind( $$("list_kurikulum"), "$data", function(data, source){
            if (data){
                this.load("./mklst?filter[id_sms]="+data.id_sms);
            }
        });
		$$("frm_kursem").bind($$("list_kurikulum"));

        var dp = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("list_kurikulum"),
            url: "connector->./kurikulumlst",
            on: handleProcessing
        });

        var dp1 = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_kursem"),
            url: "connector->./kurikulum/data",
            on: handleProcessing
        });
	}
};
});
