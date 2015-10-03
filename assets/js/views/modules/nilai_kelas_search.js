define(function(){
	return {
		view:"datatable",
		id:"listmk",
		columns:[
			{ id:"id_smt", width:60, header:[{
				content:"serverFilter", placeholder:"Smt",
			}]},
			{ id:"kode_mk", width:80,header:[{
				content:"serverFilter", placeholder:"Kode MK",
			}]},
			{ id:"nm_mk", width:250,header:[{
				content:"serverFilter", placeholder:"Nama MK",
			}]},
			{ id:"nm_kls", width:50,header:[{
				content:"serverFilter", placeholder:"Kls",
			}]}
		],
		select:"row",
		url:"./nilailst"
	}
});