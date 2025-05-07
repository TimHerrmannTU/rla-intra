var table_dict = {}
var excel_dict = {
	"rows": []
}
function get_data() {
	//////////
	// CATS //
	//////////
	table_dict["projekte"] = []
	var cats = []
	$("a[f-cat].active").each(function() {
		if ($(this).text() != "Alle") cats.push($(this).text())
	})
	table_dict["cat"] = cats.join(", ")
	excel_dict["cat"] = cats.join(", ")
	if (cats.length > 0) table_dict["hasCat"] = true
	var dict_keys = []
	
	//////////
	// DATA //
	//////////
	// which columns are relevant?
	var export_type = $("#export-settings input[name='export-type']:checked").val();
	var export_empty_fields = $("#export-settings #empty-fields").is(":checked");
	var jquery_expansion = "" // will select all fields or selected fields only
	if (export_type == "selected") {jquery_expansion = "[f-filtered='1']"}
	if (export_type == "all") {}
	if (export_type == "doku") {}
	console.log(jquery_expansion)
	$(".pro-table thead th[f-hidden='0']"+jquery_expansion).each(function() {
		dict_keys.push($(this).text().trim())
	})
	excel_dict["keys"] = dict_keys

	$(".pro-table .pro-row[f-filtered='1'][f-disabled='0']").each(function(x, row) {
		var name = ""
		var abstract = ""
		var row_array = []
		var excel_row = []
		// f-hidden='1' -> field is useless for users & the export
		$(row).find("td[f-hidden='0']"+jquery_expansion).each(function(y, cell) {
			$(cell).find("*").not(":visible").remove()
			var cell_val = $(cell).text().trim()
			var after = window.getComputedStyle($(cell)[0], "::after").content.replaceAll("\"", "")
			if (after != "none") cell_val += after
			excel_row.push(cell_val)
			if ($(cell).attr("f-index") == "0") {
				abstract = cell_val
				if (abstract != "") table_dict["hasAbstract"] = true
			}
			else if ($(cell).attr("f-index") == "Projektname") {
				name = cell_val
				if (name != "") table_dict["hasName"] = true
			}
			else {
				if (export_empty_fields || cell_val != "") {
					row_array.push({
						"name": dict_keys[y],
						"val": cell_val
					})
				}
			}
		})
		excel_dict["rows"].push(excel_row)
		table_dict["projekte"].push({"name": name, "abstract": abstract, "rows": row_array})
	})
	console.log(table_dict["projekte"][0])
	console.log(excel_dict)
}

$("#export").click(function () {
	get_data()
	if ($("#custom").prop("checked")) {
		$("#doc").click();
	}
	else {
		var file_type = $("#export-settings input[name='file-type']:checked").val();
		if (file_type == "docx") {
			loadFile(
				"http://webserver/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/docx/projekt.docx",
				function (error, content) {
					if (error) {
						throw error;
					}
					generate(content, table_dict, ".docx")
				}
			);
		}
		if (file_type == "xlsx") {
			var column_max_widths = {}
			// format data
			var converted_rows = [];
			table_dict["projekte"].forEach(pro => {
				pro["rows"].unshift({"name": "Name", "val": pro["name"]})
			})
			table_dict["projekte"].forEach(pro => {
				var pro_dict = {}
				pro["rows"].forEach(row => {
					pro_dict[row["name"]] = row["val"]
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
				converted_rows.push(pro_dict)
			})
			// generate worksheet and workbook
			const worksheet = XLSX.utils.json_to_sheet(converted_rows);
			const workbook = XLSX.utils.book_new();
			XLSX.utils.book_append_sheet(workbook, worksheet, "Projekte");
			// max width for each column
			var converted_max_widths = []
			for (const [key, value] of Object.entries(column_max_widths)) {
				converted_max_widths.push({width: value})
			}
			console.log(converted_max_widths)
			worksheet["!cols"] = converted_max_widths;
			// create an XLSX file and try to
			XLSX.writeFile(workbook, "Projekte.xlsx", { compression: true });
		}
	}
});

$("#doc").change(function() {
	const reader = new FileReader();
	reader.readAsBinaryString($(this).prop('files')[0])

	reader.onerror = function (evt) {
		console.log("error reading file", evt);
		alert("error reading file" + evt);
	};
	reader.onload = function (evt) {
		generate(evt.target.result)
	};
})        
function loadFile(url, callback) {
	PizZipUtils.getBinaryContent(url, callback);
}
function generate(content, payload, ending) {
	const zip = new PizZip(content);
	const doc = new window.docxtemplater(zip, {
		paragraphLoop: true,
		linebreaks: true,
	});
	doc.render(payload);
	const blob = doc.getZip().generate({
		type: "blob",
		mimeType:
			"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		// compression: DEFLATE adds a compression step.
		// For a 50MB output document, expect 500ms additional CPU time
		compression: "DEFLATE",
	});
	// Output the document using Data-URI
	saveAs(blob, "projekt_liste"+ending);
}