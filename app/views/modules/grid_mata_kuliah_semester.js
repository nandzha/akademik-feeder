define(function(){
	return {
	    view:"datatable",
	    id:"grid_mata_kuliah_semester",
	    columns:[
			{ id:"jml_kls", width: 100, header:"Kelas", editor:"select", options:[1,2,3,4,5,6,7,8]},
			{ id:"smt", width: 80, header:[{
				content:"serverFilter", placeholder:"SMT",
			}]},
	    	{ id:"nm_kurikulum_sp", width: 150, header:[{
				content:"serverFilter", placeholder:"Nama Kurikulum",
			}]},
			{ id:"kode_mk", width: 100, header:[{
				content:"serverFilter", placeholder:"Kode MK",
			}]},
	        { id:"nm_mk", width: 250, header:[{
				content:"serverFilter", placeholder:"Nama MK",
			}]},
	        { id:"sks_mk", width: 80, header:[{
				content:"serverFilter", placeholder:"SKS MK",
			}]},

	    ],
	    select:"multiselect", editable:true,
	    datafetch:30,
	    dataFeed : "./kurikulum/data"
	}
});
