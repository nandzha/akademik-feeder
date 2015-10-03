define(function(){
	return {
		view: "form",
		id: "frm_dosen_riwfung",
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
			{view:"combo", name:"id_jabfung", label: "id_jabfung", labelWidth:100, options:[
				{ id:"1", value:"Guru Pratama, ll/a"},		
				{ id:"2", value:"Guru Pratama TK I, ll/b"},		
				{ id:"3", value:"Guru Muda, ll/c"},		
				{ id:"4", value:"Guru Muda TK I, ll/d"},		
				{ id:"5", value:"Guru Madya, lll/a"},		
				{ id:"6", value:"Guru Madya TK I, II l/b"},		
				{ id:"7", value:"Guru Dewasa, lll/c"},		
				{ id:"8", value:"Guru Dewasa TK I, lll/d"},		
				{ id:"9", value:"Guru Pembina, IV/a"},		
				{ id:"10", value:"Guru Pembina TK I, IV/b"},		
				{ id:"11", value:"Guru Utama Muda, IV/c"},		
				{ id:"12", value:"Guru Utama Madya, IV/d"},		
				{ id:"13", value:"Guru Utama, IV/e"},		
				{ id:"14", value:"Guru Pertama, lll/a"},		
				{ id:"15", value:"Guru Pertama, lll/b"},		
				{ id:"16", value:"Guru Muda, lll/c"},		
				{ id:"17", value:"Guru Muda, lll/d"},		
				{ id:"18", value:"Guru Madya, IV/a"},		
				{ id:"19", value:"Guru Madya, IV/b"},		
				{ id:"20", value:"Guru Madya, IV/c"},		
				{ id:"21", value:"Guru Utama, IV/d"},		
				{ id:"22", value:"Guru Utama, IV/e"},		
				{ id:"31", value:"Dosen"},		
				{ id:"32", value:"Asisten Ahli"},		
				{ id:"33", value:"Lektor"},		
				{ id:"34", value:"Lektor Kepala"},		
				{ id:"35", value:"Profesor"}	
			]},
			{view:"text", name:"sk_jabatan", label: "sk_jabatan", labelWidth:100},
			{view:"datepicker", name:"tgl_sk_jabatan", label: "tgl_sk_jabatan", labelWidth:100},
			{view:"datepicker", name:"tmt_jabatan", label: "tmt_jabatan", labelWidth:100},

			]
		}
		],
		dataFeed:"./riwfung/data",
	}
});