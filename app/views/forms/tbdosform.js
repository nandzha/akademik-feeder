define(["apps"],function(apps){

	return {
		view: "form",
		id: "formTbdos",
		scroll: true,
		dataFeed: "./detail",
		elements: [
		{
			margin: 10,
			rows:[
			{view:"text", name:"nm_ptk", label:"Nama", labelWidth:"100"},
			{
				cols: [
				{
					rows: [
					{view:"text", name:"tmpt_lahir", label:"Tempat Lahir", labelWidth:"100"},
					{ view: "radio",name: "jk",label: "Jenis Kelamin",labelWidth: "100",
						options: [
							{id:"L",value:"Laki-laki"},
							{id:"P",value:"Perempuan"}
						]
					},
					{view:"select", name:"id_stat_aktif", label:"Status Aktif", labelWidth:"100",
						options:[
							{id:"1",  value: "Aktif"},
							{id:"2",  value: "Tidak Aktif"},
							{id:"20", value: "CUTI"},
							{id:"21", value: "KELUAR"},
							{id:"22", value: "ALMARHUM"},
							{id:"23", value: "PENSIUN"},
							{id:"24", value: "IJIN BELAJAR"},
							{id:"25", value: "TUGAS DI INSTANSI LAIN"},
							{id:"26", value: "GANTI NIDN"},
							{id:"27", value: "TUGAS BELAJAR"},
							{id:"28", value: "HAPUS NIDN"},
							{id:"99", value: "Lainnya"}
						]
					}
					]
				},
				{
					rows: [
					{view:"datepicker", name:"tgl_lahir", label:"Tgl Lahir", labelWidth:"100", format:"%Y-%m-%d"},
					{view: "select", name: "id_agama", label: "Agama", labelWidth: "100",
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
					},
					{view:"text", name:"nidn", label:"NIDN", labelWidth:"100"}
					]
				}
				]
			},
			{
				type:"clean",
				gravity:2.2,
				rows:[
				{
					borderless:true, view:"tabbar", id:"msmhstab", value: "biodata", multiview:true,
					options: [
						{ value: "Biodata", id: "biodata"},
						{ value: "Keluarga", id: "keluarga"}
					]
				},
				{
					cells:[
					{
						id:"biodata",
						rows: [
						{view:"text", name:"nm_ibu_kandung", label:"Nama Ibu", labelWidth:"100"},
						{view:"text", name:"nip", label:"NIP", labelWidth:"100"},
						{view:"text", name:"nik", label:"NIK", labelWidth:"100"},
						{view:"text", name:"npwp", label:"NPWP", labelWidth:"100"},
						{
							cols:[
							{
								rows:[
									{view:"select", name:"id_ikatan_kerja", label:"Ikatan Kerja", labelWidth:"100",
									options:[
										{id:"A", value:"DOSEN TETAP"},
										{id:"B", value:"DOSEN PNS DPK"},
										{id:"D", value:"DOSEN HONORER"},
										{id:"E", value:"DOSEN SP RUMAH SAKIT"},
										{id:"F", value:"DOSEN TETAP BHMN"},
										{id:"G", value:"DOSEN TIDAK TETAP"},
										{id:"X", value:"LAINNYA"}
									]},
									{view:"text", name:"sk_cpns", label:"NO SK CPNS", labelWidth:"100"},
									{view:"text", name:"sk_angkat", label:"NO SK Pengangkatan", labelWidth:"100"},
									{view:"select", name:"id_lemb_angkat", label:"Lembg Pengangkatan", labelWidth:"100", options:[
										{id:"0" , value: "(tidak diisi)"},
										{id:"1" , value: "Pemerintah Pusat"},
										{id:"2" , value: "Pemerintah Propinsi"},
										{id:"3" , value: "Pemerintah Kab/Kota"},
										{id:"4" , value: "Ketua Yayasan"},
										{id:"5" , value: "Kepala Sekolah"},
										{id:"6" , value: "Komite Sekolah"},
										{id:"7" , value: "Lainnya"},
										{id:"20" , value: "Kemenag"},
										{id:"40" , value: "Kemenkes"},
										{id:"99" , value: "Lainnya"}
									]},
								]
							},
							{
								rows:[
									{view:"select", name:"id_stat_pegawai", label:"Status Pegawai", labelWidth:"100",
									options:[
										{id:"1", value:"PNS"},
										{id:"2", value:"PNS Diperbantukan"},
										{id:"3", value:"PNS Depag"},
										{id:"4", value:"GTY/PTY"},
										{id:"5", value:"GTT/PTT Provinsi"},
										{id:"6", value:"GTT/PTT Kab/Kota"},
										{id:"7", value:"Guru Bantu Pusat"},
										{id:"8", value:"Guru Honor Sekolah"},
										{id:"9", value:"Tenaga Honor Sekolah"},
										{id:"10", value:"NON PNS"},
										{id:"11", value:"TNI"},
										{id:"99", value:"Lainnya"}
									]},
									{view:"text", name:"tgl_sk_cpns", label:"Tgl SK CPNS", labelWidth:"100"},
									{view:"text", name:"tmt_sk_angkat", label:"Tgl SK Pengangkatan", labelWidth:"100"},
									{view:"select", name:"id_pangkat_gol", label:"Pangkat Gol", labelWidth:"100",
									options:[
										{id:"1", value:"I/a"},
										{id:"2", value:"I/b"},
										{id:"3", value:"I/c"},
										{id:"4", value:"I/d"},
										{id:"5", value:"II/a"},
										{id:"6", value:"II/b"},
										{id:"7", value:"II/c"},
										{id:"8", value:"II/d"},
										{id:"9", value:"III/a"},
										{id:"10", value:"III/b"},
										{id:"11", value:"III/c"},
										{id:"12", value:"III/d"},
										{id:"13", value:"IV/a"},
										{id:"14", value:"IV/b"},
										{id:"15", value:"IV/c"},
										{id:"16", value:"IV/d"},
										{id:"17", value:"IV/e"},
										{id:"99", value:"-"}
									]},
								]
							}
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
							{
								rows:[
									{view:"text", name: "ds_kel" , label: "Kelurahan", labelWidth:"100"},
									{view:"text", name: "no_tel_rmh" , label: "Telepon", labelWidth:"100"},
								]
							},
							{
								rows:[
									{view:"text", name: "kode_pos" , label: "Kode POS", labelWidth:"100"},
									{view:"text", name: "no_hp" , label: "HP", labelWidth:"100"},
								]
							}
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
						{view:"text", name: "email" , label: "Email", labelWidth:"100"}
						]
					},
					{
						id:"keluarga",
						rows:[
							{view:"select", name:"stat_kawin", label:"Status Pernikahan", labelWidth:"150",
							options:[
								{id:"0", value: "Belum Menikah"},
								{id:"1", value: "Sudah Menikah"},
								{id:"2", value: "Cerai"},
							]},
							{view:"text", name:"nm_suami_istri", label:"nama suami/istri", labelWidth:"150"},
							{view:"text", name:"nip_suami_istri", label:"nip suami/istri", labelWidth:"150"},
							{view:"text", name:"tmt_pns", label:"TMT PNS", labelWidth:"150"},
							{view:"select", name:"id_pekerjaan_suami_istri", label:"Pekerjaan", labelWidth:"150",
							options:[
								{id:"0", value:"-"},
								{id:"1", value:"Tidak bekerja"},
								{id:"2", value:"Nelayan"},
								{id:"3", value:"Petani"},
								{id:"4", value:"Peternak"},
								{id:"5", value:"PNS/TNI/Polri"},
								{id:"6", value:"Karyawan Swasta"},
								{id:"7", value:"Pedagang Kecil"},
								{id:"8", value:"Pedagang Besar"},
								{id:"9", value:"Wiraswasta"},
								{id:"10", value:"Wirausaha"},
								{id:"11", value:"Buruh"},
								{id:"12", value:"Pensiunan"},
								{id:"98", value:"Sudah Meninggal"},
								{id:"99", value:"Lainnya"},
							]},
						]
					},
					]
				}
				]
			}
			]
		}
		],
		/*rules:{
			KDPSTTBDOS: webix.rules.isNotEmpty,
			NIDNNTBDOS: webix.rules.isNumber,
			NOKTPTBDOS: webix.rules.isNotEmpty,
			NMDOSTBDOS: webix.rules.isNotEmpty
		},
		on:{
	        onValidationError:function(key, obj){
	        	webix.message({ type:"error", text:key + " isNotEmpty", expire:5000 });
	    	}
	    }*/
	}
});
