define(function(){
	return {
		view:"datatable",
		id:"listmsmhs",
		columns:[
			{ id:"nipd", width:100, header:[{
				content:"serverFilter", placeholder:"Nim",
			}]},
			{ id: "nm_pd", width:242,header:[{
				content:"serverFilter", placeholder:"Nama",
			}]}
		],
		select:"row",
		url:"./lst"
	}
});