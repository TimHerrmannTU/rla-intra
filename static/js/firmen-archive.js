jQuery(document).ready(function($) {
	// searchbar stuff
	$("#header .searchform").hide()
	$("#pro-search").val("")
	$("#pro-search").on("keyup", function() {
		filterCascade()
	})
	// create options for dropdowns dynamically
	generateOptionsFromKey("bundesland")
	generateOptionsFromKey("ort")
	// styling of the dd arrow
	$(".selector").click(function() {
		$(".selector .icon-down-open-2").removeClass("up")
		$(this).find("select:focus").closest(".selector").find(".icon-down-open-2").addClass("up")
	})
	$("option").click(function() {
		$(".selector .icon-down-open-2").removeClass("up")
	})
	// type toggle behaviour
	$("#type-toggle").click(function() {
		$(this).toggleClass("active");
		$(this).find(".option").toggleClass("selected");
		// changes to elements outside
		$("select[type='tag']").val("default"); // reset the tag dropdown
		$("#pro-search").val(""); // reset the serachbar
		$(".toggle-me").toggle(); // show elements of the other type
	})
	///////////////
	// FILTERING //
	///////////////
	var filters = {
		"tag": "default",
		"bundesland": "default",
		"ort": "default",
		"plz": "default"
	}
	$(".selector select").change(function() {
		filters[$(this).attr("type")] = $(this).val()
		filterCascade()
	})
	function filterCascade() {
		$(".firmen-liste tr").show() // reset row filters
		var mode = $("#type-toggle .option.selected").attr("f-val");
		var target_table = $(`.pro-table[ui='${mode}'] .firmen-liste tbody`);
		// lvl 1: dropdowns
		for (const [key, value] of Object.entries(filters)) {
			if (value != "default") {
				console.log(key)
				if (key == "tag") {
					$(target_table).find("tr").each(function() {
						if (!$(this).attr(key).includes(value)) {
							$(this).hide()
						}
					})
				}
				else {
					$(target_table).find("tr:not(["+key+"='"+value+"'])").hide()
				}
			}
		}
		// lvl 2: searchbar
		var input_val = $("#pro-search").val().trim().toLowerCase() 
		if (input_val != "") {
			$(target_table).find("tr:visible").each(function() {
				var render = false;
				$(this).find("td").each(function() {
					if ($(this).text().trim().toLowerCase().match(input_val)) {
						render = true
					}
				})
				if (!render) $(this).hide()
			})
		}
	}
	function generateOptionsFromKey(key) {
		var options = []
		$(`[f-name='${key}']`).each(function() {
			options.push($(this).text())
		})
		options = [...new Set(options)].sort() // remvoes duplicates from the array
		options.forEach(option => {
			if (option != "") {
				var newOption = `<option value='${option}'>${option}</option>`
				$(`select[type='${key}']`).append(newOption)
			}
		});
	}
})