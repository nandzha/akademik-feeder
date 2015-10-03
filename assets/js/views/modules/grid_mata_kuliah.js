define(function(){
	return {
	    view:"datatable",
	    id:"grd_mata_kuliah",
	    columns:[
	        { id:"nm_prodi", header: "Prodi", width: 250},
			{ id:"kode_mk", header: "kode_mk", width: 100},
	        { id:"nm_mk", header: "nm_mk", width: 250},
	        { id:"sks_mk", header: "sks_mk", width: 80},
	        { id:"nm_jns_mk", header: "nm_jns_mk", width: 100},
	        { id:"nm_kel_mk", header: "nm_kel_mk", width: 100},
	    ],
	    select:"multiselect",
	    datafetch:30,
	    dataFeed : "./kuliah/mklst",
	}
});
