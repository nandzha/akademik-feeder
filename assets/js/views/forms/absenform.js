define(["apps"], function(apps){
	return {
		view: "form",
		id: "formAbsen",
		scroll: true,
		margin:10,
		elements: [
			{view: "select", name: "prodi", label: "Prodi", labelWidth: "100",options: [
				{id:"61001", value: "MANAJEMEN"},
				{id:"62201", value: "AKUNTANSI"},
			]},
			{view: "combo", name: "mk",	label: "Mata Kuliah", labelWidth: "100", suggest: {
				body:{
			        yCount:5,
					dataFeed : apps.ApiProvider+"/api/tbkmk/sugest"
			    }
			}},
			{view: "combo", name: "dos", label: "Nama Dosen", labelWidth: "100", suggest:{
				body : {
					yCount:5,
					dataFeed : apps.ApiProvider+"/api/tbdos/sugest"	
				}
			}},

			{
				cols:[
					{view: "text", name: "thsmst",	label: "Thn.Smst", labelWidth: "100", placeholder:"20141"},
					{view: "select", name: "kls",label: "Kelas", labelWidth: "70",options: [
						{id:"R", value: "Reguler"},
						{id:"N", value: "Non Reguler"},
						{id:"K", value: "Kerjasama"},
					]},
				]
			}
		]
	}
});