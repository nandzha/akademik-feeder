define(function(){

	return {
		height: 136,
		css: "tiles",
		template: function(data){
			var t = null;
			var items = data.items;
			var html = "<div class='flex_tmp'>";
			for(var i=0; i < items.length; i++){
				t = items[i];
				html += "<div class='item "+t.css+"'>";
				html += "<div class='webix_icon icon fa-"+ t.icon+"'></div>";
				html += "<div class='details'><div class='value'>"+t.value+"</div><div class='text'>"+t.text+"</div></div>";
				html += "<div class='footer'>"+t.desc+"</div>";
				html += "</div>";
			}
			html += "</div>";
			return html;
		},
		data: {
			items:[
				{id:1, desc:"Registrasi Mahasiswa", text: "MHS Registrasi", value: 250, icon: "check-square-o", css: "orders"},
				{id:2, desc:"Mahasiswa Status Aktif", text: "MHS Aktif", value: 300, icon: "user", css: "users"},
				{id:4, desc:"Mahasiswa Status Cuti", text: "MHS Cuti", value: 40, icon: "quote-right", css: "feedbacks"},
				{id:3, desc:"Mahasiswa Status Lulus", text: "MHS Lulus", value: "+25%", icon: "line-chart", css:"profit" }
			]
		}
	};

});
