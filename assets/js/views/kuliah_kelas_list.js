define([
	"apps",
	"views/modules/kelas_search",
	"views/modules/window",
    "views/modules/dataProcessing",
    "views/modules/grid_mahasiswa",
	"views/modules/grid_dosen"
],function(apps, search, ui_window, handleProcessing, grd_mhs, grd_dosen){

var grd_kls_mhs = {
	view:"datatable",
	id:"grd_kls_mhs",
	columns:[
        { id: "trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id:"nipd", header:"nipd", width:100},
		{ id:"nm_pd", header:"nm_pd", width:250},
		{ id:"jk", header:"jk", width:50},
		{ id:"nm_prodi", header:"Prodi", width:200}
	],
	select:"row",
	datafetch:30,
	dataFeed : "./kelas/mhs",
    onClick:{
        trash:function(e,id,node){
            webix.confirm({
                text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
                callback:function(res){
                    if(res){
                        $$("grd_kls_mhs").remove(id);
                    }
                }
            });
        },
    },
    on:{
        onAfterLoad:function(){
            $$("lbl_mhs").define("template", "Jml Peserta kelas: "+this.count() );
            $$('lbl_mhs').refresh();
        }
    }
};

var grd_kls_dos = {
	view:"datatable",
	id:"grd_kls_dos",
	columns:[
        { id: "trash", header:"&nbsp;", width:35, template:"<span  style='cursor:pointer;' class='webix_icon trash fa-trash-o text_danger'></span>"},
		{ id: "nidn", header: "nidn", width:100},
		{ id: "nm_ptk", header: "nm_ptk", width:100},
		{ id: "nm_subst", header: "nm_subst", width:100},
		{ id: "jml_tm_renc", header: "jml_tm_renc", width:100},
		{ id: "jml_tm_real", header: "jml_tm_real", width:100},
	],
	select:"row",
	datafetch:30,
	dataFeed : "./kelas/dosen",
    onClick:{
        trash:function(e,id,node){
            webix.confirm({
                text:"The data will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
                callback:function(res){
                    if(res){
                        $$("grd_kls_dos").remove(id);
                    }
                }
            });
        },
    },
    on:{
        onAfterLoad:function(){
            $$("lbl_dos").define("template", "Jml Dosen Mengajar: "+this.count() );
            $$('lbl_dos').refresh();
        }
    }
};

var btn_add_mhs_krs ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {id:"lbl_mhs", view :"label", width:200 },
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Add Mahasiswa", width: 180, click:function(obj){
            var id = $$("listmk").getSelectedId();
            if (id) {
                webix.$$("win_krs_mhs").show();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_add_dos_ngajar ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {id:"lbl_dos", view :"label", width:200},
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Add Dosen", width: 180, click:function(obj){
            var id = $$("listmk").getSelectedId();
            if (id) {
                webix.$$("win_ngajar_dos").show();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_submit_mhs_krs ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){
            var items  = $$("grd_mhs").getSelectedItem();
            var listmk = $$("listmk").getSelectedId();

            if (items){
                if (Array.isArray(items)) {
                    for (var i=0; i< items.length; i++){
                        items[i].id_kls = listmk.id;
                        $$("grd_kls_mhs").add(items[i]);
                    }
                }else{
                    items.id_kls = listmk.id;
                    $$("grd_kls_mhs").add(items);
                }

                this.getTopParentView().hide();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_submit_dos_ngajar ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){
            var items  = $$("grd_dosen").getSelectedItem();
            var listmk = $$("listmk").getSelectedId();

            if (items){
                if (Array.isArray(items)) {
                    for (var i=0; i< items.length; i++){
                        items[i].id_kls = listmk.id;
                        $$("grd_kls_dos").add(items[i]);
                    }
                }else{
                    items.id_kls = listmk.id;
                    $$("grd_kls_dos").add(items);
                }

                this.getTopParentView().hide();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var ui_scheme = {
	type: "line",
	id:"ui_mk",
	rows:[
	{
		margin:10,
		type: "material",
		cols:[
			search,
			{
				gravity: 1.5,
				rows:[
					{
				        borderless:true,
				        view:"tabbar",
				        id:'tabbar',
				        value: 'mhs',
				        multiview:true,
				        options: [
				            { value: 'Mahasiswa KRS', id: 'mhs'},
				            { value: 'Dosen Mengajar', id: 'dosen'}
				        ]
				    },
				    {
			    		cells:[
					    	{
					    		id:"mhs",
					    		rows: [grd_kls_mhs, btn_add_mhs_krs]
					    	},
					    	{
					    		id:"dosen",
					    		rows: [grd_kls_dos, btn_add_dos_ngajar]
					    	}
						]
					}
				]
			}
		]
	}
	]
};

var win_krs_mhs = ui_window.ui("win_krs_mhs", "LIST MAHASISWA", {rows:[grd_mhs, btn_submit_mhs_krs]});
var win_ngajar_dos = ui_window.ui("win_ngajar_dos", "LIST DOSEN", {rows:[grd_dosen, btn_submit_dos_ngajar]});

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix({win_krs_mhs, win_ngajar_dos});
    },
    onInit: function(){
		$$("grd_kls_mhs").bind($$("listmk"), "a.id_kls");
		$$("grd_kls_dos").bind($$("listmk"), "a.id_kls");
        $$("grd_mhs").bind( $$("listmk"), "$data", function(data, source){
            if (data){
                this.load("/twig_template/mahasiswa/lst?filter[id_sms]="+data.id_sms);
            }
        });
        $$("grd_dosen").bind( $$("listmk"), "$data", function(data, source){
            if (data){
                this.load("/twig_template/dosen/lst");
            }
        });

		var dp_mhs = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_kls_mhs"),
            url: "connector->./kelas/mhs",
            on: handleProcessing
        });

        var dp_dos = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_kls_dos"),
            url: "connector->./kelas/dosen",
            on: handleProcessing
        });

	}
};

});
