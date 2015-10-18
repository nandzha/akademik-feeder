define(function(){
	return {
		view: "form",
		id: "frm_dosen_riwstruk",
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
			{view:"text", name:"sk_jabatan", label: "sk_jabatan", labelWidth:100},
			{view:"combo", name:"id_jabstruk", label: "id_jabstruk", labelWidth:100, options:[
				{ id:"1", value:"Rektor"},
				{ id:"2", value:"Wakil Rektor I"},
				{ id:"3", value:"Wakil Rektor II"},
				{ id:"4", value:"Wakil Rektor III"},
				{ id:"5", value:"Wakil Rektor IV"},
				{ id:"6", value:"Kepala Lembaga"},
				{ id:"7", value:"Kepala Biro"},
				{ id:"8", value:"Kepala UPT"},
				{ id:"9", value:"Kepala Bagian"},
				{ id:"10", value:"Kepala Sub Bagian"},
				{ id:"11", value:"Dekan"},
				{ id:"12", value:"Wakil Dekan"},
				{ id:"13", value:"Ketua Prodi"},
				{ id:"14", value:"Sekretaris Prodi"},
				{ id:"16", value:"Staff Administrasi"},
				{ id:"17", value:"Laboran"},
				{ id:"20", value:"Kabag TU"},
				{ id:"21", value:"Kasubbag TU"},
				{ id:"23", value:"Direktur"},
				{ id:"24", value:"Wakil Direktur"}
			]},
			{view:"datepicker", name:"tgl_sk_jabatan", label: "tgl_sk_jabatan", labelWidth:100},
			{view:"datepicker", name:"tmt_jabatan", label: "tmt_jabatan", labelWidth:100},

			]
		}
		],
		dataFeed:"./riwstruk/data"
	}
});