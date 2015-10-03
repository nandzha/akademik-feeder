define([
	"apps",
	"views/modules/dataProcessing"
],function(apps, handleProcessing){
	var grid = {
		view:"datatable",
		id:"datatable1",
		columns:[
			{ id: "id_smt", header : "smt", width: 80 },
			{ id: "nipd", header: "nim", width: 100 },
            { id: "nm_pd", header: "nama", width: 250 },
            { id: "nm_prodi", header: "prodi", width: 250 },
            { id: "mulai_smt", header: "thak", width: 80 },
	        { id: "nm_stat_mhs", header : "status", width: 200 },
	        { id: "ips", header : "ips", width: 80 },
	        { id: "ipk", header : "ipk", width: 80 },
	        { id: "sks_smt", header : "sks smt", width: 80 },
	        { id: "sks_total", header : "sks total", width: 80 }
		],
		select:"row", editable:true, editaction:"dblclick",

		url: "./aktifitasmhs/data"
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
	    },
	    onInit:function(){
	    	var dp = new webix.DataProcessor({
	            id:"dp_mahasiswa",
	            updateFromResponse:true,
	            autoupdate:true,
	            master: $$("listdosen"),
	            url: "connector->./nilai/data",
	            on: handleProcessing
	        });
	    }
	};

});
