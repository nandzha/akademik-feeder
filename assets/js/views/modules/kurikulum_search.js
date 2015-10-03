define(function(){
	return {
		view:"datatable",
		id:"list_kurikulum",
		columns:[
			{ id: "nm_kurikulum_sp", width:342,header:[{
				content:"serverFilter", placeholder:"Nama Kurikulum",
			}]}
		],
		select:"row",
		url:"./kurikulumlst"
	}
});