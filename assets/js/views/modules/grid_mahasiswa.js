define(function(){
	return {
	    view:"datatable",
	    id:"grd_mhs",
	    columns:[
	        { id: "nipd", width:200, header: "nipd"},
			{ id: "nm_pd", width:250, header: "nm_pd"},
			{ id: "jk", width:50, header: "jk"},
			{ id: "nm_stat_mhs", width:150, header: "status"}
	    ],
	    select:"multiselect",
	    datafetch:30,
	    dataFeed : "./mahasiswa/lst"
	}
});
