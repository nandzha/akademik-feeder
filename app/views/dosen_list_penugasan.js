define([
	"apps",
	"views/modules/dosen_search",
	"views/forms/frm_penugasan_dosen",
	"views/modules/window",
	"views/modules/dataProcessing"
],function(apps, search, form, ui_window, handleProcessing){

var grd_penugasan = {
	view:"datatable",
	id:"grd_penugasan",
	columns:[
		{ id: "trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id: "edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
		{ id: "nm_thn_ajaran", header: "tahun", width: 100},
        { id: "nm_prodi", header: "Prodi", width: 200},
        { id: "no_srt_tgs", header: "no surat tugas", width: 200},
        { id: "tgl_srt_tgs", header: "tgl surat tugas", width: 100},
        { id: "tmt_srt_tgs", header: "tgl Mulai", width: 100}
	],
	select:"row",
	dataFeed : "./penugasan/data",
	onClick:{
		edit:function(e,id,node){
			webix.$$("win_penugasan").show();
		},
		trash:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_penugasan").remove(id);
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
			var dosen = $$("listdosen").getSelectedId();
			if (dosen) {
				webix.$$("win_penugasan").show();
				$$('frm_penugasan_dosen').clear();
			}else{
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}
		}}
	]
};

var btn_submit_penugasan ={
    height:50,
    padding:10,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success ", label: "Submit", width: 120, click:function(obj){
			var form      = $$('frm_penugasan_dosen');
			var dosen     = $$("listdosen").getSelectedId();
			var values    = form.getValues();
			values.id_ptk = dosen.id;

			if(form.isDirty()){
				if(!form.validate())
					return false;

				form.setValues( values );
				form.save();
                this.getTopParentView().hide();
			};
        }}
    ]
};

var win_penugasan = {
	rows:[form,btn_submit_penugasan]
}

var ui_scheme = {
	type: "line",
	id:"ui_penugasan",
	rows:[
	{
		margin:10,
		type: "material",
		cols:[
			search,
			{
				gravity: 2.2,
				rows:[
					grd_penugasan,
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
        apps.setUiWindow = ui_window.ui("win_penugasan", "FORM PENUGASAN DOSEN", win_penugasan);
    	apps.webix();
    },
    onInit: function(){
		$$("grd_penugasan").bind($$("listdosen"), "id_ptk");
		$$("frm_penugasan_dosen").bind($$("grd_penugasan"));

		var dp = new webix.DataProcessor({
            updateFromResponse:true, 
            autoupdate:true,
            master: $$("grd_penugasan"),
            url: "connector->./penugasan/data",
            on: handleProcessing
        });
	}
};

});