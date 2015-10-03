define([
	"apps",
	"views/modules/msmhssearch",
	"views/forms/msmhsform",
], function(apps,search,forms){

var store = new webix.DataCollection({
	url:apps.ApiProvider+"/api/msmhs"
});

var dp = new webix.DataProcessor({
    updateFromResponse:true, 
    autoupdate:true,
    master: store,
    url:apps.ApiProvider+"/api/msmhs/save",
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
		        width: 500,
		        height: 500,
		        title:"Error Messages",
		        text: details.text,
		        buttons:["Ok"],
		   	});

		}
    }
});  

var controls = [
	{ view: "button", type: "iconButton", icon: "plus", css:"button_success button_raised", label: "Add Mahasiswa", width: 180, click: function(){
		$$("winmsmhs").show();
		$$("formMsmhs").clear();
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
				$$("listmsmhs").filter("#KDPSTMSMHS#","");
			else
				$$("listmsmhs").filter("#KDPSTMSMHS#",val);
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
			var id = $$("listmsmhs").getSelectedId();
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
			var id = $$("listmsmhs").getSelectedId();
			if (id =='') {
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}else{
				webix.$$("winmsmhs").show();
			}
			$$("btsubmit").setValue("update");
		}},
		{ view: "button", css: "button_info", label: "Detail", width: 90, click:function(obj){
			var id = $$("listmsmhs").getSelectedId();
			if (id =='') {
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}else{
				webix.$$("winmsmhs").show();
				// $$("formMsmhs").disable();
				// for (var i=0; i<form2.length; i++) form2[i].readonly = true;
			}
			$$("btsubmit").setValue("update");
		}},
	]
}

var button2 ={
	view: "form",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{},
		{ view: "button", css: "button_success button_raised", label: "ADD", width: 90, click:function(obj){
			var id = $$("listmsmhs").getSelectedId();
			if (id =='') {
				webix.message({ type:"error", text:"Please select one", expire:3000});
			}else{
				$$('statusReg').add({
					NIMHS : id,
					THSMS : apps.storeInit.thnSmt // from thsmt aktif
					// REGSMS : '0',
					// REGTA : '0',
					// REGKP : '0',
					// REGKKL : '0',
					// REGHER : '0'
				})
			}
		}},
	]
}

function ruleRegTA(obj, common, value){	
	if (obj.SMTMHS < 8)
		return "-";
	else{
		if (value == "1")
			return '<input class="webix_table_checkbox" type="checkbox" checked="true">';
		else
			return '<input class="webix_table_checkbox" type="checkbox">';
	}
}

function ruleRegKP(obj, common, value){	
	if (obj.SMTMHS < 5)
		return "-";
	else{
		if (value == "1")
			return '<input class="webix_table_checkbox" type="checkbox" checked="true">';
		else
			return '<input class="webix_table_checkbox" type="checkbox">';
	}
}

function ruleRegKKL(obj, common, value){	
	if (obj.SMTMHS < 6)
		return "-";
	else{
		if (value == "1")
			return '<input class="webix_table_checkbox" type="checkbox" checked="true">';
		else
			return '<input class="webix_table_checkbox" type="checkbox">';
	}
}

var tab = {
	type:"clean",
	gravity:2.2,
	rows:[
	    {
	        borderless:true, view:"tabbar", id:'tabbar', value: 'profile', multiview:true, options: [
	            { value: 'Profile', id: 'profile'},
	            { value: 'Registrasi', id: 'registrasi'},
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
	    		id:"registrasi",
	    		rows:[
	    		{
	    			view:"datatable",
	    			id:"statusReg", editable:true,
	    			dataFeed: apps.ApiProvider+"/api/trstsreg",
	    			save: {
						updateFromResponse: true,
						url: apps.ApiProvider+"/api/trstsreg/save",
						on:{
							onAfterSync: function(id, text, data){
								var res = data.json();
								if (res.status){
									webix.message({ type:"default", 
										text: res.messages.data+" | "+res.value+"<br>"+res.messages.status +" in "+res.messages.operation, 
										expire:5000
									});
								}
								
								if(! res.status){
									// $$('statusReg').remove(id.id);
									$$('statusReg').addRowCss(id.id,'bg-danger');
									
									webix.modalbox({
										type:"alert-error",
										width: "500px",
										title:"Error Messages",
										text:res.messages.THSMS,
										buttons:["Ok"]
									});
								}
							},
							onAfterSaveError: function(id, status, res, details){
								webix.modalbox({
									type:"alert-error",
									width: "500px",
									title:"Error Messages",
									text:details.text,
									buttons:["Ok"],
								});
							}
						}
					},
	    			columns:[
	    			{ id:"THSMS"},
	    			{ id:"SMTMHS"},
	    			{ id:"NIMHS"},
	    			{ id:"REGSMS",	checkValue:'1', uncheckValue:'0', template:"{common.checkbox()}"},
	    			{ id:"REGTA", 	checkValue:'1', uncheckValue:'0', template: ruleRegTA },
	    			{ id:"REGKP", 	checkValue:'1', uncheckValue:'0', template: ruleRegKP },
	    			{ id:"REGKKL", 	checkValue:'1', uncheckValue:'0', template: ruleRegKKL },
	    			{ id:"REGHER", 	checkValue:'1', uncheckValue:'0', template:"{common.checkbox()}"},
	    			{ id:"MAXKRS", editor:"text"},
	    			{ id:"MAXKRSHER", editor:"text"},
	    			]
	    		},
	    		button2
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
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.setUiWindow = ui_window;
    	// apps.setUiPopup  = win.popup;
    	apps.webix();
    },
    onInit: function(){
		$$("listmsmhs").sync(store);
		$$("tplprofile").bind($$("listmsmhs"));
		$$("formMsmhs").bind($$("listmsmhs"));
		$$("statusReg").bind( $$("listmsmhs"),'NIMHS');
	}
};
});