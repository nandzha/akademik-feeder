define([
	"apps",
	"views/modules/mhs_search",
    "views/modules/dataProcessing",
    "views/modules/dataProgressBar",
], function(apps,search, handleProcessing,notifidata){

var grd_aktifitas = {
	view:"datatable",
	id:"grd_aktifitas",
	columns:[
		{ id: "id_smt", header : "semester", width: 100 },
        { id: "id_stat_mhs", header : "status mahasiswa", width: 200, editor:"select", options:[
            { id:'A', value:'AKTIF'},
            { id:'C', value:'CUTI'},
            { id:'D', value:'DROP-OUT/PUTUS STUDI'},
            { id:'G', value:'SEDANG DOUBLE DEGREE'},
            { id:'K', value:'KELUAR'},
            { id:'L', value:'LULUS'},
            { id:'N', value:'NON-AKTIF'},
            { id:'X', value:'UNKNOWN'}
        ]},
        { id: "ips", header : "ips", width: 80,editor:"text" },
        { id: "ipk", header : "ipk", width: 80,editor:"text" },
        { id: "sks_smt", header : "sks_smt", width: 80,editor:"text" },
        { id: "sks_total", header : "sks_total", width: 100,editor:"text" }
	],
	select:"row", editable:true, editaction:"dblclick",
	datafetch:30,
	dataFeed : "./kuliahaktifitas/data",
    ready: notifidata.emptydata,
    on: notifidata.progressbar
};

var btn_add ={
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {},
        { view: "button", type: "iconButton", icon: "plus", css:"button_success", label: "Hitung IPS/IPK", width: 170, click:function(obj){
            var id = $$("listmsmhs").getSelectedId();
            var mhs = $$("listmsmhs").getSelectedItem();
            if (id) {
                var data = {
                    id_reg_pd : mhs.id_reg_pd,
                    ipk : 0,
                    sks_total : 0,
                    id_stat_mhs : 'A'
                }
                $$("grd_aktifitas").add(data);
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
            master: $$("grd_aktifitas"),
            url: "connector->./kuliahaktifitas/data",
            on: handleProcessing
        });
	}
};
});
