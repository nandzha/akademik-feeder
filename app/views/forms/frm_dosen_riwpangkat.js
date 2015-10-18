define(function(){
	return {
		view: "form",
		id: "frm_dosen_riwpangkat",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
			{ view: "combo", name: "id_sms", label: "Prodi", labelWidth: "100", suggest: {
				body:{
			        yCount:5,
					dataFeed : "/twig_template/sugest/prodi"
			    }
			}},
			{ view:"combo", name:"id_pangkat_gol", label: "id_pangkat_gol", labelWidth:100,options:[
				{ id:"1", value:"I/a"},
				{ id:"2", value:"I/b"},
				{ id:"3", value:"I/c"},
				{ id:"4", value:"I/d"},
				{ id:"5", value:"II/a"},
				{ id:"6", value:"II/b"},
				{ id:"7", value:"II/c"},
				{ id:"8", value:"II/d"},
				{ id:"9", value:"III/a"},
				{ id:"10", value:"III/b"},
				{ id:"11", value:"III/c"},
				{ id:"12", value:"III/d"},
				{ id:"13", value:"IV/a"},
				{ id:"14", value:"IV/b"},
				{ id:"15", value:"IV/c"},
				{ id:"16", value:"IV/d"},
				{ id:"17", value:"IV/e"},
				{ id:"99", value:"-"}
			]},
			{ view:"text", name:"sk_pangkat", label: "sk_pangkat", labelWidth:100},
			{ view:"datepicker", name:"tgl_sk_pangkat", label: "tgl_sk_pangkat", labelWidth:100},
			{ view:"datepicker", name:"tmt_sk_pangkat", label: "tmt_sk_pangkat", labelWidth:100},
			{ view:"text", name:"masa_kerja_thn", label: "masa_kerja_thn", labelWidth:100},
			{ view:"text", name:"masa_kerja_bln", label: "masa_kerja_bln", labelWidth:100},
			]
		}
		],
		dataFeed:"./riwpang/data"
	}
});