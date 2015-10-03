define([
	"apps",
	"views/modules/mhs_search",
    "views/modules/dataProcessing"
], function(apps,search, handleProcessing){

var grd_aktifitas = {
	view:"datatable",
	id:"grd_aktifitas",
	columns:[
		{ id: "id_smt", header : "semester", width: 100 },
        { id: "nm_stat_mhs", header : "status mahasiswa", width: 200, editor:"text" },
        { id: "ips", header : "ips", width: 80,editor:"text" },
        { id: "ipk", header : "ipk", width: 80,editor:"text" },
        { id: "sks_smt", header : "sks_smt", width: 80,editor:"text" },
        { id: "sks_total", header : "sks_total", width: 100,editor:"text" }
	],
	select:"row", editable:true, editaction:"dblclick",
	datafetch:30,
	dataFeed : "./kuliahaktifitas/data"
};

var btn_add ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Add New", width: 130, click:function(obj){
            var id = $$("listmsmhs").getSelectedId();
            if (id) {
                var data = {
                    id_smt : "",
                    nm_stat_mhs : "",
                    ips : "",
                    ipk : "",
                    sks_smt : "",
                    sks_total : ""
                }
                webix.$$("grd_aktifitas").add(data);
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
    ],
};

return {
    ui: function() {
    	apps.setUiScheme = ui_scheme;
    	apps.webix();
    },
    onInit: function(){
		$$("grd_aktifitas").bind($$("listmsmhs"), "id_reg_pd");
        var dp = new webix.DataProcessor({
            updateFromResponse:true, 
            autoupdate:true,
            master: $$("listmsmhs"),
            url: "connector->./nilai/data",
            on: handleProcessing
        });
	}
};
});