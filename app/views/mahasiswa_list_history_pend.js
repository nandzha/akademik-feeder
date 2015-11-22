define([
	"apps",
	"views/modules/mhs_search",
    "views/modules/dataProcessing",
    "views/modules/dataProgressBar",
], function(apps,search, handleProcessing, notifidata){

var grd_riwpend = {
	view:"datatable",
	id:"grd_riwpend",
	columns:[
		{ id: "nipd", width:200 },
		{ id: "nm_jns_daftar", width:200},
		{ id: "mulai_smt", width:200},
		{ id: "tgl_masuk_sp", width:200},
		{ id: "nm_jurusan", width:200}
	],
	select:"row", editable:true, editaction:"dblclick",
	dataFeed : "./riwpend/data",
    ready: notifidata.emptydata,
    on: notifidata.progressbar
};

var btn_add ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "daftarkan", width: 150, click:function(obj){
            var id = $$("listmsmhs").getSelectedId();
            if (id) {
                var data = {
                    nipd: "",
					nm_jns_daftar: "",
					mulai_smt: "",
					tgl_masuk_sp: "",
					nm_jurusan: "",
                }
                webix.$$("grd_riwpend").add(data);
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var ui_scheme = {
	type: "line",
	id:"uimsmhs",
    rows:[
    {
    	margin:10,
    	type: "material",
    	cols:[
    		search,
    		{
                gravity: 2.2,
                rows:[
		    		grd_riwpend,
                ]
            }
    	]
    }
    ],
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){
        $$("grd_riwpend").bind( $$("listmsmhs"), "c.id_reg_pd");

        var dp = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_riwpend"),
            url: "connector->./riwpend/data",
            on: handleProcessing
        });
	}
};
});
