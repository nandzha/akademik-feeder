define(["apps"],function(apps){
	return {
		view: "form",
		id: "formMsmhs",
		scroll: true,
		dataFeed: "./detail",
		elements: [
		{
			margin: 10,
			rows:[
			{
				cols: [
				{
					rows: [
					{ view: "text",name: "nm_pd", label: "Nama", labelWidth: "100"},
					{ view: "text",name: "tmpt_lahir",label: "Tempt Lahir",labelWidth: "100"},
					{ view: "radio",name: "jk",label: "Jenis Kelamin",labelWidth: "100",
						options: [
							{id:"L",value:"Laki-laki"},
							{id:"P",value:"Perempuan"}
						]
					}
					]
				},
				{
					rows: [
					{ view: "text", name: "nm_ibu_kandung", label: "Nama Ibu", labelWidth: "100", disabled:true},
					{ view: "datepicker", name: "tgl_lahir",label: "Tanggal Lahir", labelWidth: "100", format:"%Y-%m-%d"},
					{ view: "select", name: "id_agama", label: "Agama", labelWidth: "100",
						options: [
							{id:"1", value:"Islam" },
							{id:"2", value:"Kristen" },
							{id:"3", value:"Katholik" },
							{id:"4", value:"Hindu" },
							{id:"5", value:"Budha" },
							{id:"6", value:"Konghucu" },
							{id:"98", value:"Tidak diisi" },
							{id:"99", value:"Lainnya" }
						]
					}
					]
				}
				]
			},
			{
				type:"clean",
				gravity:2.2,
				rows:[
				{
					borderless:true, view:"tabbar", id:"msmhstab", value: "alamat", multiview:true,
					options: [
					{ value: "Alamat", id: "alamat"},
					{ value: "Orang Tua", id: "ortu"},
					{ value: "Wali", id: "wali"},
					]
				},
				{
					cells:[
					{
						id:"alamat",
						rows: [
						{
							cols:[
								{view:"text", name: "nik" , label: "NIK", labelWidth:"100"},
								{view:"text", name: "kewarganegaraan" , label: "Warga", labelWidth:"100", value:"ID"},
							]
						},
						{view:"text", name: "jln" , label: "Jalan", labelWidth:"100"},
						{
							cols:[
								{view:"text", name: "nm_dsn" , label: "Dusun", labelWidth:"100"},
								{view:"text", name: "rt" , label: "Rt", labelWidth:"50"},
								{view:"text", name: "rw" , label: "Rw", labelWidth:"50"},
							]
						},
						{
							cols:[
								{view:"text", name: "ds_kel" , label: "Kelurahan", labelWidth:"100"},
								{view:"text", name: "kode_pos" , label: "Kode POS", labelWidth:"100"},
							]
						},
						{view:"combo", name: "id_wil" , label: "Kecamatan", labelWidth:"100", suggest: {
							body:{
						        yCount:5,
								dataFeed : "/twig_template/sugest/wilayah",
								template:function(obj){
									return obj.value;
							 	},
						    }
						}},
						{
							cols:[
								{view:"text", name: "telepon_rumah" , label: "Telepon", labelWidth:"100"},
								{view:"text", name: "telepon_seluler" , label: "HP", labelWidth:"100"},
							]
						},
						{view:"text", name: "email" , label: "Email", labelWidth:"100"}
						]
					},
					{
						id:"ortu",
						cols:[
						{
							rows:[
								{ template:"Ayah", type:"section"},
								{view:"text", name: "nm_ayah", label:"Nama", labelWidth:"100"},
								{view:"datepicker", name: "tgl_lahir_ayah", label:"Tgl.Lahir", labelWidth:"100"},
								{view:"text", name: "id_jenjang_pendidikan_ayah", label:"Pendidikan", labelWidth:"100"},
								{view:"text", name: "id_pekerjaan_ayah", label:"Pekerjaan", labelWidth:"100"},
								{view:"text", name: "id_penghasilan_ayah", label:"Penghasilan", labelWidth:"100"},
							]
						},
						{
							rows:[
								{ template:"Ibu", type:"section"},
								{view:"text", name: "nm_ibu_kandung", label:"Nama", labelWidth:"100"},
								{view:"datepicker", name: "tgl_lahir_ibu", label:"Tgl.Lahir", labelWidth:"100"},
								{view:"text", name: "id_jenjang_pendidikan_ibu", label:"Pendidikan", labelWidth:"100"},
								{view:"text", name: "id_pekerjaan_ibu", label:"Pekerjaan", labelWidth:"100"},
								{view:"text", name: "id_penghasilan_ibu", label:"Penghasilan", labelWidth:"100"},
							]
						}
						]
					},
					{
						id:"wali",
						rows:[
						{view:"text", name: "nm_wali", label:"Nama", labelWidth:"100"},
						{view:"datepicker", name: "tgl_lahir_wali", label:"Tgl.Lahir", labelWidth:"100"},
						{view:"text", name: "id_jenjang_pendidikan_wali", label:"Pendidikan", labelWidth:"100"},
						{view:"text", name: "id_pekerjaan_wali", label:"Pekerjaan", labelWidth:"100"},
						{view:"text", name: "id_penghasilan_wali", label:"Penghasilan", labelWidth:"100"}
						]
					},
					]
				}
				]
			}
			]
		}
		]
	}
});
