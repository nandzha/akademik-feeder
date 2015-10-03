define(function(){
	return {
		view: "form",
		id: "frm_matakuliah",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
			{
				cols:[
					{ view: "combo", name: "id_sms", label: "Prodi", gravity:1.5, labelWidth: "100", suggest: {
						body:{
					        yCount:5,
							dataFeed : "/twig_template/sugest/prodi"
					    }
					}},
					{ view: "combo", name: "id_jenj_didik", label: "Jenjang Pendidikan", labelWidth: "150", suggest: {
						body:{
					        yCount:5,
							url : "/twig_template/sugest/jenjang"
					    }
					}},
				]
			},
			{
				cols:[
					{ view:"text", name:"kode_mk",gravity:0.5, label: "kode_mk", labelWidth:100},
					{ view:"text", name:"nm_mk", label: "nm_mk", labelWidth:100},
				]
			},
			{
				cols:[
					{ view:"combo", name:"jns_mk", label: "jns_mk", labelWidth:100, options:[
						{ id: "A", value: "WAJIB"},
						{ id: "B", value: "PILIHAN"},
						{ id: "C", value: "WAJIB PEMINATAN"},
						{ id: "D", value: "PILIHAN PEMINATAN"},
						{ id: "S", value: "TA/SKRIPSI/TESIS/DISERTASI"}
					]},
					{ view:"combo", name:"kel_mk", label: "kel_mk", labelWidth:100, options:[
						{ id:"A", value:"MPK-PENGEMBANGAN KEPRIBADIAN"},
						{ id:"B", value:"MKK-KEILMUAN DAN KETRAMPILAN"},
						{ id:"C", value:"MKB-KEAHLIAN BERKARYA"},
						{ id:"D", value:"MPB-PERILAKU BERKARYA"},
						{ id:"E", value:"MBB-BERKEHIDUPAN BERMASYARAKAT"},
						{ id:"F", value:"MKU/MKDU"},
						{ id:"G", value:"MKDK"},
						{ id:"H", value:"MKK"}
					]},
				]
			},
			{
				cols:[
					{ view:"text", name:"sks_mk", label: "sks_mk", labelWidth:100},
					{ view:"text", name:"sks_tm", label: "sks_tm", labelWidth:100},
					{ view:"text", name:"sks_prak", label: "sks_prak", labelWidth:100},
					{ view:"text", name:"sks_prak_lap", label: "sks_prak_lap", labelWidth:100},
					{ view:"text", name:"sks_sim", label: "sks_sim", labelWidth:100},
				]
			},
			{
				cols:[
					{ view:"checkbox", name:"a_sap", label: "a_sap", labelWidth:100},
					{ view:"checkbox", name:"a_silabus", label: "a_silabus", labelWidth:100},
					{ view:"checkbox", name:"a_bahan_ajar", label: "a_bahan_ajar", labelWidth:100},
					{ view:"checkbox", name:"acara_prak", label: "acara_prak", labelWidth:100},
					{ view:"checkbox", name:"a_diktat", label: "a_diktat", labelWidth:100},
				]
			},
			{
				cols:[
					{ view:"datepicker", name:"tgl_mulai_efektif", label: "tgl mulai efektif", labelWidth:150},
					{ view:"datepicker", name:"tgl_akhir_efektif", label: "tgl akhir efektif", labelWidth:150},
				]
			}
			]
		}
		]
	}
});
