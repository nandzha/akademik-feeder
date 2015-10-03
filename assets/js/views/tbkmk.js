define([
	"apps",
],function(apps){

var controls = [
	{view:"richselect", id:"prodi", value: "all", maxWidth: 400, minWidth: 400, vertical: true, labelWidth: 100, options:[
		{id:"all", value:"All"},
		{id:"61201", value: "<div class='marker prodi_61201'></div> MANAJEMEN"},
		{id:"62201", value: "<div class='marker prodi_62201'></div> AKUNTANSI"},
	],  
	label:"Filter Prodi", on:{
		onChange:function(){
			var val = this.getValue();
			if(val=="all")
				$$("tbkmkmaster").filter("#KDPSTTBKMK#","");
			else
				$$("tbkmkmaster").filter("#KDPSTTBKMK#",val);
		}
	}
	},
	{},
	{ view: "button", type: "iconButton", icon: "plus", css:"button_success button_raised", label: "Add Kurikulum", width: 180, click: function(){
		/*$$("wintbkmk").show();
		$$("formTbkmk").clear();
		$$("btsubmit").setValue("Save");*/
		$$('tbkmksms').add({
			THSMSTBKMK : apps.storeInit.thnSmt
		});
	}},
	{ view: "button", css: "button_danger", label: "Delete", width: 90, click:function(){
		var id = $$("tbkmksms").getSelectedId();

		if (typeof id == "undefined") {
			webix.message({ type:"error", text:"Please select one", expire:3000});
		}else{
			webix.confirm({
				title: "Delete",
				text: "Are you sure you want to delete the selected item?",
				callback: function(res) { 
					if (res) {
						$$("tbkmksms").remove(id);
						// store.remove(id);
					}
				}
			});
		}
	}},
];

var grid1 = {
	view:"datatable",
	select:"multiselect",
	id:"tbkmkmaster",
	url:apps.ApiProvider+"/api/tbkmk",
	drag:"source", scrollX:false,
	columns:[
		{ id:"THSMSTBKMK",	header:["THSMS",{content:"selectFilter"}], width:80 },
		{ id:"KDKMKTBKMK",	header:["KDKMK",{content:"textFilter"}], width:100},
		{ id:"NAKMKTBKMK",	header:["NAMA MK",{content:"textFilter"}], width:200 },
		{ id:"SKSMKTBKMK",	header:"SKS", width:70 },
		{ id:"SEMESTBKMK",	header:"SMS", width:70 },
	],
	on:{
		onBeforeDrag:function(context, ev){
			context.html = "<div style='padding:8px;'>";
			for (var i=0; i< context.source.length; i++){
				context.html += context.from.getItem(context.source[i]).NAKMKTBKMK + "<br>" ;
			}
			context.html += "</div>";
		}
	}
};

var grid2 = {
	view: "datatable",
	id: "tbkmksms",
	url: apps.ApiProvider+"/api/tbkmk?filter[THSMSTBKMK]="+apps.storeInit.thnSmt, //from thsmt aktif
	save: {
		updateFromResponse: true,
		url: apps.ApiProvider+"/api/tbkmk/save",
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
		}
	},
	select:"multiselect",
	drag:"target", scrollX:false, editable:true, editaction:"dblclick",
	columns:[
		{ id:"THSMSTBKMK", header:["THSMS",{content:"selectFilter"}], width:80 },
		{ id:"KDKMKTBKMK", editor:"text", header:["KDKMK",{content:"textFilter"}], width:100},
		{ id:"NAKMKTBKMK", editor:"text", header:["NAMA MK",{content:"textFilter"}], width:200 },
		{ id:"SKSMKTBKMK", editor:"text", header:"SKS", width:50},
		{ id:"SEMESTBKMK", editor:"text", header:"SMS", width:50, fillspace:1},
	],
	on:{
		onBeforeDrop:function(context, ev){
			for (var i=0; i< context.source.length; i++){

				record =  context.from.getItem(context.source[i]);
				record["_THSMSTBKMK_"] = record["THSMSTBKMK"] ;
				record["THSMSTBKMK"] = apps.storeInit.thnSmt;

				context.from.copy(context.source[i],context.start,this);
				context.from.addRowCss(context.source[i],"bg-info");	

				record["THSMSTBKMK"] = record["_THSMSTBKMK_"]; 
			}
			return false;
		},
		onAfterAdd : function(id, index){
			this.addRowCss(id,"bg-info");	
			this.sort("#id#", "desc", "int");
		},
		onAfterEditStop : function(state, editor, ignoreUpdate){
		    if(state.value != state.old){
		    	this.addRowCss(editor.row,"bg-info");
		    }
		},
	}
};

var ui_scheme ={
	type:"line",
    rows:[
    {
    	type:"space",
    	cols:controls
    },
    {
    	margin:10,
		type: "material",
    	cols:[
    	{
    		rows:[
	    		{
					template: "<span class='webix_icon fa-star-o'></span>Referensi Matakuliah", "css": "highlighted_header header3", borderless: true, height:40
				},
	    		grid1,
    		]
    	},
    	{
    		rows:[
	    		{
					template: "<span class='webix_icon fa-star-o'></span>Matakuliah Aktif", "css": "highlighted_header header2", borderless: true, height:40
				},
	    		grid2
    		]
    	}
    	]
    }
    ]
}

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){
		// $$("tbkmksms").sync(store);
	}
};
});