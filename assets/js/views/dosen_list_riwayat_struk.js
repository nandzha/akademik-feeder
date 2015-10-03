define([
	"apps",
	"views/modules/dosen_search",
	"views/forms/frm_dosen_riwstruk",
	"views/modules/window",
	"views/modules/dataProcessing"
],function(apps, search, form, ui_window, handleProcessing){

var grd_struktural = {
	view:"datatable",
	id:"grd_struktural",
	columns:[
		{ id: "trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id: "edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
		{ id:"nm_jabstruk", header: "nm_jabfung", width:100},
        { id:"tgl_sk_jabatan", header: "tgl_sk_jabatan", width:100},
        { id:"sk_jabatan", header: "sk_jabatan", width:100},
        { id:"tmt_jabatan",  header: "tmt_jabatan", width:100}
	],
	select:"row",
	datafetch:30,
	dataFeed : "./riwstruk/data",
	onClick:{
		edit:function(e,id,node){
			webix.$$("win_riwstruk").show();
		},
		trash:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_struktural").remove(id);
					}
				}
			});
		}
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
				webix.$$("win_riwstruk").show();
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
			var form      = $$('frm_dosen_riwstruk');
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

var win_riwstruk = {
	rows:[form,btn_submit]
}

var ui_scheme = {
	type: "line",
	id:"ui_struktural",
	rows:[
	{
		margin:10,
		type: "material",
		cols:[
			search,
			{
				gravity: 2.2,
				rows:[
					grd_struktural,
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
    	apps.setUiWindow = ui_window.ui("win_riwstruk", "FORM RIWAYAT JABATAN STRUKTURAL", win_riwstruk);
    	apps.webix();
    },
    onInit: function(){
		$$("grd_struktural").bind($$("listdosen"), "id_ptk");
		$$("frm_dosen_riwstruk").bind($$("grd_struktural"));
		var dp = new webix.DataProcessor({
            updateFromResponse:true, 
            autoupdate:true,
            master: $$("grd_struktural"),
            url: "connector->./riwstruk/data",
            on: handleProcessing
        });
	}
};

});