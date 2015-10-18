define(function(){
		//grid kelas berserta matakuliah yang di tawarkan dan belum penuh
	return {
	    view:"datatable",
	    id:"grd_mk_kls",
	    columns:[
			{ id: "id_smt", width:100, header:["smt",{content:"serverFilter"}], tooltip:"#nm_smt#", },
	        { id: "kode_mk",width:100, header:["kode mk",{content:"serverFilter"}]},
	        { id: "nm_mk", width:300, header:["nm_mk",{content:"serverFilter"}]},
	        { id: "nm_kls", header:"kls", width:80},
	        { id: "sks_mk", header:"sks", width:80}
	    ],
	    select:"multiselect",
	    tooltip:true,
	    datafetch:30,
	    dataFeed : "./mksmt/data", // kelas yang di tawarkan
	}
});
