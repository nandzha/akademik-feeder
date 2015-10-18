define([
	"webix",
	"views/forms/msmhs",
	"views/menus/export"
], function(msmhsform, exports){
	var controls = [
		{ view: "button", type: "iconButton", icon: "plus", label: "Add Mahasiswa", width: 150, click: function(){
			this.$scope.ui(msmhsform.$ui).show();
		}},
		{ view: "button", type: "iconButton", icon: "external-link", label: "Export", width: 120, popup: exports},
		{},
		{view:"richselect", id:"order_filter", value: "all", maxWidth: 400, minWidth: 250, vertical: true, labelWidth: 100, options:[
			{id:"all", value:"All"},
			{id:"55201", value:"Teknik Informatika"},
			{id:"22201", value:"Teknik Sipil"},
			{id:"23201", value:"Teknik Arsitektur"},
			{id:"20401", value:"Teknik Elektro"},
			{id:"21402", value:"Teknik Mesin"},
			{id:"57401", value:"Manajemen Informatika"}
		],  label:"Filter Prodi", on:{
			onChange:function(){
				var val = this.getValue();
				if(val=="all")
					$$("msmhsData").filter("#KDPSTMSMHS#","");
				else
					$$("msmhsData").filter("#KDPSTMSMHS#",val);
			}
		}
		}
	];

	var grid = {
		margin:10,
		rows:[
			{
				id:"msmhsData",
				view:"datatable", select:true, editable:true, editaction:"dblclick",
				url: "http://localhost/core/api/msmhs",
				save:"http://localhost/core/api/msmhs/save",
				autoheight:true,
				columns:[
					{id:"NO", header:["NO"],width:50},
					{id:"NIMHSMSMHS", editor:"text", header:["Nim", {content:"textFilter"} ], sort:"string", minWidth:60, fillspace:1},
					{id:"NMMHSMSMHS", editor:"text", header:["Nama", {content:"textFilter"} ], sort:"string", minWidth:300, fillspace:1},
					{id:"TPLHRMSMHS", editor:"text", header:["Tpt.Lahir", {content:"textFilter"} ], sort:"string", minWidth:200, fillspace:1},
					{id:"TGLHRMSMHS", editor:"text", header:"Tgl.Lahir", sort:"string", width:100},
					{id:"KDJEKMSMHS", editor:"text", header:"JK", width:50, sort:"string"},
					{id:"TAHUNMSMHS", editor:"text", header:["Angkatan", {content:"selectFilter"}], sort:"string", minWidth:100, fillspace:1},
					{id:"id", editor:"text", header:"&nbsp;", width:35, template:"<span  style='color:#EB6E1A; cursor:pointer;' class='webix_icon fa-trash-o'></span>"}
				],
				export: true,
				pager:"pagerA",
				onClick:{
					webix_icon:function(e,id,node){
						webix.confirm({
							text:"The order will be deleted.<br/> Are you sure?", ok:"Yes", cancel:"Cancel",
							callback:function(res){
								if(res){
									webix.$$("msmhsData").remove(id);
								}
							}
						});
					}
				},
				on:{
			        "data->onStoreUpdated":function(){
			            this.data.each(function(obj, i){
			                obj.NO = i+1;
			            })
			        },
			        onBeforeLoad:function(){
						this.showOverlay("Loading...");
					},
					onAfterLoad:function(){
						if (!this.count()){
							webix.extend(this, webix.OverlayBox);
							this.showOverlay("<div style='margin:75px; font-size:20px;'>There is no data</div>");
						}else{
							this.hideOverlay();
						}
					}
			    }
			}
		]

	};

	function layout(){
		webix.ui({
			container:"ui",
			type: "space",
			css: "wrapper",
			multiview:{ fitBiggest:true },
			rows:[
				{
					height:40,
					cols:controls
				},
				{	
					rows:[
						grid,
						{
							view: "toolbar",
							css: "highlighted_header header6",
							paddingX:5,
							paddingY:5,
							height:40,
							cols:[{
								view:"pager", id:"pagerA",
								template:"{common.first()}{common.prev()}&nbsp; {common.pages()}&nbsp; {common.next()}{common.last()}",
								// autosize:true,
								size:50,
								height: 35,
								group:5
							}

							]
						}
					]
				}
			]
		});
	};

	return {
		init: function(url){
			webix.ui({
				container:"ui",
				type: "space",
				css: "wrapper",
				rows:[
					{
						height:40,
						cols:controls
					},
					{	
						rows:[
							grid,
							{
								view: "toolbar",
								css: "highlighted_header header6",
								paddingX:5,
								paddingY:5,
								height:40,
								cols:[{
									view:"pager", id:"pagerA",
									template:"{common.first()}{common.prev()}&nbsp; {common.pages()}&nbsp; {common.next()}{common.last()}",
									// autosize:true,
									size:50,
									height: 35,
									group:5
								}

								]
							}
						]
					}
				]
			});
		}
	};

});