define(function(){

	var chart = {
		view: "chart",
		type: "line",
		xAxis:{
			template: "#tahun#"
		},
		tooltip: {
			template: "#value# MHS"
		},
		minHeight:140,
		yAxis:{
			"start":0,
			"end": 500,
			"step": 100
		},
		offset: false,
		series:[
			{
				"value": "#value#",

				"item":{
					"borderColor": "#fff",
					"color": "#61b5ee",
					"radius": 4
				},
				"line":{
					"color":"#61b5ee",
					"width":1
				}
			}
		],
		padding:{
			"top": 25
		},
		data:[
			{"id": 1, "tahun": "2011", "value": 90},
			{"id": 2, "tahun": "2012", "value": 220},
			{"id": 3, "tahun": "2013", "value": 180},
			{"id": 4, "tahun": "2014", "value": 405},
			{"id": 5, "tahun": "2015", "value": 275}
		]
	};

	var donut1 = {
		view: "chart",
		css:"donut_result",
		type: "donut",
		shadow: false,
		pieInnerText: function(obj){
			return obj.result?"<div class='donut_result'>"+obj.value+"</div>":"";
		},
		padding:{
			left:10,
			right:10,
			top:10,
			bottom:10
		},
		data:[
			{value: 30, result:1},
			{value: 0, },
			{value: 7, },
			{value: 4, },
			{value: 3, },
			{value: 6, },
		]
	};

	var donut2 = {
		view: "chart",
		type: "donut",
		shadow: false,
		css:"donut_result",
		color: "#color#",
		padding:{
			left:10,
			right:10,
			top:10,
			bottom:10
		},
		pieInnerText: function(obj){
			return obj.result?"<div class='donut_result'>"+obj.value+"</div>":"";
		},
		data:[
			{value: 25, color: "#61b5ee",result:1},
			{value: 75, color: "#eee"}
		]
	};

	var donut3 = {
		view: "chart",
		type: "donut",
		css:"donut_result",
		shadow: false,
		color: "#color#",
		pieInnerText: function(obj){
			return obj.result?"<div class='donut_result'>"+obj.value+"</div>":"";
		},
		padding:{
			left:10,
			right:10,
			top:10,
			bottom:10
		},
		data:[
			{value: 45, color: "#61b5ee",result:1},
			{value: 55, color: "#eee"}
		]
	};

	return  {
		type: "clean",
		rows:[
			{
				"template": "<span class='webix_icon fa-bar-chart'></span>Profile Mahasiswa", "css": "sub_title", "height": 35
			},
			chart,
			{
				height: 150,
				type: "clean",
				cols:[
					donut1,
					donut2,
					donut3
				]
			},
			{
				height: 40,
				type: "clean",
				css: "donut_titles",
				cols:[
					{
						template: "Kec se-Kab.Wonosobo"
					},
					{
						template: "Kab / Kota"
					},
					{
						template: "Propinsi"
					}
				]
			}
		]
	};


});
