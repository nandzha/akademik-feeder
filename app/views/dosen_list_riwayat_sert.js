define([
	"apps",
	"views/modules/dosen_search",
	"views/forms/frm_dosen_riwsert",
	"views/modules/window",
	"views/modules/dataProcessing"
],function(apps, search, form, ui_window, handleProcessing){

var grd_riwsert = {
	view:"datatable",
	id:"grd_riwsert",
	columns:[
		{ id: "trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id: "edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
		{ id:"nm_jns_sert", header: "nm_jns_sert", width: 100},
        { id:"no_peserta", header: "no_peserta", width: 100},
        { id:"no_sk_sert", header: "no_sk_sert", width: 100},
        { id:"thn_sert", header: "thn_sert", width: 100},
        { id:"bid_studi", header: "bid_studi", width: 100}
	],
	select:"row",
	datafetch:30,
	dataFeed : "./riwsert/data",
	onClick:{
		edit:function(e,id,node){
			webix.$$("win_riwsert").show();
		},
		trash:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_riwsert").remove(id);
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
				webix.$$("win_riwsert").show();
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
			var form      = $$('frm_dosen_riwsert');
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

var win_riwsert = {
	rows:[form,btn_submit]
}

var ui_scheme = {
	type: "line",
	id:"ui_riwsert",
	rows:[
	{
		margin:10,
		type: "material",
		cols:[
			search,
			{
				gravity: 2.2,
				rows:[
					grd_riwsert,
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
    	apps.setUiWindow = ui_window.ui("win_riwsert", "FORM RIWAYAT SERTIFIKASI", win_riwsert);
    	apps.webix();
    },
    onInit: function(){
		$$("grd_riwsert").bind($$("listdosen"), "id_ptk");
		$$("frm_dosen_riwsert").bind($$("grd_riwsert"));
		var dp = new webix.DataProcessor({
            updateFromResponse:true, 
            autoupdate:true,
            master: $$("grd_riwsert"),
            url: "connector->./riwsert/data",
            on: handleProcessing
        });
	}
};

});