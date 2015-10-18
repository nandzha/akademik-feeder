define([
	"apps",
	"views/modules/dashline",
	"views/modules/visitors",
	"views/modules/orders",
	"views/modules/messages",
	"views/modules/revenue",
	"views/modules/tasks",
	"views/modules/map"
],function(apps,dashline, visitors, orders, messages, revenue, tasks, map){
	
	var ui_scheme = {
		type: "line",
		view:"scrollview",
        id:"dashboard",
        scroll:"y", //vertical scrolling
        body:{
			rows:[
				dashline,
				{
					type: "space",
					rows:[
						{
							height: 220,
							type: "wide",
							cols: [
								visitors,
								orders
							]
						},
						{
							type: "wide",
							cols: [
								messages,
								revenue

							]
						},
						{
							type: "wide",
							cols: [
								tasks,
								map
							]
						}
					]

				}
			]
		}
	};

	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    }
	};

});