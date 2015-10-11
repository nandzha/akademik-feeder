define([
	"apps",
	"views/modules/dataProcessing"
],function(apps, handleProcessing){

	var grid = {
		view:"datatable",
		id:"grd_log",
		columns:[
			{id: "nim", width: 100},
			{id: "error_desc", width: 500},
		],
		select:"row"
	};

	var form = {
		view:"form",
		id:"frm_sync",
		elements:[
			// { view:"datepicker", name:"last_update", label:"Tgl. Update", invalidMessage: "Tgl. Update can not be empty"},
			{ margin:5, cols:[
				{},
				{ view:"button", value:"Hitung AKM", css: "button_success button_raised", width:150, click:function(){
					var form = $$('frm_sync');
					var values = form.getValues();

					values.webix_operation = 'insert';

					// if(form.validate()){
						webix.ajax().post("/twig_template/tools/trakm", values, function(t,res) {
							var res = res.json();
							$$("grd_log").parse(res.result);
						});
					// }
				}}
			]}
		],
		elementsConfig:{
			// labelPosition: "top",
			labelWidth: 100,
			bottomPadding: 18
		},
		rules:{
			"last_update": webix.rules.isNotEmpty
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
