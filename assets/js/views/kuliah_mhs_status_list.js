define([
	"apps",
	"views/modules/dataProcessing"
],function(apps, handleProcessing){
	var grd_stat_mhs = {
		view:"datatable",
		id:"grd_stat_mhs",
		columns:[
			{ id:"nipd", header: "nim", width: 100 },
            { id:"nm_pd", header: "nama", width: 250 },
            { id:"nm_lemb", header: "prodi", width: 200 },
            { id:"mulai_smt", header: "thak", width: 80 },
            { id:"ket_keluar", header: "ket keluar", width: 150 },
            { id:"tgl_keluar", header: "tgl keluar", width: 150 },
            { id:"ket", header: "ket", width: 100 }
		],
		select:"row", editable:true, editaction:"dblclick",

		url: "statusmhs/data"
	};

	var ui_scheme = {
		type: "material",
		rows:[
			grd_stat_mhs
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