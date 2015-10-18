define(function(){
return {
	id:"trnlmDatamhs",
	view:"datatable", select:true, editable:true,
	dataFeed: "http://localhost/core/api/trnlm",
	save:"http://localhost/core/api/trnlm/save",
	select:"cell",
	navigation:true,
	width:750,
	columns:[
		{id:"NO", header:"No", width:50},
		{id:"KDKMKTRNLM", header:["Kode", {content:"textFilter"} ], sort:"string",  width:100},
		{id:"NAKMKTBKMK", header:["Matakuliah", {content:"textFilter"} ], sort:"string",  width:400},
		{id:"SKSMKTBKMK", header:"SKS", sort:"string",  width:100,},
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

});