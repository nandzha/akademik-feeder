define([
	"apps",
	"views/dash/dashline",
	"views/dash/mahasiswa",
	"views/dash/sebaran_nilai",
	"views/dash/penghasilan_ortu",
],function(apps,dashline, mahasiswa, sebaran_nilai, penghasilan_ortu){

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
						mahasiswa,
						{
							height: 220,
							type: "wide",
							cols: [
								sebaran_nilai,
								penghasilan_ortu
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
