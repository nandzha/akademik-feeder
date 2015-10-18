define(function(){
	return {
		view:"datatable",
		id:"list_semester",
		columns:[
			{ id: "id_smt", width:70,header:[{
				content:"serverFilter", placeholder:"smt",
			}]},
			{ id: "nm_smt", width:200,header:[{
				content:"serverFilter", placeholder:"Nama Semester",
			}]}
		],
		select:"row",
		url:"./smtlst"
	}
});
