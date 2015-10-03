define([
	"apps",
	"views/modules/msmhssearch",
], function(apps,search){

var storeMsmhs = new webix.DataCollection({
	url:apps.ApiProvider+"/api/msmhs"
});

var storeTbdos = new webix.DataCollection({
	url:apps.ApiProvider+"/api/listtbdos"
});

var dp = new webix.DataProcessor({
    updateFromResponse:true, 
    master: storeMsmhs,
    url: apps.ApiProvider+"/api/trlsm/save",
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
		},
		onBeforeUpdate: function(id, details){
			if(details.data.id === ""){
				details.operation = "insert";
				details.data.c_operation = "insert";
			}
		}
    }
}); 

var form =  {
	view: "form",
	id: "formTrlsm",
	dataFeed: apps.ApiProvider+"/api/trlsm",
	scroll: true,
	elements: [
		{
			margin: 10,
			cols: [
			{
				rows: [
					{view: "text",name: "NIMHSTRLSM",placeholder: "NIMHSTRLSM", readonly:true},
					{view: "select",name: "STMHSTRLSM",placeholder: "Status",options: [
						{id:"A", value: "AKTIF"},
						{id:"C", value: "CUTI"},
						{id:"D", value: "DROP-OUT/PUTUS STUDI"},
						{id:"G", value: "SEDANG DOUBLE DEGREE"},
						{id:"K", value: "KELUAR"},
						{id:"L", value: "LULUS"},
						{id:"N", value: "NON-AKTIF"}
					]},
					{view: "datepicker",	name: "TGLLSTRLSM",	placeholder: "TGLLSTRLSM",},
					{view: "text",	name: "SKSTTTRLSM",	placeholder: "SKSTTTRLSM",	},
					{view: "text",	name: "NLIPKTRLSM",	placeholder: "NLIPKTRLSM",	},
					{view: "text",	name: "NOSKRTRLSM",	placeholder: "NOSKRTRLSM",	}
				]
			},
			{
				rows: [
					{view: "datepicker",	name: "TGLRETRLSM",	placeholder: "TGLRETRLSM",},
					{view: "text",	name: "NOIJATRLSM",	placeholder: "NOIJATRLSM",},
					{view: "select",name: "STLLSTRLSM",placeholder: "STLLSTRLSM",options: [
						{id:"N", value:"Non skripsi"},
						{id:"S", value:"Skripsi-TA"}
					]},
					{view: "select",name: "JNLLSTRLSM",placeholder: "JNLLSTRLSM",options: [
						{id:"I", value:"Individu"},
						{id:"K", value:"Kelompok"}
					]},
					{view: "text",	name: "BLAWLTRLSM",	placeholder: "BLAWLTRLSM",},
					{view: "text",	name: "BLAKHTRLSM",	placeholder: "BLAKHTRLSM",}
				]
			}
			]
		}
	]
}

var form2={
	view: "form",
	id: "formTrskr",
	dataFeed: apps.ApiProvider+"/api/trlsm",
	scroll: true,
	elements: [
		{view: "text",	name: "NIMHSTRLSM",	label: "NIMHSTRLSM",labelWidth: 100,readonly:true},
		{view:"combo", id:"NODS1TRLSM", name:"NODS1TRLSM", label:"NODS1TRLSM", labelWidth:100, options:{
			body:{yCount:5}
		}},	
        {view:"combo", id:"NODS2TRLSM", name:"NODS2TRLSM", label:"NODS2TRLSM", labelWidth:100, options:{
        	body:{yCount:5}
        }},	
		{view: "textarea",	name: "JUDULTRSKR",	label: "JUDULTRSKR",labelWidth: 100}
	]
}

var ui_scheme = {
	
    rows:[
    {
    	margin:10,
    	type: "material",
    	cols:[
    		search,
    		{
    			gravity:2.2,
    			rows: [
    			{
    				view: "tabbar", id:'tabbar', multiview: true,
    				value: 'formTrlsm',optionWidth: 130,
    				options:[
    				{id: "formTrlsm", value: "Main"},
    				{id: "formTrskr", value: "Data Skripsi-TA"},
    				]
    			},
    			{
    				cells:[
    				form,
    				form2
    				]
    			},
    			{
    				view: "form",
    				css: "highlighted_header header6",
    				paddingX:5,
    				paddingY:5,
    				height:40,
    				cols:[
    				{ view: "button",css:"button_success button_raised", label: "Save", width: 90, click: function() {
    					/*var values = $$("formTrlsm").getValues();
    					var url = apps.ApiProvider+"/api/trlsm/save";
    					var message = "Data status mahasiswa is saved";

    					if ($$("formTrskr").isVisible() ){
    						url = apps.ApiProvider+"/api/trlsm/save";
    						values = $$("formTrskr").getValues();
    						message = "Data Skripsi is saved";
    					}

    					webix.ajax().sync().post(url, values, function() {
    						webix.message({ type:"debug", text:message, expire:3000});
    					});*/
						
						var id = $$("listmsmhs").getSelectedId();
						if (id =='') {
							webix.message({ type:"error", text:"Please select one", expire:3000});
						}

						if ($$("formTrlsm").isVisible() ){
    						$$("formTrlsm").save();
    					}

    					if ($$("formTrskr").isVisible() ){
    						$$("formTrskr").save();
    					}

    				}},
    				{ view: "button", css: "button_raised", label: "Reset", width: 90, click:function(){
    					if ($$("formTrskr").isVisible() )
    						$$('formTrskr').clear();
    					else
    						$$('formTrlsm').clear();
    				}},
    				{},
    				{ view: "button", css: "button_danger", label: "Delete", width: 90, click:function(){
						var id = $$("listmsmhs").getSelectedId();
						if (id =='') {
							webix.message({ type:"error", text:"Please select one", expire:3000});
						}else{
							webix.confirm({
								title: "Delete",
								text: "If you delete, the system also deletes the data TA / thesis",
								callback: function(res) { 
									if (res) {
										storeMsmhs.remove(id);
						            }
						        }
						    });
						}
					}},
    				]
    			}
    			]
    		}
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
		$$("listmsmhs").sync(storeMsmhs);
		$$("formTrlsm").bind($$("listmsmhs"));
		$$("formTrskr").bind($$("listmsmhs"));
		$$("NODS1TRLSM").getPopup().getList().sync(storeTbdos);
		$$("NODS2TRLSM").getPopup().getList().sync(storeTbdos);
	}
};

});