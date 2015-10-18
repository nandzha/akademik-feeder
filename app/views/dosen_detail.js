define([
	"apps",
	"views/modules/dosen_search",
	"views/forms/tbdosform",
	"views/modules/dataProcessing"
], function(apps,search,forms,handleProcessing){

var button ={
	view: "form",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{},
		{ view: "button", css: "button_danger", label: "Delete", width: 90, click:function(){
			var id = $$("listdosen").getSelectedId();
			if (id) {
				webix.confirm({
					title: "Delete",
					text: "Are you sure you want to delete the selected item?",
					callback: function(res) { 
						if (res) {
							store.remove(id);
			            }
			        }
			    });
			}else{
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}
		}},
		{ view: "button", css: "button_raised", label: "Edit", width: 90, click:function(obj){
			var id = $$("listdosen").getSelectedId();
			if (id) {
				webix.$$("wintbdos").show();
				$$("btsubmit").setValue("update");
			}else{
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}

		}}
	]
}

var ui_scheme = {
	type: "line",
	id:"ui_dosen",
    rows:[
    {
    	margin:10,
    	type: "material",
    	cols:[
    		search,
    		{
		    	gravity:2.2,
    			rows:[
	    			{
		    			view: "template",
		    			id:"tplprofile",
		    			scroll:true,
		    			template:"html->profile"
	    			},
	    			button
    			]
    		}
    	]
    }
    ],
};

var ui_window = {
	view:"window", 
	id:"wintbdos",
	modal:true,
    head:{
		view:"toolbar", margin:-4, cols:[
			{ view:"label", label: "Form Dosen" },
			{ view:"icon", icon:"question-circle", click:"webix.message('About pressed')"},
			{ view:"icon", icon:"times-circle", click:function(){
				this.getTopParentView().hide();
			}}
		]
	},
	move:true,
	position:"center",
	width:700,
	height:500,
    body: {
    	rows:[
    		forms,
	    	{
	    		padding:15,
		    	cols:[
		    		{},
					{ view: "button", id:"btsubmit", css: "button_raised",label:"Save", width: 90, click:function(){
						var form = $$('formTbdos');
						var values = form.getValues();
						var val = $$("btsubmit").getValue();

						if(form.isDirty()){
							if(!form.validate())
								return false;

							form.save();
		                    this.getTopParentView().hide(); //hide window
						};
					}}
		    	]
	    	}
    	],
    }
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.setUiWindow = ui_window;
    	apps.webix();
    },
    onInit: function(){
		$$("tplprofile").bind( $$("listdosen") );
		$$("formTbdos").bind( $$("listdosen"), "id_ptk");
		var dp = new webix.DataProcessor({
		    updateFromResponse:true, 
		    autoupdate:true,
		    master: $$("listdosen"),
		    url: "connector->./detail",
		    on: handleProcessing
		});
	}
};
});