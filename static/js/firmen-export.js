// MODAL BEHAVIOUR
$(function() {
	$("#export-modal .text-toggle").click(function() {
		$(this).toggleClass('active');
		$(this).find('.option').toggleClass('selected');
		// toggle preview text
		$(this).closest('.toggle-parent').find('p').toggle()
		// edit file-name preview
		$("#export-modal #filename ."+$(this).attr("f-key")).text($(this).find(".selected").attr("f-val"))
	})
	$("#type-toggle").click(function() {
		var new_type = $(this).find(".option.selected").attr("f-val");
		$("#export-modal #filename .type").text(new_type)
	})
})

// RETRIEVING DATA FROM THE TABLE
var table_dict = {
	"dienstleistung": "",
	"bundesland": "",
	"firmen": [],
}
function get_data() {
	table_dict["dienstleistung"] = $(".selector select[type='tag']").val()
	table_dict["hasDienstleistung"] = (table_dict["dienstleistung"] != "default")
	table_dict["bundesland"] = $(".selector select[type='bundesland']").val()
	table_dict["hasBundesland"] = (table_dict["bundesland"] != "default")
	table_dict["firmen"] = []
	console.log(table_dict)
	var dict_keys = []
	// fetch data
	$(".pro-table thead th").each(function() {
		dict_keys.push($(this).text().trim())
	})
	// populate dict with company data
	$(".pro-table tbody tr:visible").each(function(x, row) {
		// DOCX
		var firma = {
			"name": findTableData(row, "name"),
			"hasName": true,
			"rows": [
				{"name": "Adresse",  			"val": findTableData(row, "full-adress")},
				{"name": "Website",  			"val": findTableData(row, "website")},
				{"name": "Telefon",  			"val": findTableData(row, "phone")},
				{"name": "Email",  				"val": findTableData(row, "e-mail")}
			]
		}
		if ($("#export-modal .text-toggle[f-key='use'] .option[f-val='intern']").hasClass("selected")) {
			console.log("Attaching remaining fields...")
			var bonus_info = [
				{"name": "Leistungsfähigkeit",	"val": findTableData(row, "leistungsfahigkeit")},
				{"name": "Anmerkung",  			"val": findTableData(row, "anmkerung")},
				{"name": "Dienstleistung",		"val": findTableData(row, "tag")},
			]
			firma["rows"] = firma["rows"].concat(bonus_info)
		}
		var cleaned_rows = []
		firma["rows"].forEach(element => {
			if (element["val"] != "") {
				cleaned_rows.push(element)
			}
		});
		firma["rows"] = cleaned_rows
		table_dict["firmen"].push(firma)
	})
}
function findTableData(parent, fName) {
	var text = $(parent).find("*[f-name='"+fName+"']").text().trim();
	return text
}

$("#export").click(function () {
	const file_name = $("#filename").text();
	get_data()
	const file_type = $("#export-modal #filename .file-type").text()
	if (file_type == "docx") {
		var template_file = "http://webserver/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/docx/firmen."+file_type;
		loadFile(
			template_file,
			function (error, content) {
				if (error) {
					throw error;
				}
				generate(content, table_dict, file_name)
			}
		);
	}
	if (file_type == "xlsx") {
		var column_max_widths = {}
		// format data
		var converted_rows = [];
		table_dict["firmen"].forEach(firma => {
			firma["rows"].unshift({"name": "Name", "val": firma["name"]})
		})
		table_dict["firmen"].forEach(firma => {
			var firma_dict = {}
			firma["rows"].forEach(row => {
				firma_dict[row["name"]] = row["val"]
				// width calc
				if (!(row["name"] in column_max_widths)) {
					column_max_widths[row["name"]] = 0
				}
				if (typeof row["val"] !== "undefined") {
					const new_max = Math.max(row["name"].length, row["val"].length)
					if (new_max > column_max_widths[row["name"]]) {
						column_max_widths[row["name"]] = new_max
					}
				}
			})
			converted_rows.push(firma_dict)
		})
		// generate worksheet and workbook
		const worksheet = XLSX.utils.json_to_sheet(converted_rows);
		const workbook = XLSX.utils.book_new();
		XLSX.utils.book_append_sheet(workbook, worksheet, "Firmen");
		// max width for each column
		var converted_max_widths = []
		for (const [key, value] of Object.entries(column_max_widths)) {
			converted_max_widths.push({width: value})
		}
		console.log(converted_max_widths)
		worksheet["!cols"] = converted_max_widths;
		// create an XLSX file and try to
		XLSX.writeFile(workbook, file_name, { compression: true });
	}
});       
function loadFile(url, callback) {
	PizZipUtils.getBinaryContent(url, callback);
}
function generate(content, payload, file_name) {
	const zip = new PizZip(content);
	const doc = new window.docxtemplater(zip, {
		paragraphLoop: true,
		linebreaks: true,
	});
	doc.render(payload);
	const blob = doc.getZip().generate({
		type: "blob",
		mimeType: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		// compression: DEFLATE adds a compression step.
		// For a 50MB output document, expect 500ms additional CPU time
		compression: "DEFLATE",
	});
	// Output the document using Data-URI
	saveAs(blob, file_name);
}