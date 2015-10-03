define([
	"apps",
	"views/modules/dataProcessing"
],function(apps, handleProcessing){

	function equals(a,b){
		a = a.toString().toLowerCase();
		return a.indexOf(b) !== -1;
	}

	function oneForAll(value, filter, obj){
		if (equals(obj.start_date, filter)) return true;
		if (equals(obj.event_name, filter)) return true;
		return false;
	};

	var grid = {
		view:"datatable",
		id:"datatable1",
		columns:[
			{ id:"start_date",	editor:"text",  width:200},
			{ id:"end_date",	editor:"text",  width:200},
			{ id:"event_name",	editor:"text",  width:400,
				header:[{
					content:"serverFilter", placeholder:"Find Mahasiswa by Nim or Name",
					compare:oneForAll
				}]
			},
			{id: "type", width: 100},
			{id: "foo", width: 100},
		],
		select:"row", editable:true, editaction:"dblclick",

		// save: "connector->test/model",
		/*save: {
			updateFromResponse:true,
			url:"connector->test/model"
		},*/
		url: "test/model"
	};


	var buttons = {
		view:"toolbar", elements:[
			{ view:"button", value:"Add Row", click:function(){
				$$('datatable1').add({
					start_date:"2015-08-30",
					end_date  :"2015-12-30",
					event_name:"Wisuda event"
				});
			}},
			{ view:"button", value:"Delete Row", click:function(){
				var id = $$('datatable1').getSelectedId();
				if (id)
					$$('datatable1').remove(id);
			}},
			{}
		]
	};
	var ui_scheme = {
		type: "line",
		view:"scrollview",
        id:"dashboard",
        scroll:"y", //vertical scrolling
        body:{
			rows:[
				buttons, grid
			]
		}
	};

	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    },
	    onInit:function(){
	    	// webix.ready(function(){
		    	var dp = new webix.DataProcessor({
		            updateFromResponse:true,
		            autoupdate:true,
		            master: $$("datatable1"),
		            url: "connector->test/model",
		            on: handleProcessing
		        });
		    // });
	    }
	};

});
