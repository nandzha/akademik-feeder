define([
	"apps",
	"views/modules/mhs_search",
	"views/modules/dataProcessing",
    "views/modules/dataProgressBar"
], function(apps,search, handleProcessing, notifidata){


function paramsBuilder(obj){
    var str = "";
    for (var key in obj) {
        if (str != "") {
            str += "&";
        }
        str += key + "=" + obj[key];
    }
    return str;
}

function startCompare(value, filter){
    value = value.toString().toLowerCase();
    filter = filter.toString().toLowerCase();
    return value.indexOf(filter) !== -1;
}

var grd_nilai = {
	view:"treetable",
	id:"grd_nilai",
	navigation:true,
    scheme:{
        $group:{
            by:"semester",
            map:{
                sks_mk:["sks_mk", "sum"],
                nm_mk:["semester"]
            }
        },
        $sort:{ by:"value", dir:"desc" }
    },
	columns:[
        { id: "id_smt", header: "id_smt", width:70 },
		{ id: "semester", header: "smt", width:50 },
		{ id: "kode_mk", header: "kode_mk", width:100},
        { id: "nm_mk", header:["Matakuliah", {content:"textFilter", compare:startCompare} ], sort:"string",  width:300,
            template:function(obj, common){
                if (obj.$group) return common.treetable(obj, common) +"Semester "+obj.nm_mk;
                return obj.nm_mk;
            }
        },
		{ id: "nm_kls", header: "nm_kls", width:80},
		{ id: "sks_mk", header: "sks_mk", width:80},
		{ id: "nilai_huruf", header: "nilai_huruf", width:80, editor: "text"},
	],
	select:true,
	editable:true,
	dataFeed : "./nilailst/data",
    ready: function(){
        this.open(this.getFirstId());
        if (!this.count()){
            webix.extend(this, webix.OverlayBox);
            this.showOverlay("<div style='margin:75px; font-size:20px;'>There is no data</div>");
        };
    },
    on: notifidata.progressbar
};

var button = {
    paddingX:5,
    paddingY:5,
    height:40,
    cols:[
        {},
        { view: "button", type: "iconButton", icon: "print", label: "KHS", width: 100, popup:"popKHS"},
        { view: "button", type: "iconButton", icon: "print", label: "Transkrip", width: 150, click:function(){
            var items     = $$("listmsmhs").getSelectedItem();
            var listmsmhs = $$("listmsmhs").getSelectedId();

            if (items) {
                $$("grd_nilai").exportToPDF("/twig_template/preview/transkrip?filter[a.id_reg_pd]="+listmsmhs.id);
            }else{
                webix.message({ type:"error", text:"Please select one", expire:3000});
            }
        }}
    ]
};

var formKHS = {
    view:"form",
    id:"formKHS",
    margin:10,
    elements:[
        {
            cols:[
                { view: "select", name: "semester",options: [
                    {id:"1", value: "Semester 1"},
                    {id:"2", value: "Semester 2"},
                    {id:"3", value: "Semester 3"},
                    {id:"4", value: "Semester 4"},
                    {id:"5", value: "Semester 5"},
                    {id:"6", value: "Semester 6"},
                    {id:"7", value: "Semester 7"},
                    {id:"8", value: "Semester 8"}
                ]},
                { view: "button",type: "iconButton", icon: "print", label: "Cetak", width: 110, height:35, click:function(){
                    var items     = $$("listmsmhs").getSelectedItem();
                    var listmsmhs = $$("listmsmhs").getSelectedId();

                    var form = $$('formKHS');
                    var values = form.getValues();

                    if (items) {
                        values.id_reg_pd = listmsmhs.id;
                        if(form.isDirty()){
                            $$("grd_nilai").exportToPDF("/twig_template/preview/khs?"+paramsBuilder(values));
                            this.getTopParentView().hide(); //hide window
                        };
                    }else{
                        webix.message({ type:"error", text:"Please select one", expire:3000});
                    }
                }}
            ]
        }
    ]
};

var ui_popup = {
    view:"popup",
    id:"popKHS",
    head:"Submenu",
    width:450,
    height:280,
    body: {
        rows:[
        formKHS
        ],
    }
}

var ui_scheme = {
	type: "line",
	id:"ui_nilai",
    rows:[
    {
    	margin:10,
    	type: "material",
    	cols:[
    		search,
            {
                gravity: 2.2,
                rows:[
                    grd_nilai,
                    button
                ]
            }
    	]
    }
    ],
};

return {
    ui: function() {
        apps.setUiScheme = ui_scheme;
        apps.setUiPopup  = ui_popup;
    	apps.webix();
    },
    onInit: function(){
		$$("grd_nilai").bind($$("listmsmhs"), "a.id_reg_pd");
		var dp = new webix.DataProcessor({
            updateFromResponse:true,
            autoupdate:true,
            master: $$("grd_nilai"),
            url: "connector->./nilailst/data",
            on: handleProcessing
        });
	}
};
});
