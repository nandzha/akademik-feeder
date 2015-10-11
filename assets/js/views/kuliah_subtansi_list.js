define([
	"apps",
	"views/forms/frm_subtansi_mk",
	"views/modules/dataProcessing"
],function(apps, forms, handleProcessing){

var grd_subtansi = {
	view:"datatable",
	id:"grd_subtansi",
	columns:[
		{ id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id:"edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
		{ id:"nm_subst", header: "nm_subst", width: 100},
		{ id:"nm_jns_subst", header: "nm_jns_subst", width: 100},
		{ id:"nm_prodi", header: "nm_lemb", width: 100},
		{ id:"sks_mk", header: "sks_mk", width: 100},
		{ id:"sks_tm", header: "sks_tm", width:100},
		{ id:"sks_prak", header: "sks_prak", width:100},
		{ id:"sks_prak_lap", header: "sks_prak_lap", width:100},
		{ id:"sks_sim", header: "sks_sim", width:100}
	],
	select:"row",
	url: "./subtansi/data",
	onClick:{
		edit:function(e,id,node){
			$$("tabbar").setValue("frm_subtansi");
		},
		trash:function(e,id,node){
			webix.confirm({
				text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						$$("grd_subtansi").remove(id);
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
			var form   = $$('frm_subtansi_mk');
			var values = form.getValues();

			if (!values.webix_operation)
				values.webix_operation = 'update';

			if(form.isDirty()){
				if(!form.validate())
					return false;

				form.setValues(values);
				webix.ajax().post("./subtansi", values);
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
		    		value: 'lst_subtansi',
			    	multiview:true,
			    	optionWidth:141,
			    	padding: 5,
			    	options: [
				    	{ value: 'List Subtansi', id: 'lst_subtansi'},
				    	{ value: 'Add New', id: 'frm_subtansi'}
			    	],
			    	on:{
			    		onItemClick : function(id, e){
						    if (e.target.innerText == 'Add New'){
						    	$$("frm_subtansi_mk").clear();
						    	$$("frm_subtansi_mk").setValues({webix_operation:'insert'});
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
		    		id:"lst_subtansi",
		    		rows: [grd_subtansi]
		    	},
		    	{
		    		id:"frm_subtansi",
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
    	$$("frm_subtansi_mk").bind($$("grd_subtansi"));

    	var dp = new webix.DataProcessor({
		    updateFromResponse:true,
		    autoupdate:true,
		    master: $$("grd_subtansi"),
		    url: "connector->./kuliah/subtansi/data",
		    on: handleProcessing
		});
    }
};

});
