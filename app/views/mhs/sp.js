define([
	"apps",
],function(apps){

function startCompare(value, filter){
    value = value.toString().toLowerCase();
    filter = filter.toString().toLowerCase();
    return value.indexOf(filter) !== -1;
}

var title1 = {
	view: "toolbar",
	css: "highlighted_header header3",
	paddingX:5,
	paddingY:5,
	height:40,
	cols:[
		{
			template: "<span class='webix_icon fa-star-o'></span>Matakuliah Nilai kurang dari B", "css": "sub_title2", borderless: true
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
			template: "<span class='webix_icon fa-star-o'></span>Matakuliah yang ulang", "css": "sub_title2", borderless: true
		},
		{ view: "button", type: "iconButton", css:"button_transparent", icon: "print", label: "Cetak", width: 100, click:function(){
			$$("tbkmksms").exportToPDF("/preview/krsher");
		}}
	]
};

var grid1 = {
	id:"trnlmDatamhs",
	view:"datatable",
	url: apps.ApiProvider+"/api/transkrip/her?filter[NIMHSTRNLM]="+apps.storeInit.nim,
	drag:"source",
	select:"multiselect",
	columns:[
		{id:"NO", header:"No", width:50},
		{id:"SEMESTBKMK", header:["SMT", {content:"selectFilter"} ], sort:"string",  width:70},
		{id:"KDKMKTRNLM", header:["Kode", {content:"textFilter", compare:startCompare} ], sort:"string",  width:100},
		{id:"NAKMKTBKMK", header:["Matakuliah", {content:"textFilter", compare:startCompare} ], sort:"string",  width:250},
		{id:"SKSMKTBKMK", header:"SKS", sort:"string",  width:50,},
		{id:"NLAKHTRNLM", header:"Nilai", sort:"string", width:70 },
		{id:"NMDOSTBDOS", header:"Dosen", sort:"string", width:240 },
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
	url: apps.ApiProvider+"/api/krsher?filter[THSMSTRNLMHER]="+apps.storeInit.thnSmt+"&filter[NIMHSTRNLMHER]="+apps.storeInit.nim, //from thsmt aktif
	save: {
		updateFromResponse: true,
		url: apps.ApiProvider+"/api/krsher/save",
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
	drag:"target",
	columns:[
		{ id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon fa-trash-o text_danger'></span>"},
		{ id:"THSMSTRNLMHER", header:["THSMS",{content:"selectFilter"}], width:80 },
		{ id:"KDKMKTRNLMHER", header:["KDKMK",{content:"textFilter"}], width:90},
		{ id:"NAKMKTBKMK", header:["NAMA MK",{content:"textFilter"}], width:200 },
		{ id:"SKSMKTBKMK", header:"SKS", width:50},
		{ id:"SEMESTBKMK", header:"SMT", width:50,},
		{ id:"KELASTRNLMHER", header:"KLS", width:50, fillspace:1},
	],
	onClick:{
		webix_icon:function(e,id,node){
			webix.confirm({
				text:"The Data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
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
				record["THSMSTRNLMHER"] = apps.storeInit.thnSmt;
				record["KDKMKTRNLMHER"] = record["KDKMKTRNLM"];
				record["KELASTRNLMHER"] = '01';

				context.from.copy(context.source[i],context.start,this);
				context.from.addRowCss(context.source[i],"bg-info");	
			}
			return false;
		},
		onAfterAdd : function(id, index){
			// console.log(index);
			this.addRowCss(id,"bg-info");	
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