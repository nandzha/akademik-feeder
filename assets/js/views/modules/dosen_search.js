define(function(){
	return {
		view:"datatable",
		id:"listdosen",
		columns:[
			{ id:"nidn", width:100, header:[{
				content:"serverFilter", placeholder:"NIDN",
			}]},
			{ id: "nm_ptk", width:242,header:[{
				content:"serverFilter", placeholder:"Nama",
			}]}
		],
		select:"row",
		url:"./lst"
	}
});