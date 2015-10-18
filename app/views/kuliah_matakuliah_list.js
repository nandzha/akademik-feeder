define([
	"apps",
	"views/forms/frm_matakuliah",
	"views/modules/dataProcessing"
],function(apps, forms, handleProcessing){

function checkbox(obj, common, value){
	if (value == "1")
		return '<span class="webix_icon fa-check"></span>';
	else
		return '<span class="webix_icon fa-times"></span>';
}

var grd_matakuliah = {
	view:"datatable",
	id:"grd_matakuliah",
	columns:[
		{ id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id:"edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
		{ id:"kode_mk", header: "kode_mk", width: 100},
        { id:"nm_mk", header: "nm_mk", width: 250},
        { id:"sks_mk", header: "sks_mk", width: 80},
        { id:"nm_prodi", header: "Prodi", width: 250},
        { id:"nm_jns_mk", header: "nm_jns_mk", width: 100},
        { id:"nm_kel_mk", header: "nm_kel_mk", width: 100},
        { id:"a_bahan_ajar", header: "a_bahan_ajar", width: 100, template: checkbox },
        { id:"a_sap", header: "a_sap", width: 100, template: checkbox},
        { id:"a_silabus", header: "a_silabus", width: 100, template: checkbox},
	],
	select:"row",
	url: "kuliah/mklst",
	onClick:{
		edit:function(e,id,node){
			$$("tabbar").setValue("frm_mk");
		},
		trash:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_matakuliah").remove(id);
					}
				}
			});
		},
	}
};

var btn_save ={
	paddingX:5,
	paddingY:5,
	height:45,
	cols:[
		{},
		{ view: "button", css:"button_raised", label: "save", width: 130, click:function(obj){
			var form   = $$('frm_matakuliah');
			var values = form.getValues();

			if (!values.webix_operation)
				values.webix_operation = 'update';

			if(form.isDirty()){
				if(!form.validate())
					return false;

				form.setValues(values);
				webix.ajax().post("./kuliah/mklst", values);
			};
		}}
	]
};

var ui_scheme = {
	type: "material",
	rows:[
		{
			css:"bg_clean",
	    	cols:[
		    	{
		    		view:"segmented",
		    		id:'tabbar',
		    		value: 'lst_mk',
			    	multiview:true,
			    	optionWidth:141,
			    	padding: 5,
			    	options: [
				    	{ value: 'List Matakuliah', id: 'lst_mk'},
				    	{ value: 'Add New', id: 'frm_mk'}
			    	],
			    	on:{
			    		onItemClick : function(id, e){
						    if (e.target.innerText == 'Add New'){
						    	$$("frm_matakuliah").clear();
						    	$$("frm_matakuliah").setValues({webix_operation:'insert'});
						    }
						}
			    	}
		    	}
	    	]
		},
		{
			id:"multiview_mk",
	    	cells:[
		    	{
		    		id:"lst_mk",
		    		rows: [grd_matakuliah]
		    	},
		    	{
		    		id:"frm_mk",
		    		rows:[forms, btn_save]
		    	}
	    	]

		}
	]
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit:function(){
    	$$("frm_matakuliah").bind($$("grd_matakuliah"));

    	var dp = new webix.DataProcessor({
		    updateFromResponse:true,
		    autoupdate:true,
		    master: $$("grd_matakuliah"),
		    url: "connector->./kuliah/mklst",
		    on: handleProcessing
		});

    }
};

});
