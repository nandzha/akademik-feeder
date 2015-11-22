define([
	"apps",
	"views/forms/msmhsform",
	"views/modules/dataProcessing",
	"views/modules/dataProgressBar",
],function(apps, forms, handleProcessing, notifidata){

var grd_mahasiswa = {
	view:"datatable",
	id:"grd_mahasiswa",
	columns:[
		{ id: "nipd", width:200, header: "nipd"},
		{ id: "nm_pd", width:200, header: "nm_pd"},
		{ id: "jk", width:200, header: "jk"},
		{ id: "nm_agama", width:200, header: "nm_agama"},
		{ id: "tgl_lahir", width:200, header: "tgl_lahir"},
		{ id: "nm_stat_mhs", width:200, header: "nm_stat_mhs"}
	],
	select:"row",
	url: "./mahasiswa/lst",
	ready: notifidata.emptydata,
	on: notifidata.progressbar

};

var btn_save ={
	paddingX:5,
	paddingY:5,
	height:45,
	cols:[
		{},
		{ view: "button", css:"button_raised", label: "save", width: 130, click:function(obj){
			var form = $$('formMsmhs');
			if(form.isDirty()){
				if(!form.validate())
					return false;

				// form.save();
				$$("grd_mahasiswa").parse(form.getValues());
				$$("grd_mahasiswa").add(form.getValues());
				// webix.ajax().post("./mahasiswa/detail?action=add", form.getValues());
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
		    		value: 'lst_mhs',
			    	multiview:true,
			    	optionWidth:141,
			    	padding: 5,
			    	options: [
				    	{ value: 'List Mahasiswa', id: 'lst_mhs'},
				    	{ value: 'Add new', id: 'frm_mhs'}
			    	]
		    	}
	    	]
		},
		{
			id:"multiview_mhs",
	    	cells:[
		    	{
		    		id:"lst_mhs",
		    		rows: [grd_mahasiswa]
		    	},
		    	{
		    		id:"frm_mhs",
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
    	// $$("formMsmhs").bind( $$("grd_mahasiswa") );
    	// $$("formMsmhs").define("dataFeed","./mahasiswa/detail");

    	var dp = new webix.DataProcessor({
		    updateFromResponse:true,
		    autoupdate:true,
		    master: $$("grd_mahasiswa"),
		    url: "connector->./mahasiswa/detail",
		    on: handleProcessing
		});
    }
};
});
