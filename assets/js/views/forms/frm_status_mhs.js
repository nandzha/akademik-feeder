define(function(){
	return {
		view: "form",
		id: "frm_status_mhs",
		scroll: true,
		elements: [
		{
			margin: 10,
			rows:[
			{ view: "combo", name: "id_reg_pd", label: "Mahasiswa", labelWidth: 120, suggest: {
				body:{
			        yCount:5,
					dataFeed : "/twig_template/sugest/mhs",
					template:function(obj){
						return obj.nipd + " - " + obj.value;
				 	},
			    }
			}},
			{
				cols:[
					{ view:"combo", name:"id_jns_keluar", label: "Jenis Keluar", labelWidth:120, options:[
						{ id:"1", value: "Lulus"},
						{ id:"2", value: "Mutasi"},
						{ id:"3", value: "Dikeluarkan"},
						{ id:"4", value: "Mengundurkan diri"},
						{ id:"5", value: "Putus Sekolah"},
						{ id:"6", value: "Wafat"},
						{ id:"7", value: "Hilang"},
						{ id:"Z", value: "Lainnya"},
					]},
					{ view:"datepicker", name:"tgl_keluar", label:"tgl_keluar", labelWidth:120},
				]
			},
			{ view:"text", name:"ket", label:"ket", labelWidth:120},
			{ view: "radio",name: "jalur_skripsi",label: "jalur_skripsi",labelWidth: 120,
				options: [
					{id:"1",value:"Skripsi"},
					{id:"0",value:"Non-Skripsi"}
				]
			},
			{ view:"textarea", name:"judul_skripsi", label:"judul_skripsi", labelWidth:120},
			{
				cols:[
					{ view:"datepicker", name:"bln_awal_bimbingan", label:"awal_bimbingan", labelWidth:120},
					{ view:"datepicker", name:"bln_akhir_bimbingan", label:"akhir_bimbingan", labelWidth:120},
				]
			},
			{
				cols:[
					{ view:"text", name:"sk_yudisium", label:"sk_yudisium", labelWidth:120},
					{ view:"datepicker", name:"tgl_sk_yudisium", label:"tgl_sk_yudisium", labelWidth:120},
				]
			},
			{
				cols:[
					{ view:"text", name:"ipk", label:"ipk", labelWidth:120},
					{ view:"text", name:"no_seri_ijazah", label:"no_seri_ijazah", labelWidth:120},
				]
			}
			]
		}
		],
		dataFeed:"./statusmhs/detail"
	}
});
