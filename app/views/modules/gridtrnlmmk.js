define(function(){
return {
	id:"trnlmDatamk",
	view:"datatable", select:true, editable:true,
	dataFeed: "http://localhost/core/api/trnlm",
	save:"http://localhost/core/api/trnlm/save",
	select:"cell",
	navigation:true,
	width:750,
	columns:[
		{id:"NO", header:"No", width:50},
		{id:"NIMHSTRNLM", header:["NIM",   {content:"textFilter"} ], sort:"string",  width:200},
		{id:"NMMHSMSMHS", header:["Nama",  {content:"textFilter"} ], sort:"string",  width:340},
		{id:"KELASTRNLM", header:["Kelas", {content:"selectFilter"} ], sort:"string",  width:60},
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
}
	
});