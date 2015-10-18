define(function(){
	return {
	    view:"datatable",
	    id:"grd_mata_kuliah",
	    columns:[
			{ id:"kode_mk", width: 100, header:[{
				content:"serverFilter", placeholder:"Kode MK",
			}]},
	        { id:"nm_mk", width: 250, header:[{
				content:"serverFilter", placeholder:"Nama MK",
			}]},
	        { id:"sks_mk", width: 80, header:[{
				content:"serverFilter", placeholder:"SKS MK",
			}]},
	        { id:"nm_jns_mk", width: 180, header:[{
				content:"serverFilter", placeholder:"Nama Jns MK",
			}]},
	        { id:"nm_kel_mk", width: 300,header:[{
				content:"serverFilter", placeholder:"Nama Kel MK",
			}]},
	    ],
	    select:"multiselect",
	    datafetch:30,
	    dataFeed : "./kuliah/mklst",
	    on:{
	        onAfterLoad:function(){
	        	var id = this.getFirstId();
	        	var record = this.getItem(id);
	            $$("lbl_win_detail").define("label", "MATAKULIAH KURIKULUM " + record.nm_prodi.toUpperCase() );
	            $$('lbl_win_detail').refresh();
	        }
	    }
	}
});
