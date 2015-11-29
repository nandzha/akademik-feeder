define([
	"apps",
	"views/modules/mhs_search",
    "views/modules/window",
    "views/modules/grid_mk_kelas",
    "views/modules/dataProcessing",
    "views/modules/dataProgressBar",
], function(apps,search,ui_window, grid_mk_kelas, handleProcessing, notifidata){

var grd_krs = {
	view:"datatable",
	id:"grd_krs",
    footer:true,
	columns:[
		{ id: "trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon fa-trash-o text_danger'></span>",},
		{ id: "id_smt", header:"smt", width:80,  },
		{ id: "kode_mk", header:"kode mk", width:100},
		{ id: "nm_mk", header:"nama mk", width:300, footer:{text:"Total SKS:", colspan:2} },
		{ id: "nm_kls", header:"kls", width:80, editor:"text"},
		{ id: "sks_mk", header:"sks", width:80, footer:{content:"summColumn"}}
	],
	onClick:{
		webix_icon:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_krs").remove(id);
					}
				}
			});
		}
	},
	select:"row",
	dataFeed : "./krslst/data",
    ready: notifidata.emptydata,
    on: {
        onAfterAdd : function(id, index){
            this.addRowCss(id,"bg_info");
            $$("grd_mk_kls").addRowCss(id,"bg_info");
            this.sort("#id#", "desc", "int");
        },
        onBeforeLoad:function(){
                this.showOverlay("Loading...");
        },
        onAfterLoad:function(){
            this.hideOverlay();
            if (!this.count()){
                webix.extend(this, webix.OverlayBox);
                this.showOverlay("<div style='margin:75px; font-size:20px;'>There is no data</div>");
            }
            $$("lbl_krs").define("template", "Jml Matakuliah yang diambil: "+ this.count() );
            $$("lbl_krs").refresh();
        }
    }
};

var btn_add ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {id:"lbl_krs", view :"label", width:250 },
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Add Matakuliah", width: 180, click:function(obj){
            var id = $$("listmsmhs").getSelectedId();
            var mhs = $$("listmsmhs").getSelectedItem();

            if (id) {
                webix.$$("win_krs_mhs").show();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }},
        { view: "button", type: "iconButton", icon: "print", label: "KRS", width: 100, click:function(){
            var listmsmhs = $$("listmsmhs").getSelectedId();
            var values = {id_reg_pd:listmsmhs.id};

            if (listmsmhs) {
                webix.send("/twig_template/preview/krs",values, 'POST', '_balnk');
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_add_krs ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){
            var items     = $$("grd_mk_kls").getSelectedItem();
            var listmsmhs = $$("listmsmhs").getSelectedId();

            if (items){
                if (Array.isArray(items)) {
                    for (var i=0; i< items.length; i++){
                        items[i].id_reg_pd = listmsmhs.id;
                        $$("grd_krs").add(items[i]);
                    }
                }else{
                    items.id_reg_pd = listmsmhs.id;
                    $$("grd_krs").add(items);
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
	id:"uimsmhs",
    rows:[
    {
    	margin:10,
    	type: "material",
    	cols:[
    		search,
    		{
                gravity: 2.2,
                rows:[
    				grd_krs,
                    btn_add
                ]
            }
    	]
    }
    ],
};

var win_krs_mhs = {
    rows:[grid_mk_kelas, btn_add_krs]
}

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
        apps.setUiWindow = ui_window.ui("win_krs_mhs", "DAFTAR MATAKULIAH", win_krs_mhs);
    	apps.webix();
    },
    onInit: function(){
		$$("grd_krs").bind($$("listmsmhs"), "id_reg_pd");
        $$("grd_mk_kls").bind( $$("listmsmhs"), "$data", function(data, source){
            if (data){
                this.load("./mksmt/data?filter[id_sms]="+data.id_sms);
            }
        });

        var dp = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_krs"),
            url: "connector->./krslst/data",
            on: handleProcessing
        });

	}
};
});
