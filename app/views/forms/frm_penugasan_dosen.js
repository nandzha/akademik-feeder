define(function(){
	return {
		view: "form",
		id: "frm_penugasan_dosen",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
				{ view: "combo", name: "id_thn_ajaran",	label: "Thn Ajaran", labelWidth: "100", suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/thnajaran"
				    }
				}},
				{ view: "combo", name: "id_sms", label: "Prodi", labelWidth: "100", suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/prodi"
				    }
				}},
				{ view: "text", name:"no_srt_tgs", label: "no_srt_tgs", labelWidth:100},
				{ view: "datepicker", name:"tgl_srt_tgs", label: "tgl_srt_tgs", labelWidth:100},
				{ view: "datepicker", name:"tmt_srt_tgs", label: "tmt_srt_tgs", labelWidth:100}
			]
		}
		],
		dataFeed:"./penugasan/data"
	}
});