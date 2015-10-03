define(function(){
	return {
		view: "form",
		id: "frm_add_kelas",
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
				{ view: "combo", id:"id_mk", name:"id_mk", label: "Matakuliah", labelWidth: "100",
				suggest: {
					body:{
				        yCount:5,
						dataFeed : "/twig_template/sugest/mk",
						template:function(obj){
							return obj.kode_mk + " - " + obj.value;
					 	},
				    }
				}},
				{ view:"multiselect", name:"nm_kls", label:"Kelas",labelWidth: "100", options:[
				    { id:'01', value:"01" },
				    { id:'02', value:"02" },
				    { id:'03', value:"03" },
				    { id:'04', value:"04" },
				    { id:'05', value:"05" },
				    { id:'06', value:"06" },
				    { id:'07', value:"07" },
				    { id:'08', value:"08" },
				    ]
				},
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
