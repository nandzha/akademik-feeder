define([
	"apps",
	"views/modules/mhs_search",
	"views/modules/dataProcessing"
], function(apps,search, handleProcessing){

var grd_nilai = {
	gravity: 2.2,
	view:"datatable",
	id:"grd_nilai",
	navigation:true,
	columns:[
		{ id: "id_smt", header: "id_smt", width:80 },
		{ id: "kode_mk", header: "kode_mk", width:100},
		{ id: "nm_mk", header: "nm_mk", width:300},
		{ id: "nm_kls", header: "nm_kls", width:80},
		{ id: "sks_mk", header: "sks_mk", width:80},
		{ id: "nilai_huruf", header: "nilai_huruf", width:80, editor: "text"},
	],
	select:"cell",
	editable:true,
	dataFeed : "./nilailst/data"
};

var ui_scheme = {
	type: "line",
	id:"ui_nilai",
    rows:[
    {
    	margin:10,
    	type: "material",
    	cols:[
    		search,
    		grd_nilai
    	]
    }
    ],
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){
		$$("grd_nilai").bind($$("listmsmhs"), "id_reg_pd");
		var dp = new webix.DataProcessor({
            updateFromResponse:true, 
            autoupdate:true,
            master: $$("grd_nilai"),
            url: "connector->./nilailst/data",
            on: handleProcessing
        });
	}
};
});