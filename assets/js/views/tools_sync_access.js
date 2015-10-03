define([
	"apps",
	"views/modules/dataProcessing"
],function(apps, handleProcessing){

	var grid = {
		view:"datatable",
		id:"grd_log",
		columns:[
			{id: "kode", width: 100},
			{id: "nama", width: 300},
			{id: "error_code", width: 100},
			{id: "error_desc", width: 500},
		],
		select:"row"
	};

	var button = {
		type: "space",
		cols:[
			{ template:"File pddikti.mdb harus ada di folder access"},
			{ view:"button", value:"Sinkronisasi", css: "button_success button_raised", width:150, click:function(){
				webix.ajax().post("/twig_template/sync/fromaccess", function(t,res) {
					var res = res.json();
					$$("grd_log").parse(res.result);
				});
			}}
		]

	}

	var ui_scheme = {
		type: "line",
		view:"scrollview",
        id:"dashboard",
        scroll:"y", //vertical scrolling
        body:{
			rows:[
				button, grid
			]
		}
	};

	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    },
	    onInit:function(){

	    }
	};

});
