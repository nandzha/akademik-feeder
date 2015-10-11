define([
	"apps",
	"views/forms/frm_status_mhs",
	"views/modules/dataProcessing"
],function(apps, forms, handleProcessing){

	var grd_stat_mhs = {
		view:"datatable",
		id:"grd_stat_mhs",
		columns:[
			{ id:"trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
			{ id:"edit", header:"&nbsp;", width:35, template:"<span  style=' cursor:pointer;' class='webix_icon edit fa-pencil'></span>"},
			{ id:"nipd", header: "nim", width: 100 },
            { id:"nm_pd", header: "nama", width: 250 },
            { id:"nm_lemb", header: "prodi", width: 200 },
            { id:"mulai_smt", header: "thak", width: 80 },
            { id:"ket_keluar", header: "ket keluar", width: 150 },
            { id:"tgl_keluar", header: "tgl keluar", width: 150 },
            { id:"ket", header: "ket", width: 100 }
		],
		select:"row",
		url: "statusmhs/data",
		onClick:{
			edit:function(e,id,node){
				$$("tabbar").setValue("form");
			},
			trash:function(e,id,node){
				webix.confirm({
					text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
					callback:function(res){
						if(res){
							$$("grd_stat_mhs").remove(id);
						}
					}
				});
			},
		}
	};

	var btn_save ={
		paddingX:5,
		paddingY:5,
		height:45,
		cols:[
			{},
			{ view: "button", css:"button_raised", label: "save", width: 130, click:function(obj){
				var form   = $$('frm_status_mhs');
				var values = form.getValues();

				if (!values.webix_operation)
					values.webix_operation = 'update';

				if(!form.validate())
					return false;

				form.setValues(values);
				form.save();
				// webix.ajax().post("./statusmhs/detail", values);
			}}
		]
	};

	var ui_scheme = {
		type: "material",
		rows:[
			{
				css:"bg_clean",
		    	cols:[
			    	{
			    		view:"segmented",
			    		id:'tabbar',
			    		value: 'lst_stat_mhs',
				    	multiview:true,
				    	optionWidth:141,
				    	padding: 5,
				    	options: [
					    	{ value: 'List Mahasiswa', id: 'lst_stat_mhs'},
					    	{ value: 'Add New', id: 'form'}
				    	],
				    	on:{
				    		onItemClick : function(id, e){
							    if (e.target.innerText == 'Add New'){
							    	$$("frm_status_mhs").clear();
							    	$$("frm_status_mhs").setValues({webix_operation:'update'});
							    }
							}
				    	}
			    	}
		    	]
			},
			{
				id:"multiview_mk",
		    	cells:[
			    	{
			    		id:"lst_stat_mhs",
			    		rows: [grd_stat_mhs]
			    	},
			    	{
			    		id:"form",
			    		rows:[forms, btn_save]
			    	}
		    	]

			}
		]
	};


	return {
	    ui: function() {
	    	apps.setUiScheme = ui_scheme;
	    	apps.webix();
	    },
	    onInit:function(){
	    	$$("frm_status_mhs").bind($$("grd_stat_mhs"));

	    	var dp = new webix.DataProcessor({
	            updateFromResponse:true,
	            autoupdate:true,
	            master: $$("grd_stat_mhs"),
	            url: "connector->./statusmhs/detail",
	            on: {
	            	handleProcessing,
	            	onBeforeInsert: function(id, action){
					    action.operation = "update";
					}
	            }
	        });
	    }
	};

});
