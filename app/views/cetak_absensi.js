define([
	"apps",
	"views/modules/kelas_search",
	"views/modules/dataProcessing"
],function(apps, searchKelas, handleProcessing){

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

	var form_absen = {
		view:"form",
		id:"form_absen",
		dataFeed : "./absensi/data",
		scroll:true,
		elements:[
			{ view:"text", name: "nm_kls", label:"Kelas", labelWidth: 100, disabled:true},
			{ view:"text", name:"kode_mk", label:"Kode MK",labelWidth: 100, disabled:true},
			{ view:"text", name:"nm_mk", label:"Nama MK",labelWidth: 100, disabled:true},
			{ view:"text", name:"peserta_kelas", label:"Jml Mhs",labelWidth: 100, disabled:true},
			{ view:"text", name:"dosen_mengajar", label:"Jml Dosen",labelWidth: 100, disabled:true},
			{ view:"select", name: "id_reg_ptk",id: "id_reg_ptk", label:"Dosen", labelWidth: 100, options:[] },
		]
	};


	var button = {
		paddingX:5,
	    paddingY:5,
	    height:40,
		cols:[
    		{},
    		{ view: "button",type: "iconButton", icon: "print", css: "button_success button_raised", label: "Cover", width: 110, click:function(){
				var form = $$('form_absen');
				var values = form.getValues();

				webix.send("/twig_template/preview/coverabsen",values, 'POST', '_balnk');
			}},
			{ view: "button",type: "iconButton", icon: "print", css: "button_raised", label: "Absen", width: 110, click:function(){
				var form = $$('form_absen');
				var values = form.getValues();
				webix.send("/twig_template/preview/absen",values, 'POST', '_balnk');
			}}
    	]
	}

	var ui_scheme = {
		type: "line",
		id:"ui_cetak_absensi",
		rows:[
		{
			margin:10,
			type: "material",
			cols:[
				searchKelas,
				{
					gravity:1.5,
					rows:[
						form_absen,
						button
					]
				}
			]
		}
		]
	};

	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    },
	    onInit:function(){
	    	$$("form_absen").bind($$("listmk"), "a.id_kls");
	    	$$("id_reg_ptk").bind($$("listmk"), "$data", function(data, source){
	            if (data){
	                this.define("options","/twig_template/sugest/dosen?id_kls="+data.id_kls);
	            }
       		});
	    }
	};

});
