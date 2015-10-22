define([
	"apps",
	"views/modules/dataProcessing"
],function(apps, handleProcessing){

	var grid = {
		view:"datatable",
		id:"grd_log",
		columns:[
			{id: "kode", width: 100},
			{id: "nipd", width: 150},
			{id: "nama", width: 250},
			{id: "nilai", width: 60},
			{id: "error_code", width: 110},
			{id: "error_desc", width: 450},
		],
		select:"row"
	};

	var form = {
		view:"form",
		id:"frm_sync",
		elements:[
			{
				rows:[
					{
						cols:[
							{ view:"combo", name:"id_sms", label:"Prodi", options:"/twig_template/sugest/prodi"},
							{ view:"text", name:"kode_mk", label:"Kode MK"},
						]
					},
					{
						cols:[
							{ view:"text", name:"sheet_name", label:"Nama Sheet"},
							{ view:"combo", name:"nama_file", label:"Nama File", options:[
								'Nilai_TI_Extensi.xls',
								'Nilai_TI_Reguler.xls'
							]}
						]
					}
				]
			},
			{ margin:5, cols:[
				{},
				{ view:"button", value:"Submit", css: "button_success button_raised", width:150, click:function(){
					var form = $$('frm_sync');
					var values = form.getValues();

					if(form.validate()){
						webix.ajax().post("./nilaiexcel/data", values, function(t,res) {
							var res = res.json();
							$$("grd_log").clearAll();
							$$("grd_log").parse(res.result);
						});
					}
				}}
			]}
		],
		elementsConfig:{
			labelWidth: 100,
			bottomPadding: 18
		}
	}

	var ui_scheme = {
		type: "line",
		view:"scrollview",
        id:"dashboard",
        scroll:"y", //vertical scrolling
        body:{
			rows:[
				form, grid
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
