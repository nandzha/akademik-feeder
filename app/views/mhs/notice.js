define([
	"apps",
], function(apps){

var issues_grid = {
	view:"list", id:"issues-grid", borderless:true,
	url: apps.ApiProvider+"/notice/load",
	scheme:{
		$init:function(obj){
			if (obj.id % 2 === 0) obj.$css = "bg_gray";
		}
	},
	type:{ 
		height:100,
		template:"html->issue_record"
	},
	on:{
		onItemClick:function(id){ 
			console.log(id);
		},
	},
	header:false
};

var ui_scheme = {
	type:"material",
    rows:[
    issues_grid
    ],
};


return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){

	}
};

});