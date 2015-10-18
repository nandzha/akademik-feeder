define([
	"apps"
],function(apps){
	var grid = {
		view:"datatable",
		id:"datatable1",
		columns:[
			{ id: "nm_pd", width:200, header: "nm_pd"},
			{ id: "nipd", width:200, header: "nipd"},
			{ id: "jk", width:200, header: "jk"},
			{ id: "nm_agama", width:200, header: "nm_agama"},
			{ id: "tgl_lahir", width:200, header: "tgl_lahir"},
			{ id: "nm_lemb", width:200, header: "nm_lemb"},
			{ id: "nm_stat_mhs", width:200, header: "nm_stat_mhs"}
		],
		select:"row", editable:true, editaction:"dblclick",

		save: "connector->mahasiswa/lst",
		url: "mahasiswa/lst"
	};

	var ui_scheme = {
		type: "material",
		rows:[
			grid
		]	
	};

	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    }
	};

});