define([
	"apps",
    "views/modules/kelas_search",
	"views/modules/dataProcessing"
],function(apps, search, handleProcessing){

var grd_nilai = {
	gravity:1.5,
    id:"grd_nilai",
	view:"datatable",
    navigation:true,
	columns:[
		{ id: "id_smt", header: "smt", width: 80 },
        { id: "nm_kls", header: "kls", width: 50 },
        { id: "nipd", header: "nim", width: 130 },
        { id: "nm_pd", header: "nama", width: 250 },
        { id: "mulai_smt", header: "thak", width: 80 },
        { id: "nilai_angka", header: "angka", width: 70, editor: "text" },
        { id: "nilai_huruf", header: "huruf", width: 70, editor: "select", options: "/twig_template/sugest/bobotnilai" },
	],
	select:true,
	editable:true,
	datafetch:30,
	dataFeed : "./nilai/data",
    on:{
        onAfterEditStop : function(state, editor, ignoreUpdate){
            if(state.value != state.old){
                this.addRowCss(editor.row,"bg-info");
            }
        }
    }
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
	]
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){
		$$("grd_nilai").bind($$("listmk"), "id_kls");
		var dp = new webix.DataProcessor({
    		id:"dp_mahasiswa",
		    updateFromResponse:true,
		    autoupdate:true,
		    master: $$("grd_nilai"),
		    url: "connector->./nilai/data",
		    on: handleProcessing
		});
	}
};

});
