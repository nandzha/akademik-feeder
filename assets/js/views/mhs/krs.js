define([
	"apps",
],function(apps){

var title1 = {
	view: "toolbar",
	css: "highlighted_header header3",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{
			template: "<span class='webix_icon fa-star-o'></span>Matakuliah yang ditawarkan", "css": "sub_title2", borderless: true
		},
	]
};

var title2 = {
	view: "toolbar",
	css: "highlighted_header header2",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{
			template: "<span class='webix_icon fa-star-o'></span>Matakuliah yang diambil", "css": "sub_title2", borderless: true
		},
		{ view: "button", type: "iconButton", css:"button_transparent", icon: "print", label: "Cetak", width: 100, click:function(){
			$$("tbkmksms").exportToPDF("/preview/krs");
		}}
	]
};

var grid1 = {
	view:"datatable",
	select:"multiselect",
	id:"tbkmkmaster",
	url: apps.ApiProvider+"/api/tbkmk?filter[THSMSTBKMK]="+apps.storeInit.thnSmt, //from thsmt aktif
	drag:"source",
	columns:[
		{ id:"THSMSTBKMK",	header:["THSMS",{content:"selectFilter"}], width:80 },
		{ id:"KDKMKTBKMK",	header:["KDKMK",{content:"textFilter"}], width:100},
		{ id:"NAKMKTBKMK",	header:["NAMA MK",{content:"textFilter"}], width:200 },
		{ id:"SKSMKTBKMK",	header:"SKS", width:70 },
		{ id:"SEMESTBKMK",	header:"SMT", width:70 },
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
	url: apps.ApiProvider+"/api/krsmhs?filter[THSMSTRNLM]="+apps.storeInit.thnSmt+"&filter[NIMHSTRNLM]="+apps.storeInit.nim, //from thsmt aktif
	save: {
		updateFromResponse: true,
		url: apps.ApiProvider+"/api/krsmhs/save",
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
			}
		}
	},
	select:true,
	drag:"target",editable:true, editaction:"dblclick",
	columns:[
		{ id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon fa-trash-o text_danger'></span>"},
		{ id:"THSMSTRNLM", header:["THSMS",{content:"selectFilter"}], width:80 },
		{ id:"KDKMKTRNLM", header:["KDKMK",{content:"textFilter"}], width:90},
		{ id:"NAKMKTBKMK", header:["NAMA MK",{content:"textFilter"}], width:200 },
		{ id:"SKSMKTBKMK", header:"SKS", width:50},
		{ id:"SEMESTBKMK", header:"SMT", width:50,},
		{ id:"KELASTRNLM", header:"KLS", width:50, fillspace:1},
	],
	onClick:{
		webix_icon:function(e,id,node){
			webix.confirm({
				text:"The order will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
				callback:function(res){
					if(res){
						webix.$$("tbkmksms").remove(id);
					}
				}
			});
		}
	},
	on:{
		onBeforeDrop:function(context, ev){
			for (var i=0; i< context.source.length; i++){

				record =  context.from.getItem(context.source[i]);
				record["_THSMSTBKMK_"] = record["THSMSTBKMK"] ;
				record["THSMSTRNLM"] = apps.storeInit.thnSmt;
				record["KDKMKTRNLM"] = record["KDKMKTBKMK"];

				context.from.copy(context.source[i],context.start,this);
				context.from.addRowCss(context.source[i],"bg-info");	

				record["THSMSTBKMK"] = record["_THSMSTBKMK_"]; 
			}
			return false;
		},
		onAfterAdd : function(id, index){
			// console.log(index);
			this.addRowCss(id,"bg-info");	
		},
		onAfterEditStop : function(state, editor, ignoreUpdate){
		    if(state.value != state.old){
		    	this.addRowCss(editor.row,"bg-info");
		    }
		}
	}
};

var ui_scheme ={
    rows:[
    {
    	margin:10,
		type:"material",
    	cols:[
    	{
    		rows:[
				title1,
    			grid1
			]
    		
    	},
    	{
			rows:[
				title2,
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