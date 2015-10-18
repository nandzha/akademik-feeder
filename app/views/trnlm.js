define([
	"apps",
	"views/modules/msmhssearch",
	"views/modules/tbkmksearch",
	"views/forms/absenform",
], function(apps,msmhsSearch,tbkkSearch,forms){


var storeMsmhs = new webix.DataCollection({
	url:apps.ApiProvider+"/api/msmhs"
});

var storeTbkmk = new webix.DataCollection({
	url:apps.ApiProvider+"/api/tbkmk/list"
});
/*
var storeTrnlm = new webix.DataCollection({
	url:apps.ApiProvider+"/api/trnlm"
});


var dpTrnlm = new webix.DataProcessor({
    updateFromResponse:true, 
    autoupdate:true,
    master: storeTrnlm,
    url:apps.ApiProvider+" */ 

function startCompare(value, filter){
    value = value.toString().toLowerCase();
    filter = filter.toString().toLowerCase();
    return value.indexOf(filter) !== -1;
}

function paramsBuilder(obj){
	var str = "";
	for (var key in obj) {
		if (str != "") {
		    str += "&";
		}
		str += key + "=" + obj[key];
	}
	return str;
}

var grid1 = {
	id:"trnlmDatamhs",
	view:"treetable", 
	editable:true,
	navigation:true,
	gravity:2.2,
	scheme:{
		$group:{
			by:"SEMESTBKMK",
			map:{
				SKSMKTBKMK:["SKSMKTBKMK", "sum"],
				NAKMKTBKMK:["SEMESTBKMK"]
			}
		},
		$sort:{ by:"value", dir:"desc" }
	},
	ready:function(){
		this.open(this.getFirstId());
	},
	dataFeed: apps.ApiProvider+"/api/trnlm",
	save: {
		updateFromResponse: true,
		url: apps.ApiProvider+"/api/trnlm/save",
		on:{
			onAfterSync: function(id, text, data){
				var res = data.json();
				if (res.status){
					webix.message({ type:"default", 
						text: res.messages.data+"<br>"+res.messages.status +" in "+res.messages.operation, 
						expire:5000
					});
				}

				if (! res.status){
					webix.modalbox({
						type:"alert-error",
						width: "500px",
						title:"Error Messages",
						text:res.messages.NLAKHTRNLM,
						buttons:["Ok"],
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
		{id:"KDKMKTRNLM", header:["Kode", {content:"textFilter", compare:startCompare} ], sort:"string",  width:100},
		{id:"NAKMKTBKMK", header:["Matakuliah", {content:"textFilter", compare:startCompare} ], sort:"string",  width:400,
			template:function(obj, common){
				if (obj.$group) return common.treetable(obj, common) +"Semester "+obj.NAKMKTBKMK;
				return obj.NAKMKTBKMK;
			}
		},
		{id:"SEMESTBKMK", header:"SMS", sort:"string",  width:75,},
		{id:"SKSMKTBKMK", header:"SKS", sort:"string",  width:75,},
		{id:"NLAKHTRNLM", header:"Nilai", editor:"text", sort:"string", width:75 }
	],
	on:{
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

var grid2={
	id:"trnlmDatamk",
	view:"datatable", select:true, editable:true,
	dataFeed: apps.ApiProvider+"/api/trnlm",
	save: {
		updateFromResponse: true,
		url: apps.ApiProvider+"/api/trnlm/save",
		on:{
			onAfterSync: function(id, text, data){
				var res = data.json();
				if (res.status){
					webix.message({ type:"default", 
						text: res.messages.data+"<br>"+res.messages.status +" in "+res.messages.operation, 
						expire:5000
					});
				}

				if (! res.status){
					webix.modalbox({
						type:"alert-error",
						width: "500px",
						title:"Error Messages",
						text:res.messages,
						buttons:["Ok"],
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
			},
			onAfterEditStop : function(state, editor, ignoreUpdate){
			    if(state.value != state.old){
			    	this.addRowCss(editor.row,"bg-info");
			    }
			}
		}
	},
	select:"cell",
	navigation:true,
	gravity:2.2,
	columns:[
		{id:"NO", header:"No", width:50},
		{id:"THSMSTRNLM", header:["THSMS",   {content:"selectFilter"} ], sort:"string",  width:80},
		{id:"NIMHSTRNLM", header:["NIM",   {content:"textFilter", compare:startCompare} ], sort:"string",  width:120},
		{id:"NMMHSMSMHS", header:["Nama",  {content:"textFilter", compare:startCompare} ], sort:"string",  width:300},
		{id:"KELASTRNLM", header:["Kelas", {content:"selectFilter"} ], sort:"string",  width:80},
		{id:"NLAKHTRNLM", header:"Nilai", editor:"text", sort:"string", width:100 }
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
		}
    }
};

var ui_scheme = {
	type:"line",
    rows:[
    {
    	type:"space",
    	cols:[
    		{
		    	view:"segmented", id:'tabbar', value: 'msmhs', 
		    	multiview:true, optionWidth:141,  
		    	padding: 5, options: [
			    	{ value: 'Mahasiswa', id: 'msmhs'},
			    	{ value: 'Matakuliah', id: 'tbkmk'}
		    	],
		    	on:{
		    		onItemClick : function(id, e){
		    			$$("printAbsen").disable();
					    if (e.target.innerText == 'Matakuliah')
					    	$$("printAbsen").enable();
					}
		    	}

    		},
    		{
    			view:"richselect", id:"order_filter", value: "all", maxWidth: 400, 
    			minWidth: 250, vertical: true, labelWidth: 100, options:[
		    		{id:"all", value:"All"},
		    		{id:"61201", value: "<div class='marker prodi_61201'></div> MANAJEMEN"},
		    		{id:"62201", value: "<div class='marker prodi_62201'></div> AKUNTANSI"},
	    		],  
	    		label:"Filter Prodi", on:{
	    			onChange:function(){
	    				var val = this.getValue();
	    				if(val=="all"){
	    					$$("listmsmhs").filter("#KDPSTMSMHS#","");
	    					$$("listtbkmk").filter("#KDPSTTBKMK#","")
	    				}
	    				else{
	    					$$("listmsmhs").filter("#KDPSTMSMHS#",val);
	    					$$("listtbkmk").filter("#KDPSTTBKMK#",val)
	    				}
	    			}
	    		}
	    	},
	    	{ view: "button", id:"printAbsen", type: "iconButton", icon: "print", label: "Cetak Absensi", width: 180, popup:"popAbsen", disabled:true}
    	]
    },
    {   
    	id:"mymulti",
    	cells:[
    	{
    		margin:10,
    		id:"msmhs",
    		type:"material",
    		cols: [
    		msmhsSearch, 
    		{
    			gravity:2.2,
    			rows:[
    			grid1,
    			{
    				margin:10,
    				cols:[
						{},
						{ view: "button", type: "iconButton", icon: "print", label: "KHS", width: 100, popup:"popKHS"},
						{ view: "button", type: "iconButton", icon: "print", label: "Transkrip", width: 150, click:function(){
							var id = $$("listmsmhs").getSelectedId();
							if (id =='') {
								webix.message({ type:"error", text:"Please select one", expire:3000});
							}else{
								$$("trnlmDatamhs").exportToPDF("/preview/transkrip?nim="+id);
		                    }
						}}
    				]
    			}
    			]
    		}
    		],
    	},
    	{
    		margin:10,
    		id:"tbkmk",
    		type:"material",
    		cols: [tbkkSearch, grid2],
    	},
    	]
    }
    ],
};

var ui_popup = {
	view:"popup",
	id:"popAbsen",
    head:"Submenu",
	width:450,
	height:280,
	body: {
    	rows:[
    		forms,
	    	{
	    		padding:5,
		    	cols:[
		    		{},
		    		{ view: "button",type: "iconButton", icon: "print", css: "button_success button_raised", label: "Cover", width: 110, height:35, click:function(){
						var form = $$('formAbsen');
						var values = form.getValues();

						if(form.isDirty()){
							
							if ($$("trnlmDatamk").isVisible()) {
    							$$("trnlmDatamk").exportToPDF("/preview/coverabsen?"+paramsBuilder(values));
							}else{
								webix.message({ type:"error", text:"Please Tab Matakuliah", expire:3000});
							}

		                    this.getTopParentView().hide(); //hide window
						};
					}},
					{ view: "button",type: "iconButton", icon: "print", css: "button_raised", label: "Absen", width: 110, height:35, click:function(){
						var form = $$('formAbsen');
						var values = form.getValues();

						if(form.isDirty()){

							if ($$("trnlmDatamk").isVisible()) {
    							$$("trnlmDatamk").exportToPDF("/preview/absen?"+paramsBuilder(values));
							}else{
								webix.message({ type:"error", text:"Please Tab Matakuliah", expire:3000});
							}

		                    this.getTopParentView().hide(); //hide window
						};
					}}
		    	]
	    	}
    	],
    }
}

var ui_popup2 = {
	view:"popup",
	id:"popKHS",
    head:"Submenu",
	width:450,
	height:280,
	body: {
    	rows:[
    		{
    			view:"form",
    			id:"formKHS",
    			margin:10,
    			elements:[
    				{
    					cols:[
	    					{view: "select", name: "smt",options: [
		    					{id:"01", value: "Semester 1"},
		    					{id:"02", value: "Semester 2"},
		    					{id:"03", value: "Semester 3"},
		    					{id:"04", value: "Semester 4"},
		    					{id:"05", value: "Semester 5"},
		    					{id:"06", value: "Semester 6"},
		    					{id:"07", value: "Semester 7"},
		    					{id:"08", value: "Semester 8"}
		    					
	    					]},
	    					{ view: "button",type: "iconButton", icon: "print", label: "Cetak", width: 110, height:35, click:function(){
	    						var id = $$("listmsmhs").getSelectedId();
	    						var form = $$('formKHS');
	    						var values = form.getValues();
	    						values.nim = id;
								if (id =='') {
									webix.message({ type:"error", text:"Please select one", expire:3000});
								}else{
		    						if(form.isDirty()){
		    							$$("trnlmDatamhs").exportToPDF("/preview/khs?"+paramsBuilder(values));
			                    		this.getTopParentView().hide(); //hide window
			                		};
		                		}
		            		}}
    					]
    				}
    			]
    		}
    	],
    }
}


return {
    ui: function() {
		apps.setUiScheme = ui_scheme;
    	apps.webix({ui_popup, ui_popup2});
    },
    onInit: function(){
		$$("listmsmhs").sync(storeMsmhs);
		$$("listtbkmk").sync(storeTbkmk);
		
		$$("trnlmDatamhs").bind( $$("listmsmhs"),"NIMHSTRNLM");
		$$("trnlmDatamk").bind( $$("listtbkmk"),"KDKMKTRNLM");

	}
};

});