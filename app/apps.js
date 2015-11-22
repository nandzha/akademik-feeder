define(['menuTree'], function(){

    var ui_scheme = {};
    var ui_window = {};
    // var ui_popup  = {};
    // var xhr = webix.ajax().sync().get("./api");

    var dataMenu =[
        {id : "m0", url : "", value : "Mahasiswa", details : "Mahasiswa", open :  false, data : [
            {id : "m1", url : "admin/mahasiswa", value : "List Mahasiswa", icon : "check-square-o", details : "List Mahasiswa"},
            {id : "m2", url : "admin/mahasiswa/detail", value : "Detail Mahasiswa", icon : "check-square-o", details : "Detail Mahasiswa"},
            {id : "m3", url : "admin/mahasiswa/riwpend", value : "Riwayat Pendidikan ", icon : "check-square-o", details : "Riwayat Pendidikan "},
            {id : "m4", url : "admin/mahasiswa/krslst", value : "Krs Mahasiswa", icon : "check-square-o", details : "Krs Mahasiswa"},
            {id : "m5", url : "admin/mahasiswa/nilailst", value : "Nilai Mahasiswa", icon : "check-square-o", details : "Nilai Mahasiswa"},
            {id : "m6", url : "admin/mahasiswa/kuliahaktifitas", value : "Aktifitas Perkuliahan", icon : "check-square-o", details : "Aktifitas Perkuliahan"}
        ]},
        {id : "d0", url : "", value : "Dosen", details : "Dosen", open :  false, data : [
            {id : "d1", url : "admin/dosen", value : "List Dosen", icon : "check-square-o", details : "List Dosen"},
            {id : "d2", url : "admin/dosen/detail", value : "Detail Dosen", icon : "check-square-o", details : "Detail Dosen"},
            {id : "d3", url : "admin/dosen/penugasan", value : "Penugasan Dosen", icon : "check-square-o", details : "Penugasan Dosen"},
            {id : "d4", url : "admin/dosen/aktifitasngajar", value : "Aktifitas Mengajar", icon : "check-square-o", details : "Aktifitas Mengajar"},
            {id : "d5", url : "admin/dosen/riwpend", value : "Riwayat Pendidikan", icon : "check-square-o", details : "Riwayat Pendidikan"},
            {id : "d6", url : "admin/dosen/riwstruk", value : "Riwayat Struktural", icon : "check-square-o", details : "Riwayat Struktural"},
            {id : "d7", url : "admin/dosen/riwfung", value : "Riwayat Fungsional", icon : "check-square-o", details : "Riwayat Fungsional"},
            {id : "d8", url : "admin/dosen/riwpang", value : "Riwayat Kepangkatan", icon : "check-square-o", details : "Riwayat Kepangkatan"},
            {id : "d9", url : "admin/dosen/riwsert", value : "Riwayat Sertifikasi", icon : "check-square-o", details : "Riwayat Sertifikasi"}
        ]},
        {id : "p0", url : "", value : "Perkuliahan", details : "Perkuliahan", open :  false, data : [
            {id : "p1", url : "admin/kuliah", value : "List Matakuliah", icon : "check-square-o", details : "List Matakuliah"},
            {id : "p2", url : "admin/kuliah/kurikulum", value : "Kurikulum Semester", icon : "check-square-o", details : "Kurikulum Semester"},
            {id : "p3", url : "admin/kuliah/subtansi", value : "List Subtansi Kuliah", icon : "check-square-o", details : "List Subtansi Kuliah"},
            {id : "p4", url : "admin/kuliah/listkelas", value : "List Kelas", icon : "check-square-o", details : "Add Kelas Perkuliahan"},
            {id : "p5", url : "admin/kuliah/kelas", value : "Kelas Perkuliahan", icon : "check-square-o", details : "Kelas Perkuliahan"},
            {id : "p6", url : "admin/kuliah/nilai", value : "Nilai Perkuliahan", icon : "check-square-o", details : "Nilai Perkuliahan"},
            {id : "p7", url : "admin/kuliah/aktifitasmhs", value : "Aktifitas Kuliah MHS", icon : "check-square-o", details : "Aktifitas Kuliah Mahasiswa"},
            {id : "p8", url : "admin/kuliah/statusmhs", value : "Mahasiswa Lulus/DO", icon : "check-square-o", details : "Mahasiswa Lulus/droput"}
        ]},
        {id : "t0", url : "", value : "Tools", details : "Tools", open :  false, data : [
            {id : "t1", url : "admin/tools/sync", value : "Sinkronisasi", icon : "check-square-o", details : "Sinkronisasi Data"},
        ]},

    ];

    function restore_state() {
        var state = webix.storage.local.get("menuState");
        return state;
    }

    var menu = {
        view :  "tree",
        id :  "menu",
        type :  "menuTree2",
        css :  "menu",
        activeTitle :  true,
        select :  true,
        url:"/twig_template/api/menu",
        ready : function(){
            var state = restore_state();
            this.addCss(state.select[0], "webix_selected");
            this.open(state.open[0]);
        },
        tooltip :  {
            template :  function(obj){
                return obj.$count?"" : obj.details;
            }
        },
        on : {
            onBeforeSelect : function(id){
                return !this.getItem(id).$count;
            },
            onAfterSelect : function(id){
                // var storeInit = JSON.parse(xhr.response);
                var item = this.getItem(id);
                if (!item.$count){
                    window.location.href = "/twig_template/"+item.url;
                    webix.storage.local.put("menuState", this.getState());
                }
            },
        },
        // data :  dataMenu
    };

    var mainToolbar = {
        view :  "toolbar",
        css : "header",
        elements : [
            // {view :  "label", label :  "<a href='http : //webix.com'><img class='photo' src='/assets/imgs/webix-logotype.svg' height='40' /></a>", width :  200},
            {view : "label", label:"SIM Akademik", template : "html->toolbar",  width : 500},
            {},
            { height : 46, id :  "person_template", css :  "header_person", borderless : true, width :  180, data :  {id : 3,name :  "Administrator"},
                template :  function(obj){
                    var html =  "<div style='height : 100%;width : 100%;' onclick='webix.$$(\"profilePopup\").show(this)'>";
                    html += "<img class='photo' src='/twig_template/assets/imgs/photos/"+obj.id+".png' /><span class='name'>"+obj.name+"</span>";
                    html += "<span class='webix_icon fa-angle-down'></span></div>";
                    return html;
                }
            },
            // {view :  "icon", icon :  "search",  width :  45, popup :  "searchPopup"},
            // {view :  "icon", icon :  "envelope-o", value :  3, width :  45, popup :  "mailPopup"},
            // {view :  "icon", icon :  "comments-o", value :  5, width :  45, popup :  "messagePopup"}
        ]
    };

    var ui_popup = {
        view :  "submenu",
        id :  "profilePopup",
        width :  200,
        padding : 0,
        data :  [
            {id :  1, icon :  "user", value :  "My Profile", href : "#"},
            { $template : "Separator" },
            {id :  4, icon :  "sign-out", value : "Logout", href : "/admin/signout"}
        ],
        type : {
            template :  function(obj){
                if(obj.type)
                    return "<div class='separator'></div>";
                return "<span onclick='window.location.href="+obj.href+"' class='webix_icon alerts fa-"+obj.icon+"'></span><span>"+obj.value+"</span>";
            }
        }

    };

    function show_progress_bar(delay, position){
        $$("mainContent").disable();
        $$("mainContent").showProgress({
            type: position,
            delay:delay,
            hide:true
        });
        setTimeout(function(){
            $$("mainContent").enable();
        }, delay);
    }

    return {
        setUiScheme :  ui_scheme,
        setUiWindow :  ui_window,
        setUiPopup  :  ui_popup,
        ApiProvider : '',
        // storeInit  :  JSON.parse(xhr.response),
        webix : function(data){
            webix.ui({
                id : "mainContent",
                rows : [
                mainToolbar,
                {
                    id : "accordion",
                    responsive : "wrap",
                    view : "accordion",
                    type : "line",
                    cols : [
                        { css : "sidebar", header : "Menu", body : menu,  gravity : 0.22,},
                        {
                            id : "rowContent",
                            rows : [
                            { height :  44, id :  "title", css :  "title", template : "html->title",},
                            {
                                id : "content",
                                cols : [this.setUiScheme]
                            }
                        ]}
                    ]
                }
                ]
            });
            webix.ui(this.setUiWindow);
            webix.ui(this.setUiPopup);
            if (data) {
                 for (var key in data) {
                    webix.ui(data[key]);
                }
            }

            webix.attachEvent("onBeforeAjax", function(mode, url, params, x, headers){
               headers["Authorization"] = '44fff5b17102ad962481aea5975092dd';
            });

            webix.i18n.parseFormatDate = webix.Date.strToDate("%d %m %Y");
            webix.debug = true;
            webix.extend($$("mainContent"), webix.ProgressBar);
            // show_progress_bar(2000, "top");
        },

    };
});
