define(function(){
	return {
	    view:"datatable",
	    id:"grd_dosen",
	    columns:[
			{ id:"nidn", width:100, header: "nidn" },
			{ id:"nm_ptk", width:200, header: "nm_ptk" },
			{ id:"jk", width:50, header: "jk" },
			{ id:"nm_stat_pegawai", width:100, header: "nm_stat_pegawai" },
			{ id:"nm_lemb", width:200, header: "nm_lemb" }
		],
	    select:"multiselect",
	    datafetch:30,
	    dataFeed : "./dosen/lst"
	}
});
