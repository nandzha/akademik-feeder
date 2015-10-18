define(function(){
	return {
		view: "form",
		id: "frm_kursem",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
				{ view:"text", name:"nm_kurikulum_sp", label: "nm_kurikulum_sp", labelWidth:100},
				{ view:"text", name:"jml_sem_normal", label: "jml_sem_normal", labelWidth:100},
				{ view:"text", name:"jml_sks_lulus", label: "jml_sks_lulus", labelWidth:100},
				{ view:"text", name:"jml_sks_wajib", label: "jml_sks_wajib", labelWidth:100},
				{ view:"text", name:"jml_sks_pilihan", label: "jml_sks_pilihan", labelWidth:100},
				{ view: "combo", name:"id_sms", label: "Prodi", labelWidth: "100", suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/prodi"
				    }
				}},
				{ view: "combo", name: "id_jenj_didik", label: "Jenjang Pendidikan", labelWidth: "100", suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/jenjang"
				    }
				}},
				{ view: "combo", name: "id_smt_berlaku", label: "id_smt_berlaku", labelWidth: "100", suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/smt"
				    }
				}},
			]
		}
		]
	}
});
