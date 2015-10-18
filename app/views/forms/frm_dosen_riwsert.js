define(function(){
	return {
		view: "form",
		id: "frm_dosen_riwsert",
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
			{ view:"text", name:"no_peserta", label: "no_peserta", labelWidth:100},
			{ view:"text", name:"bid_studi", label: "bid_studi", labelWidth:100},
			{ view:"combo", name:"id_jns_sert", label: "id_jns_sert", labelWidth:100, options:[
				{ id:"1",value:"Sertifikasi Dosen"}
			]},
			{ view:"text", name:"thn_sert", label: "thn_sert", labelWidth:100},
			{ view:"text", name:"no_sk_sert", label: "no_sk_sert", labelWidth:100},
			]
		}
		],
		dataFeed:"./riwsert/data"
	}
});