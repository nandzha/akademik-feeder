define([
	"apps",
	"views/forms/absenform",
	"views/modules/dataProcessing"
],function(apps, absenform, handleProcessing){

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

	var button = {
		padding:5,
		cols:[
    		{},
    		{ view: "button",type: "iconButton", icon: "print", css: "button_success button_raised", label: "Cover", width: 110, height:35, click:function(){
				var form = $$('formAbsen');
				var values = form.getValues();

				if(form.isDirty()){
					$$("trnlmDatamk").exportToPDF("/preview/coverabsen?"+paramsBuilder(values));
				};
			}},
			{ view: "button",type: "iconButton", icon: "print", css: "button_raised", label: "Absen", width: 110, height:35, click:function(){
				var form = $$('formAbsen');
				var values = form.getValues();

				if(form.isDirty()){
					$$("trnlmDatamk").exportToPDF("/preview/absen?"+paramsBuilder(values));
				};
			}}
    	]
	}

	var ui_scheme = {
		type: "line",
		view:"scrollview",
        id:"dashboard",
        scroll:"y", //vertical scrolling
        body:{
			rows:[
				absenform,
				button
			]
		}
	};

	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    },
	    onInit:function(){}
	};

});
