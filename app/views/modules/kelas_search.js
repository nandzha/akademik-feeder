define(function(){
	return {
		view:"datatable",
		id:"listmk",
		columns:[
/*			{ id:"id_smt", width:60, header:[{
				content:"serverFilter", placeholder:"Smt",
			}], tooltip:"#nm_smt#"},*/
			{ id:"smt", width:40,header:[{
				content:"serverFilter", placeholder:"Smt",
			}]},
			{ id:"nm_kls", width:40,header:[{
				content:"serverFilter", placeholder:"Kls",
			}]},
			{ id:"kode_mk", width:80,header:[{
				content:"serverFilter", placeholder:"Kode MK",
			}]},
			{ id:"nm_mk", width:200,header:[{
				content:"serverFilter", placeholder:"Nama MK",
			}]},
			{ id:"sks_mk", width:50,header:[{
				content:"serverFilter", placeholder:"sks",
			}]},
		],
		select:"row",
		tooltip:true,
		url:"./kelaslst"
	}
});
