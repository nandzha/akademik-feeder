define([
	"apps",
	"views/modules/mhs_search",
	"views/forms/msmhsform",
	"views/modules/dataProcessing",
], function(apps,search,forms,handleProcessing){

var button ={
	view: "form",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{},
		{ view: "button", css: "button_danger", label: "Delete", width: 90, click:function(){
			var id = $$("listmsmhs").getSelectedId();
			if (id) {
				webix.confirm({
					title: "Delete",
					text: "Are you sure you want to delete the selected item?",
					callback: function(res) {
						if (res) {
							$$("listmsmhs").remove(id);
			            }
			        }
			    });
			}else{
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}
		}},
		{ view: "button", css: "button_raised", label: "Edit", width: 90, click:function(obj){
			var id = $$("listmsmhs").getSelectedId();
			if (id) {
				webix.$$("winmsmhs").show();
			}else{
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}
		}}
	]
}

var ui_scheme = {
	type: "line",
	id:"uimsmhs",
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
    		},
    	]
    }
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
						if(!form.validate())
							return false;

						form.save();
	                    this.getTopParentView().hide(); //hide window
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
		$$("tplprofile").bind( $$("listmsmhs") );
		// $$("formMsmhs").bind( $$("listmsmhs"), "id_pd");
        $$("formMsmhs").bind( $$("listmsmhs"), "$data", function(data, source){
            if (data){
                this.load("./detail?action=get&id="+data.id_pd);
            }
        });

		var dp = new webix.DataProcessor({
		    updateFromResponse:true,
		    autoupdate:true,
		    master: $$("listmsmhs"),
		    url: "connector->./detail",
		    on: handleProcessing
		});
	}
};
});
