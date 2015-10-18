define(function(){
	return {
		view: "form",
		id: "frm_dosen_riwpend",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
			{ view:"text", name:"bidang_studi", label:"bidang studi", labelWidth:100},
			{ view: "combo", name: "id_jenj_didik", label: "Jenjang Pendidikan", labelWidth: "100", suggest: {
				body:{
			        yCount:5,
					dataFeed : "/twig_template/sugest/jenjang"
			    }
			}},
			{ view:"text", name:"gelar", label:"gelar", labelWidth:100},
			{ view: "combo", name: "id_sp_asal", label: "PT Asal", labelWidth: "100", suggest: {
				body:{
			        yCount:5,
					dataFeed : "/twig_template/sugest/pt"
			    }
			}},
			{ view:"text", name:"fakultas", label:"fakultas", labelWidth:100},
			{ view:"text", name:"thn_lulus", label:"thn_lulus", labelWidth:100},
			{ view:"text", name:"sks_lulus", label:"sks_lulus", labelWidth:100},
			{ view:"text", name:"ipk_lulus", label:"ipk_lulus", labelWidth:100},

			]
		}
		],
		dataFeed:"./riwpend/data"
	}
});