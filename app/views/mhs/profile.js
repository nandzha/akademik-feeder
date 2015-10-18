define([
	"apps",
	"views/forms/msmhsform",
], function(apps,forms){

var button ={
	view: "form",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{},
		{ view: "button", css: "button_raised", label: "Edit", width: 90, click:function(obj){
			webix.$$("winmsmhs").show();
			$$("btsubmit").setValue("update");
		}},
	]
}

var ui_scheme = {
	type: "material",
	id:"uimsmhs",
    rows:[
    {
		id:"profile",
		rows: [
		{
			view: "template",
			id:"tplprofile",
			scroll:true,
			template:"html->profile",
		},
		button
		]
	},
    ],
};

var ui_window = {
	view:"window", 
	id:"winmsmhs",
	modal:true,
    head:{
		view:"toolbar", margin:-4, cols:[
			{ view:"label", label: "Form Mahasiswa" },
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
					{ view: "button", id:"btsubmit", css: "button_raised", label: "Save", width: 90, click:function(){
						var form = $$('formMsmhs');
						var values = form.getValues();
						var val = $$("btsubmit").getValue();

						if(form.isDirty()){
							if(!form.validate())
								return false;

							if (val === 'update'){								
								values.webix_operation = "update";

								webix.ajax().post(apps.ApiProvider+"/api/msmhs/save", values, function(t,res) {
									var res = res.json();
									
									webix.message({ type:"default", 
										text: res.messages.data+"<br>"+res.messages.status +" in "+res.messages.operation, 
										expire:5000
									});									
								});
							}
		                    
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
    	// apps.setUiPopup  = win.popup;
    	apps.webix();
    },
    onInit: function(){
		var result = webix.ajax().sync().get(apps.ApiProvider+"/api/msmhs?filter[NIMHSMSMHS]="+apps.storeInit.nim);
		$$("tplprofile").parse(result.responseText);
		$$("formMsmhs").parse(result.responseText);

		/*$$("formMsmhs").attachEvent("onAfterValidation", function(result, value){
		    console.log(value);
		});*/
	}
};
});