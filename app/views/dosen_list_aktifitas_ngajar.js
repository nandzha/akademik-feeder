define([
	"apps",
	"views/modules/dosen_search",
	"views/modules/window",
    "views/modules/grid_mk_kelas",
    "views/modules/dataProcessing"
],function(apps, search, ui_window,grid_mk_kelas, handleProcessing){

var grd_aktifitas = {
	view:"datatable",
	id:"grd_aktifitas",
	columns:[
		{ id:"id_smt", header: "id_smt", width:100},
        { id:"kode_mk", header: "kode_mk", width:100},
        { id:"nm_mk", header: "nm_mk", width:250},
        { id:"nm_kls", header: "nm_kls", width:80},
        { id:"jml_tm_renc", header: "jml_tm_renc", width:80},
        { id:"jml_tm_real", header: "jml_tm_real", width:80}
	],
	select:"row", editable:true, editaction:"dblclick",
	datafetch:30,
	dataFeed : "./aktifitasngajar/data"
};

var btn_add ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Add Kelas", width: 140, click:function(obj){
            var id = $$("listdosen").getSelectedId();
            if (id) {
                webix.$$("win_matakuliah").show();
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var btn_submit_kelas ={
    paddingX:10,
    paddingY:10,
    height:50,
    cols:[
        {},
        { view: "button", css:"button_danger", label: "Cancel", width: 120, click:function(obj){
             this.getTopParentView().hide();
        }},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Submit", width: 120, click:function(obj){

            var items     = $$("grd_mk_kls").getSelectedItem();
            var listdosen = $$("listdosen").getSelectedItem();

            if (items){
                if (Array.isArray(items)) {
                    for (var i=0; i< items.length; i++){
                        items[i].id_reg_ptk = listdosen.id_reg_ptk;
                        $$("grd_aktifitas").add(items[i]);
                    }
                }else{
                    items.id_reg_ptk = listdosen.id_reg_ptk;
                    $$("grd_aktifitas").add(items);
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
	id:"ui_aktifitas",
	rows:[
	{
		margin:10,
		type: "material",
		cols:[
			search,
			{
                gravity: 2.2,
				rows:[
					grd_aktifitas,
					btn_add
				]
			}
		]
	}
	]
};

var win_matakuliah = {
    rows:[grid_mk_kelas,btn_submit_kelas]
}

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.setUiWindow = ui_window.ui("win_matakuliah", "LIST KELAS KULIAH", win_matakuliah);
    	apps.webix();
    },
    onInit: function(){
		$$("grd_aktifitas").bind($$("listdosen"), "b.id_ptk");
        $$("grd_mk_kls").bind( $$("listdosen"), "$data", function(data, source){
            if (data){
                this.load("./mksmt/data?filter[id_sms]="+data.id_sms);
            }
        });

        var dp = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_aktifitas"),
            url: "connector->./aktifitasngajar/data",
            on: handleProcessing
        });
	}
};

});
