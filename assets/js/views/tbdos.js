define([
	"apps",
	"views/modules/tbdossearch",
	"views/forms/tbdosform",
], function(apps,search,forms){

var store = new webix.DataCollection({
	url:apps.ApiProvider+"/api/tbdos"
});

var dp = new webix.DataProcessor({
    updateFromResponse:true, 
    autoupdate:true,
    master: store,
    url:apps.ApiProvider+"/api/tbdos/save",
    on:{
    	onAfterSync: function(id, text, data){
        	var res = data.json();
		    webix.message({ type:"default", 
		    	text: res.messages.data+"<br>"+res.messages.status +" in "+res.messages.operation, 
		    	expire:5000
		    });
		},
		onAfterSaveError: function(id, status, res, details){
			webix.modalbox({
		        type:"alert-error",
		        width: "500px",
		        title:"Error Messages",
		        text:details.text,
		        buttons:["Ok"],
		   	});
			// webix.message({ type:"error", text:status+" error <br> please call server administrator",  expire:-1 });
		}
    }
});  

var controls = [
	{ view: "button", type: "iconButton", icon: "plus", css:"button_success button_raised", label: "Add Dosen", width: 180, click: function(){
		$$("wintbdos").show();
		// $$("listTbdos").unselectAll();
		$$("formTbdos").clear();
		$$("btsubmit").setValue("Save");
	}},
	{},
	{view:"richselect", id:"order_filter", value: "all", maxWidth: 400, minWidth: 250, vertical: true, labelWidth: 100, options:[
		{id:"all", value:"All"},
		{id:"61201", value: "<div class='marker prodi_61201'></div> MANAJEMEN"},
		{id:"62201", value: "<div class='marker prodi_62201'></div> AKUNTANSI"},
	],  label:"Filter Prodi", on:{
		onChange:function(){
			var val = this.getValue();
			if(val=="all")
				$$("listTbdos").filter("#KDPSTTBDOS#","");
			else
				$$("listTbdos").filter("#KDPSTTBDOS#",val);
		}
	}
	}
];

var button ={
	view: "form",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{},
		{ view: "button", css: "button_danger", label: "Delete", width: 90, click:function(){
			var id = $$("listTbdos").getSelectedId();
			if (id =='') {
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}else{
				webix.confirm({
					title: "Delete",
					text: "Are you sure you want to delete the selected item?",
					callback: function(res) { 
						if (res) {
							store.remove(id);
			            }
			        }
			    });
			}
		}},
		{ view: "button", css: "button_raised", label: "Edit", width: 90, click:function(obj){
			var id = $$("listTbdos").getSelectedId();
			if (id =='') {
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}else{
				webix.$$("wintbdos").show();
			}

			$$("btsubmit").setValue("update");
		}},
	]
}

var tab = {
	type:"clean",
	gravity:2.2,
	rows:[
	    {
	        borderless:true, view:"tabbar", id:'tabbar', value: 'profile', multiview:true, options: [
	            { value: 'Profile', id: 'profile'},
	            { value: 'Pengajaran', id: 'pengajaran'},
	            { value: 'Penelitian', id: 'penelitian'},
	            { value: 'Pengabdian', id: 'pengabdian'}
	        ]
	    },
	    {
	    	cells:[
	    	{
	    		id:"profile",
	    		rows: [
	    		{
	    			view: "template",
	    			id:"tplprofile",
	    			scroll:true,
	    			template:"html->profile"
	    		},
	    		button
	    		]
	    	},
	    	{
	    		id:"pengajaran",
	    		rows:[
	    		{
	    			view:"datatable",
	    			id:"DTpengajaran", editable:true,
	    			dataFeed: apps.ApiProvider+"/api/trakd",
	    			save: apps.ApiProvider+"/api/trakd/save",
	    			columns:[
	    			{ id:"THSMSTRAKD"},
	    			{ id:"KDKMKTRAKD"},
	    			{ id:"NAKMKTBKMK"},
	    			{ id:"SKSMKTBKMK"},
	    			{ id:"KELASTRAKD"},
	    			{ id:"TMRENTRAKD"},
	    			{ id:"TMRELTRAKD"},
	    			],
	    		},
	    		{
	    			view: "form",
					paddingX:5,
					paddingY:5,
					height:40,
	    			cols:[
	    				{},
	    				{ view: "button", css: "button_success button_raised", label: "ADD", width: 90, click:function(obj){
							var id = $$("listTbdos").getSelectedId();
							if (id =='') {
								webix.message({ type:"error", text:"Please select one", expire:3000});
							}else{
								webix.message({ type:"error", text:"show window", expire:3000});
							}
						}},
	    			]
	    		}
	    		]
	    	},
	    	{
	    		id:"penelitian",
	    		rows:[
	    		{
	    			view:"datatable",
	    			id:"DTpenelitian", editable:true,
	    			dataFeed: apps.ApiProvider+"/api/trakd",
	    			save: apps.ApiProvider+"/api/trakd/save",
	    			columns:[
	    			{ id:"THSMSTRAKD"},
	    			{ id:"KDKMKTRAKD"},
	    			{ id:"NAKMKTBKMK"},
	    			{ id:"SKSMKTBKMK"},
	    			{ id:"KELASTRAKD"},
	    			{ id:"TMRENTRAKD"},
	    			{ id:"TMRELTRAKD"},
	    			],
	    		},
	    		{
	    			view: "form",
					paddingX:5,
					paddingY:5,
					height:40,
	    			cols:[
	    				{},
	    				{ view: "button", css: "button_success button_raised", label: "ADD", width: 90, click:function(obj){
							var id = $$("listTbdos").getSelectedId();
							if (id =='') {
								webix.message({ type:"error", text:"Please select one", expire:3000});
							}else{
								webix.message({ type:"error", text:"show window", expire:3000});
							}
						}},
	    			]
	    		}
	    		]
	    	},
	    	{
	    		id:"pengabdian",
	    		rows:[
	    		{
	    			view:"datatable",
	    			id:"DTpengabdian", editable:true,
	    			dataFeed: apps.ApiProvider+"/api/trakd",
	    			save: apps.ApiProvider+"/api/trakd/save",
	    			columns:[
		    			{ id:"THSMSTRAKD"},
		    			{ id:"KDKMKTRAKD"},
		    			{ id:"NAKMKTBKMK"},
		    			{ id:"SKSMKTBKMK"},
		    			{ id:"KELASTRAKD"},
		    			{ id:"TMRENTRAKD"},
		    			{ id:"TMRELTRAKD"},
	    			],
	    		},
	    		{
	    			view: "form",
					paddingX:5,
					paddingY:5,
					height:40,
	    			cols:[
	    				{},
	    				{ view: "button", css: "button_success button_raised", label: "ADD", width: 90, click:function(obj){
							var id = $$("listTbdos").getSelectedId();
							if (id =='') {
								webix.message({ type:"error", text:"Please select one", expire:3000});
							}else{
								webix.message({ type:"error", text:"show window", expire:3000});
							}
						}},
	    			]
	    		}
	    		]
	    	},
	    	]
	    }
	]
}

var ui_scheme = {
	type: "material",
	id:"uimsmhs",
    rows:[
    {
    	height:40,
    	css:"bg_clean",
    	cols:controls
    },
    {
    	margin:10,
    	cols:[
    		search,
    		tab
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
					{ view: "button", id:"btsubmit", css: "button_raised",label:"Save", width: 90, click:function(){
						var form = $$('formTbdos');
						var values = form.getValues();
						var val = $$("btsubmit").getValue();

						if(form.isDirty()){
							if(!form.validate())
								return false;

							if (val === 'update')
								form.save();
							else
								store.add(values);
		                    
		                    this.getTopParentView().hide(); //hide window
						};
					}}
		    	]
	    	}
    	],
    }
};

return {
	ui_:ui_scheme,
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.setUiWindow = ui_window;
    	// apps.setUiPopup  = win.popup;
    	apps.webix();
    },
    onInit: function(){
		$$("listTbdos").sync(store);
		$$("formTbdos").bind($$("listTbdos"));
		$$("tplprofile").bind($$("listTbdos"));
		$$("DTpengajaran").bind($$("listTbdos"),"NODOSTRAKD");
	}
};
});