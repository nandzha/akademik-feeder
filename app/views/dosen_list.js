define([
	"apps",
	"views/forms/tbdosform",
	"views/modules/dataProcessing"
],function(apps, forms, handleProcessing){
var grd_list_dosen = {
	view:"datatable",
	id:"grd_list_dosen",
	columns:[
		{ id:"nidn", width:200, header: "nidn" },
		{ id:"nm_ptk", width:200, header: "nm_ptk" },
		{ id:"jk", width:200, header: "jk" },
		{ id:"tmpt_lahir", width:200, header: "tmpt_lahir" },
		{ id:"nm_agama", width:200, header: "nm_agama" },
		{ id:"nm_stat_pegawai", width:200, header: "nm_stat_pegawai" },
		{ id:"nm_lemb", width:200, header: "nm_lemb" }
	],
	select:"row",
	url: "./dosen/lst"
};

var btn_save ={
	paddingX:5,
	paddingY:5,
	height:45,
	cols:[
		{},
		{ view: "button", css:"button_raised", label: "save", width: 130, click:function(obj){
			var form = $$('formTbdos');
			if(form.isDirty()){
				if(!form.validate())
					return false;
				$$("grd_list_dosen").parse(form.getValues());
				$$("grd_list_dosen").add(form.getValues());
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
		    		value: 'lst_dosen', 
			    	multiview:true, 
			    	optionWidth:141,  
			    	padding: 5, 
			    	options: [
				    	{ value: 'List Dosen', id: 'lst_dosen'},
				    	{ value: 'Add new', id: 'frm_dosen'}
			    	]
		    	}
	    	]
		},
		{
			id:"multiview_dosen",
	    	cells:[
		    	{
		    		id:"lst_dosen",
		    		rows: [grd_list_dosen]
		    	},
		    	{
		    		id:"frm_dosen",
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
    	var dp = new webix.DataProcessor({
		    updateFromResponse:true, 
		    autoupdate:true,
		    master: $$("grd_list_dosen"),
		    url: "connector->./dosen/detail/data",
		    on: handleProcessing
		});
    }
};

});