define(function(){
	return {
		view: "form",
		id: "frm_subtansi_mk",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
				{ view: "combo", id:"id_sms", name:"id_sms", label: "Prodi", labelWidth: "100", suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/prodi"
				    }
				}},
				{ view:"text", name:"nm_subst", label: "nm_subst", labelWidth:100},
				{ view:"combo", name:"id_jns_subst", label: "id_jns_subst", labelWidth:100, options:[
					{id:"1", value:"Dummy Jenis Substansi"}
				]},
				{ view:"text", name:"sks_mk", label: "sks_mk", labelWidth:100},
				{ view:"text", name:"sks_tm", label: "sks_tm", labelWidth:100},
				{ view:"text", name:"sks_prak", label: "sks_prak", labelWidth:100},
				{ view:"text", name:"sks_prak_lap", label: "sks_prak_lap", labelWidth:100},
				{ view:"text", name:"sks_sim", label: "sks_sim", labelWidth:100},
			]
		}
		]
	}
});
