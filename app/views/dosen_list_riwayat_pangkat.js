define([
	"apps",
	"views/modules/dosen_search",
	"views/forms/frm_dosen_riwpangkat",
	"views/modules/window",
	"views/modules/dataProcessing"
],function(apps, search, form, ui_window, handleProcessing){

var grd_riwpang = {
	view:"datatable",
	id:"grd_riwpang",
	columns:[
		{ id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id:"edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
		{ id:"nm_pangkat", header:"nm_pangkat", width:100 },
        { id:"kode_gol", header:"kode_gol", width:100 },
        { id:"masa_kerja_bln", header:"masa_kerja_bln", width:100 },
        { id:"masa_kerja_thn", header:"masa_kerja_thn", width:100 },
        { id:"sk_pangkat", header:"sk_pangkat", width:100 },
        { id:"tgl_sk_pangkat", header:"tgl_sk_pangkat", width:100 },
        { id:"tmt_sk_pangkat", header:"tmt_sk_pangkat", width:100 }
	],
	select:"row",
	datafetch:30,
	dataFeed : "./riwpang/data",
	onClick:{
		edit:function(e,id,node){
			webix.$$("win_riwpangkat").show();
		},
		trash:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_riwpang").remove(id);
					}
				}
			});
		},
	}
};

var btn_add ={
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{},
		{ view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Add New", width: 130, click:function(obj){
			var id = $$("listdosen").getSelectedId();
			if (id) {
				webix.$$("win_riwpangkat").show();
			}else{
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}
		}}
	]
};

var btn_submit ={
    height:50,
    padding:10,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success ", label: "Submit", width: 120, click:function(obj){
			var form      = $$('frm_dosen_riwpangkat');
			var dosen     = $$("listdosen").getSelectedId();
			var values    = form.getValues();
			values.id_ptk = dosen.id;

			if(form.isDirty()){
				if(!form.validate())
					return false;

				form.setValues( values );
				form.save(); 
                this.getTopParentView().hide(); //hiteme window
			};
        }}
    ]
};

var win_riwpangkat = {
	rows:[form,btn_submit]
}

var ui_scheme = {
	type: "line",
	id:"ui_pangkat",
	rows:[
	{
		margin:10,
		type: "material",
		cols:[
			search,
			{
				gravity: 2.2,
				rows:[
					grd_riwpang,
					btn_add
				]
			}
		]
	}
	]
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.setUiWindow = ui_window.ui("win_riwpangkat", "FORM RIWAYAT KEPANGKATAN", win_riwpangkat);
    	apps.webix();
    },
    onInit: function(){
		$$("grd_riwpang").bind($$("listdosen"), "id_ptk");
		$$("frm_dosen_riwpangkat").bind($$("grd_riwpang"));
		 var dp = new webix.DataProcessor({
            updateFromResponse:true, 
            autoupdate:true,
            master: $$("grd_riwpang"),
            url: "connector->./riwpang/data",
            on: handleProcessing
        });
	}
};

});