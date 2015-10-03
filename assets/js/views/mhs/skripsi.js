define([
	"apps",
], function(apps){

var form={
	view: "form",
	id: "formTrskr",
	scroll: true,
	elements: [
		{view: "text",	name: "TOPIKTRSKR",	label: "Topik", labelWidth: 100},
		{view: "textarea",	name: "JUDULTRSKR",	label: "Judul TA",labelWidth: 100},
		{
			cols:[
				{},

				{ view: "button", id:"btsubmit", css: "button_success button_raised", label: "Save", width: 90, height:30, click:function(){
					var form = $$('formTrskr');
					var values = form.getValues();

					if(form.isDirty()){
						if(!form.validate())
							return false;

						webix.ajax().post(apps.ApiProvider+"/api/trskr/save", values, function(t,res) {
							var res = res.json();

							webix.message({ type:"default", 
								text: res.messages.data+"<br>"+res.messages.status +" in "+res.messages.operation, 
								expire:5000
							});									
						});
	                };
	            }},

				{ view: "button", css: "button_raised", width:90, height:30, label: "Reset", click:function(){
					if ($$("formTrskr").isVisible() )
						$$('formTrskr').clear();
				}},
			]
		}
	]
}

var grid1 = {
	id:"trnlmDatamhs",
	view:"datatable", select:true,
	url: apps.ApiProvider+"/api/trskr",
	columns:[
		{id:"NO", header:"No", width:50},
		{id:"THSMSTRSKR", header:["Tahun", {content:"selectFilter"} ], sort:"string",  width:80},
		{id:"NMPSTMSPST", header:["Prodi", {content:"textFilter"} ], sort:"string",  width:200},
		{id:"NIMHSTRSKR", header:["NIM", {content:"textFilter" } ], sort:"string",  width:150},
		{id:"NMMHSMSMHS", header:["Nama", {content:"textFilter" } ], sort:"string",  width:200},
		{id:"JUDULTRSKR", header:["Judul", {content:"textFilter" } ], sort:"string",  width:450},
	],
	on:{
        "data->onStoreUpdated":function(){
            this.data.each(function(obj, i){
                obj.NO = i+1;
            })
        },
        onBeforeLoad:function(){
			this.showOverlay("Loading...");
		},
		onAfterLoad:function(){
			if (!this.count()){
				webix.extend(this, webix.OverlayBox);
				this.showOverlay("<div style='margin:75px; font-size:20px;'>There is no data</div>");
			}else{
				this.hideOverlay();
			}
		},
		onAfterEditStop : function(state, editor, ignoreUpdate){
		    if(state.value != state.old){
		    	this.addRowCss(editor.row,"bg-info");
		    }
		},
    }
};

var ui_scheme = {
	type: "material",
    rows:[
	    form,
	    grid1
    ],
};


return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){
    	$$('formTrskr').setValues({ 
			webix_operation : "insert",
			NIMHSTRSKR : "2014100003"
		}); 
	}
};

});